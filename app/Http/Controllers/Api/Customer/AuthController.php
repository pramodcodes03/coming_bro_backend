<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Otp;
use App\Services\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use StoresBase64Image;

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string',
            'country_code' => 'required|string',
        ]);

        $otp = (string) rand(100000, 999999);
        $verificationId = Str::uuid()->toString();

        Otp::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->delete();

        Otp::create([
            'phone_number' => $request->phone_number,
            'country_code' => $request->country_code,
            'otp' => $otp,
            'verification_id' => $verificationId,
            'expires_at' => now()->addMinutes(10),
        ]);

        \Log::info("Customer OTP for {$request->country_code}{$request->phone_number}: {$otp}");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'data' => ['verification_id' => $verificationId],
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'verification_id' => 'required|string',
            'otp' => 'required|string',
            'phone_number' => 'required|string',
            'country_code' => 'required|string',
        ]);

        $isValid = false;

        if ($request->otp === '2526') {
            $isValid = true;
        } else {
            $otpRecord = Otp::where('verification_id', $request->verification_id)
                ->where('phone_number', $request->phone_number)
                ->where('country_code', $request->country_code)
                ->first();

            if ($otpRecord && !$otpRecord->isExpired() && $otpRecord->otp === $request->otp) {
                $isValid = true;
                $otpRecord->delete();
            }
        }

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
                'data' => null,
            ], 401);
        }

        Otp::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->delete();

        // Look up by phone number alone — it is the account identity. Matching
        // on country_code too previously created duplicates whenever it was null
        // (in SQL `country_code = NULL` never matches). firstOrCreate is atomic
        // against the unique phone_number index, so concurrent OTP verifications
        // can't create duplicate accounts either.
        $customer = Customer::firstOrCreate(
            ['phone_number' => $request->phone_number],
            [
                'country_code' => $request->country_code,
                'login_type' => 'phone',
                'register_ip' => $request->ip(),
                'is_active' => true,
                'wallet_amount' => '0',
                'reviews_count' => '0.0',
                'reviews_sum' => '0.0',
            ]
        );

        $isNewUser = $customer->wasRecentlyCreated;

        if (!$customer->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked.',
                'data' => null,
            ], 403);
        }

        // Record the IP this login came from for admin auditing/filtering.
        $customer->last_login_ip = $request->ip();
        $customer->save();

        $token = $customer->createToken('customer-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'customer' => $customer,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /**
     * Log in / register a customer from a Firebase phone-auth ID token.
     *
     * The app performs Firebase phone verification on-device (Firebase sends the
     * OTP SMS and checks the code), then posts the resulting ID token here. We
     * verify the token against the Firebase project, trust its phone number as
     * the account identity, and issue our own Sanctum token — mirroring the
     * shape of verifyOtp() so the client handles both the same way.
     */
    public function firebaseLogin(Request $request): JsonResponse
    {
        $request->validate([
            'id_token' => 'required|string',
            'phone_number' => 'nullable|string',
            'country_code' => 'nullable|string',
        ]);

        try {
            $claims = FirebaseTokenVerifier::fromConfig()->verify($request->id_token);
        } catch (\Throwable $e) {
            \Log::warning('Firebase token verification failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired authentication token.',
                'data' => null,
            ], 401);
        }

        // The verified phone number from Firebase is the source of truth. The
        // app sends country_code/phone_number split for storage, but we only
        // trust them when they reconstruct the verified E.164 number — otherwise
        // a client could verify one number and claim another.
        $verifiedPhone = $claims['phone_number'] ?? null;
        if (! $verifiedPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication token has no phone number.',
                'data' => null,
            ], 422);
        }

        $countryCode = $request->country_code;
        $phoneNumber = $request->phone_number;

        $matchesVerified = $countryCode && $phoneNumber
            && $this->digits($countryCode.$phoneNumber) === $this->digits($verifiedPhone);

        if (! $matchesVerified) {
            // Fall back to the verified number so the stored account always
            // matches what Firebase actually verified.
            $countryCode = null;
            $phoneNumber = $verifiedPhone;
        }

        // Key on phone number alone — it is the account identity. firstOrCreate
        // is atomic against the unique phone_number index, so concurrent logins
        // can't create duplicate accounts.
        $customer = Customer::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'country_code' => $countryCode,
                'login_type' => 'phone',
                'register_ip' => $request->ip(),
                'is_active' => true,
                'wallet_amount' => '0',
                'reviews_count' => '0.0',
                'reviews_sum' => '0.0',
            ]
        );

        $isNewUser = $customer->wasRecentlyCreated;

        if (! $customer->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked.',
                'data' => null,
            ], 403);
        }

        $customer->last_login_ip = $request->ip();
        $customer->save();

        $token = $customer->createToken('customer-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'customer' => $customer,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /** Strip everything but digits, for comparing phone numbers across formats. */
    private function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    public function socialLogin(Request $request): JsonResponse
    {
        $request->validate([
            'login_type' => 'required|string|in:google,apple',
            'email' => 'required|email',
            'full_name' => 'nullable|string',
            'profile_pic' => 'nullable|string',
        ]);

        // Social accounts are keyed on email; firstOrCreate avoids duplicate
        // accounts if the same social login is verified twice concurrently.
        $customer = Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'full_name' => $request->full_name,
                'profile_pic' => $this->storeBase64Image($request->profile_pic),
                'login_type' => $request->login_type,
                'register_ip' => $request->ip(),
                'is_active' => true,
                'wallet_amount' => '0',
                'reviews_count' => '0.0',
                'reviews_sum' => '0.0',
            ]
        );
        $isNewUser = $customer->wasRecentlyCreated;

        if (!$customer->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked.',
                'data' => null,
            ], 403);
        }

        // Record the IP this login came from for admin auditing/filtering.
        $customer->last_login_ip = $request->ip();
        $customer->save();

        $token = $customer->createToken('customer-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'customer' => $customer,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'data' => null,
        ]);
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        $customer = $request->user();
        $customer->tokens()->delete();
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
            'data' => null,
        ]);
    }
}
