<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use App\Models\Otp;
use App\Services\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Send OTP to phone number.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string',
            'country_code' => 'required|string',
        ]);

        $phoneNumber = $request->phone_number;
        $countryCode = $request->country_code;

        // Generate random 4-digit OTP (master OTP 2526 for testing)
        $otp = (string) rand(1000, 9999);
        $verificationId = Str::uuid()->toString();

        // Remove any existing OTPs for this phone number
        Otp::where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->delete();

        // Store OTP
        Otp::create([
            'phone_number' => $phoneNumber,
            'country_code' => $countryCode,
            'otp' => $otp,
            'verification_id' => $verificationId,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Log that real SMS would be sent in production
        \Log::info("OTP for {$countryCode}{$phoneNumber}: {$otp} (In production, SMS would be sent)");

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.',
            'data' => [
                'verification_id' => $verificationId,
            ],
        ]);
    }

    /**
     * Verify OTP and login/register.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'verification_id' => 'required|string',
            'otp' => 'required|string',
            'phone_number' => 'required|string',
            'country_code' => 'required|string',
        ]);

        $masterOtp = '2526';
        $isValid = false;

        if ($request->otp === $masterOtp) {
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

        // Clean up used OTP
        Otp::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->delete();

        // Look up by phone number alone — it is the account identity. Matching
        // on country_code too previously created duplicates whenever it was null
        // (in SQL `country_code = NULL` never matches, so the existence check
        // always failed and a new row was inserted on every login). firstOrCreate
        // is also atomic against the unique phone_number index, so concurrent OTP
        // verifications can't create duplicate accounts.
        $driver = DriverUser::firstOrCreate(
            ['phone_number' => $request->phone_number],
            [
                'country_code' => $request->country_code,
                'login_type' => 'phone',
                'register_ip' => $request->ip(),
                'is_online' => false,
                'document_verification' => false,
                'wallet_amount' => 0,
                'reviews_count' => 0,
                'reviews_sum' => 0,
                // New drivers start with no ride quota — they must recharge
                // before they can go online and receive ride requests.
                'remaining_rides' => 0,
                'total_rides' => 0,
            ]
        );

        $isNewUser = $driver->wasRecentlyCreated;

        // Record the IP this login came from for admin auditing/filtering.
        $driver->last_login_ip = $request->ip();
        $driver->save();

        // Create Sanctum token
        $token = $driver->createToken('driver-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'driver' => $driver,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /**
     * Firebase phone-auth login.
     *
     * The driver app performs Firebase phone verification on-device (Firebase
     * sends the OTP SMS), then posts the resulting Firebase ID token here. We
     * verify the token, firstOrCreate the driver keyed on the verified phone
     * number, and return the same Sanctum response shape as verifyOtp().
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

        // The phone number Firebase verified is the source of truth. We only
        // trust the app's split country_code/phone_number when they reconstruct
        // the verified E.164 number — otherwise a client could verify one number
        // and claim another.
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
        $driver = DriverUser::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'country_code' => $countryCode,
                'login_type' => 'phone',
                'register_ip' => $request->ip(),
                'is_online' => false,
                'document_verification' => false,
                'wallet_amount' => 0,
                'reviews_count' => 0,
                'reviews_sum' => 0,
                // New drivers start with no ride quota — they must recharge
                // before they can go online and receive ride requests.
                'remaining_rides' => 0,
                'total_rides' => 0,
            ]
        );

        $isNewUser = $driver->wasRecentlyCreated;

        $driver->last_login_ip = $request->ip();
        $driver->save();

        $token = $driver->createToken('driver-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'driver' => $driver,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /**
     * Strip everything but digits, so a split country_code + phone_number can be
     * compared against Firebase's verified E.164 number.
     */
    private function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    /**
     * Social login (Google/Apple).
     */
    public function socialLogin(Request $request): JsonResponse
    {
        $request->validate([
            'login_type' => 'required|string|in:google,apple',
            'email' => 'required|email',
            'full_name' => 'nullable|string',
            'profile_pic' => 'nullable|string',
            'id' => 'required|string',
        ]);

        // Social accounts are keyed on email; firstOrCreate avoids duplicate
        // accounts if the same social login is verified twice concurrently.
        $driver = DriverUser::firstOrCreate(
            ['email' => $request->email],
            [
                'id' => $request->id,
                'full_name' => $request->full_name,
                'profile_pic' => $request->profile_pic,
                'login_type' => $request->login_type,
                'register_ip' => $request->ip(),
                'is_online' => false,
                'document_verification' => false,
                'wallet_amount' => 0,
                'reviews_count' => 0,
                'reviews_sum' => 0,
                // New drivers start with no ride quota — they must recharge
                // before they can go online and receive ride requests.
                'remaining_rides' => 0,
                'total_rides' => 0,
            ]
        );
        $isNewUser = $driver->wasRecentlyCreated;

        // Record the IP this login came from for admin auditing/filtering.
        $driver->last_login_ip = $request->ip();
        $driver->save();

        $token = $driver->createToken('driver-auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => $isNewUser ? 'Registration successful.' : 'Login successful.',
            'data' => [
                'driver' => $driver,
                'token' => $token,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /**
     * Logout - revoke current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'data' => null,
        ]);
    }

    /**
     * Delete driver account.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $driver = $request->user();

        // Revoke all tokens
        $driver->tokens()->delete();

        // Delete driver record
        $driver->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
            'data' => null,
        ]);
    }
}
