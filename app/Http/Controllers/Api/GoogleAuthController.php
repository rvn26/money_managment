<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Google\Auth\AccessToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleAuthController extends AuthController
{
    /**
     * Login atau register menggunakan Google ID Token dari Flutter.
     *
     * Flutter mengirim `id_token` yang diperoleh dari Google Sign-In SDK.
     * Backend memverifikasi token tersebut, lalu membuat atau mengambil user
     * yang sesuai dan mengembalikan JWT access token.
     */
    public function loginWithGoogle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Verifikasi Google ID Token menggunakan google/auth library
        $tokenVerifier = app(AccessToken::class);

        try {
            $payload = $tokenVerifier->verify(
                $request->input('id_token'),
                ['certsLocation' => AccessToken::IAP_CERT_URL]
            );
        } catch (\Exception $e) {
            // Coba lagi tanpa opsi tambahan jika gagal
            try {
                $payload = $tokenVerifier->verify($request->input('id_token'));
            } catch (\Exception $e) {
                return $this->sendError('Token Google tidak valid.', [
                    'error' => 'ID Token tidak dapat diverifikasi: '.$e->getMessage(),
                ], 401);
            }
        }

        if (! $payload || empty($payload['email'])) {
            return $this->sendError('Token Google tidak valid.', [
                'error' => 'Payload token tidak mengandung email.',
            ], 401);
        }

        // Validasi audience (client_id) jika dikonfigurasi
        $configuredClientId = config('services.google.client_id');
        if ($configuredClientId && isset($payload['aud']) && $payload['aud'] !== $configuredClientId) {
            return $this->sendError('Token Google tidak valid.', [
                'error' => 'Audience token tidak cocok dengan client ID yang terkonfigurasi.',
            ], 401);
        }

        // Ambil data dari payload Google
        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? ($payload['given_name'] ?? 'User');

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update google_id jika belum diset
            if (! $user->google_id) {
                $user->update(['google_id' => $googleId]);
            }

            // Verifikasi email jika belum terverifikasi
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        } else {
            // Buat user baru
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(24)),
            ]);

            $user->markEmailAsVerified();
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        $success = $this->respondWithToken($token);
        $success['user'] = $user;

        return $this->sendResponse($success, 'Login dengan Google berhasil.');
    }
}
