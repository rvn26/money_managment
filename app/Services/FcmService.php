<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\Notifikasi;
use App\Models\User;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Kirim notifikasi FCM ke semua device milik user dan simpan riwayat.
     *
     * @param  array<string, mixed>  $data  Payload tambahan (misal ['pertemanan_id' => 1])
     */
    public function sendToUser(User $user, string $judul, string $pesan, string $tipe, array $data = []): void
    {
        // Simpan riwayat notifikasi ke database
        Notifikasi::create([
            'id_user' => $user->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'data' => $data,
        ]);

        // Ambil semua FCM token milik user
        $tokens = $user->fcmTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("FCM: User #{$user->id} tidak memiliki device token, skip push.");

            return;
        }

        foreach ($tokens as $token) {
            $this->sendToToken($token, $judul, $pesan, $tipe, $data, $user->id);
        }
    }

    /**
     * Kirim notifikasi FCM ke satu device token.
     */
    public function sendToToken(string $token, string $judul, string $pesan, string $tipe, array $data = [], ?int $userId = null): void
    {
        try {
            $accessToken = $this->getAccessToken();
            $projectId = config('firebase.project_id');

            if (! $accessToken || ! $projectId) {
                Log::warning('FCM: Firebase credentials belum dikonfigurasi.');

                return;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $judul,
                        'body' => $pesan,
                    ],
                    'data' => array_merge($data, [
                        'tipe' => $tipe,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ]),
                ],
            ];

            // Pastikan semua value di data adalah string (FCM requirement)
            $payload['message']['data'] = array_map('strval', $payload['message']['data']);

            $response = Http::withToken($accessToken)
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('FCM: Notifikasi berhasil dikirim ke token: ...'.substr($token, -10));
            } else {
                $error = $response->json();
                Log::error('FCM: Gagal mengirim notifikasi.', [
                    'status' => $response->status(),
                    'error' => $error,
                ]);

                // Hapus token yang tidak valid (NOT_FOUND atau UNREGISTERED)
                if ($this->isInvalidToken($error)) {
                    $this->removeInvalidToken($token, $userId);
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM: Exception saat mengirim notifikasi: '.$e->getMessage());
        }
    }

    /**
     * Dapatkan OAuth2 access token dari service account credentials.
     */
    private function getAccessToken(): ?string
    {
        $credentialsPath = config('firebase.credentials');

        if (! $credentialsPath || ! file_exists($credentialsPath)) {
            Log::warning('FCM: Service account file tidak ditemukan: '.$credentialsPath);

            return null;
        }

        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/firebase.messaging',
            json_decode(file_get_contents($credentialsPath), true)
        );

        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? null;
    }

    /**
     * Cek apakah error response menandakan token sudah tidak valid.
     *
     * @param  array<string, mixed>|null  $error
     */
    private function isInvalidToken(?array $error): bool
    {
        if (! $error) {
            return false;
        }

        $errorCode = $error['error']['details'][0]['errorCode'] ?? '';
        $status = $error['error']['status'] ?? '';

        return in_array($errorCode, ['UNREGISTERED', 'INVALID_ARGUMENT'])
            || $status === 'NOT_FOUND';
    }

    /**
     * Hapus token yang sudah tidak valid dari database.
     */
    private function removeInvalidToken(string $token, ?int $userId): void
    {
        $query = FcmToken::where('token', $token);

        if ($userId) {
            $query->where('id_user', $userId);
        }

        $deleted = $query->delete();

        if ($deleted) {
            Log::info('FCM: Token invalid dihapus: ...'.substr($token, -10));
        }
    }
}
