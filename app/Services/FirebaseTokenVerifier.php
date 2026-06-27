<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Verifies Firebase Authentication ID tokens server-side without the Firebase
 * Admin SDK.
 *
 * The Flutter app keeps doing Firebase phone auth (Firebase sends the OTP SMS
 * and verifies the code on-device). After sign-in it forwards the resulting
 * Firebase ID token to this backend, which validates it here and then issues
 * its own Sanctum token. This lets us "keep Firebase for SMS" while Laravel
 * owns the customer accounts and API auth.
 *
 * A Firebase ID token is an RS256-signed JWT. Validation per Google's spec:
 *   https://firebase.google.com/docs/auth/admin/verify-id-tokens#verify_id_tokens_using_a_third-party_jwt_library
 *   - header.alg  == "RS256"
 *   - signature verified with the public cert whose kid matches header.kid
 *     (certs published at the securetoken x509 endpoint, honouring max-age)
 *   - aud == project id
 *   - iss == https://securetoken.google.com/<project id>
 *   - exp in the future, iat/auth_time in the past (small leeway for clock skew)
 *   - sub (the Firebase uid) non-empty
 */
class FirebaseTokenVerifier
{
    /** Google's public x509 certs for Firebase ID tokens. */
    private const CERT_URL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';

    /** Cache key for the fetched certs. */
    private const CERT_CACHE_KEY = 'firebase_securetoken_certs';

    /** Allowed clock skew, in seconds. */
    private const LEEWAY = 60;

    public function __construct(private readonly string $projectId)
    {
    }

    public static function fromConfig(): self
    {
        $projectId = (string) config('services.firebase.project_id');

        if ($projectId === '') {
            throw new RuntimeException('Firebase project id is not configured (services.firebase.project_id).');
        }

        return new self($projectId);
    }

    /**
     * Verify the token and return its decoded claims.
     *
     * @throws RuntimeException if the token is malformed, expired, or otherwise invalid.
     */
    public function verify(string $idToken): array
    {
        [$header, $payload, $signature, $signedPart] = $this->decode($idToken);

        if (($header['alg'] ?? null) !== 'RS256') {
            throw new RuntimeException('Unexpected token algorithm.');
        }

        $kid = $header['kid'] ?? null;
        if (! $kid) {
            throw new RuntimeException('Token is missing a key id.');
        }

        $certificate = $this->certificateFor($kid);
        if (! $certificate) {
            throw new RuntimeException('No matching public key for token.');
        }

        $publicKey = openssl_pkey_get_public($certificate);
        if ($publicKey === false) {
            throw new RuntimeException('Unable to read Firebase public key.');
        }

        $verified = openssl_verify($signedPart, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($verified !== 1) {
            throw new RuntimeException('Token signature verification failed.');
        }

        $this->validateClaims($payload);

        return $payload;
    }

    /** Split the JWT and base64url-decode its parts. */
    private function decode(string $idToken): array
    {
        $segments = explode('.', $idToken);
        if (count($segments) !== 3) {
            throw new RuntimeException('Malformed token.');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $segments;

        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        $signature = $this->base64UrlDecode($encodedSignature);

        if (! is_array($header) || ! is_array($payload)) {
            throw new RuntimeException('Malformed token contents.');
        }

        return [$header, $payload, $signature, $encodedHeader.'.'.$encodedPayload];
    }

    private function validateClaims(array $payload): void
    {
        $now = time();

        $expectedIss = 'https://securetoken.google.com/'.$this->projectId;

        if (($payload['aud'] ?? null) !== $this->projectId) {
            throw new RuntimeException('Token audience mismatch.');
        }

        if (($payload['iss'] ?? null) !== $expectedIss) {
            throw new RuntimeException('Token issuer mismatch.');
        }

        if (! isset($payload['exp']) || $payload['exp'] <= ($now - self::LEEWAY)) {
            throw new RuntimeException('Token has expired.');
        }

        if (isset($payload['iat']) && $payload['iat'] > ($now + self::LEEWAY)) {
            throw new RuntimeException('Token issued-at is in the future.');
        }

        if (isset($payload['auth_time']) && $payload['auth_time'] > ($now + self::LEEWAY)) {
            throw new RuntimeException('Token auth-time is in the future.');
        }

        if (empty($payload['sub'])) {
            throw new RuntimeException('Token is missing a subject (uid).');
        }
    }

    /** Return the PEM certificate for the given key id, fetching/caching as needed. */
    private function certificateFor(string $kid): ?string
    {
        $certs = $this->certificates();

        return $certs[$kid] ?? null;
    }

    /**
     * Fetch Google's current signing certs, cached until their max-age expires.
     *
     * @return array<string,string> map of kid => PEM certificate
     */
    private function certificates(): array
    {
        $cached = Cache::get(self::CERT_CACHE_KEY);
        if (is_array($cached) && $cached !== []) {
            return $cached;
        }

        $response = Http::timeout(10)->get(self::CERT_URL);
        if (! $response->successful()) {
            throw new RuntimeException('Unable to fetch Firebase public keys.');
        }

        $certs = $response->json();
        if (! is_array($certs) || $certs === []) {
            throw new RuntimeException('Firebase public keys response was empty.');
        }

        // Cache until just before Google rotates the certs (Cache-Control max-age).
        $ttl = $this->maxAge($response->header('Cache-Control')) ?? 3600;
        Cache::put(self::CERT_CACHE_KEY, $certs, max(60, $ttl - self::LEEWAY));

        return $certs;
    }

    /** Parse max-age (seconds) out of a Cache-Control header, if present. */
    private function maxAge(?string $cacheControl): ?int
    {
        if (! $cacheControl) {
            return null;
        }

        if (preg_match('/max-age=(\d+)/', $cacheControl, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;
        if ($remainder) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);
        if ($decoded === false) {
            throw new RuntimeException('Invalid base64url segment in token.');
        }

        return $decoded;
    }
}
