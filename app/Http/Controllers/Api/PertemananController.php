<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pertemanan;
use App\Models\User;
use App\Services\FcmService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PertemananController extends Controller
{
    /**
     * Get daftar teman yang sudah accepted.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $cari = $request->query('cari');

            $temanList = Pertemanan::with(['user', 'teman'])
                ->where('status', 'accepted')
                ->where(function ($q) use ($user) {
                    $q->where('id_user', $user->id)->orWhere('id_teman', $user->id);
                })
                ->when($cari, function ($q) use ($user, $cari) {
                    $q->where(function ($qq) use ($cari, $user) {
                        $qq->whereHas('user', function ($u) use ($cari, $user) {
                            $u->where('id', '!=', $user->id)
                                ->where(function ($w) use ($cari) {
                                    $w->where('name', 'like', "%{$cari}%")
                                        ->orWhere('email', 'like', "%{$cari}%");
                                });
                        })->orWhereHas('teman', function ($t) use ($cari, $user) {
                            $t->where('id', '!=', $user->id)
                                ->where(function ($w) use ($cari) {
                                    $w->where('name', 'like', "%{$cari}%")
                                        ->orWhere('email', 'like', "%{$cari}%");
                                });
                        });
                    });
                })
                ->latest()
                ->get()
                ->map(function ($pertemanan) use ($user) {
                    $userAsli = $pertemanan->user;
                    $temanAsli = $pertemanan->teman;

                    if ($pertemanan->id_teman === $user->id) {
                        $pertemanan->id_user = $temanAsli->id;
                        $pertemanan->id_teman = $userAsli->id;

                        $pertemanan->setRelation('user', $temanAsli);
                        $pertemanan->setRelation('teman', $userAsli);
                    }

                    return $pertemanan;
                });

            return $this->sendResponse($temanList, 'Daftar teman berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil daftar teman: ' . $e->getMessage());

            return $this->sendError('Gagal mengambil daftar teman.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get daftar permintaan pertemanan yang masuk (pending).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permintaanMasuk()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $permintaanMasuk = Pertemanan::with('user')
                ->where('id_teman', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->get();

            return $this->sendResponse($permintaanMasuk, 'Permintaan masuk berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil permintaan masuk: ' . $e->getMessage());

            return $this->sendError('Gagal mengambil permintaan masuk.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get daftar permintaan pertemanan yang terkirim (pending).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permintaanTerkirim()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $permintaanTerkirim = Pertemanan::with('teman')
                ->where('id_user', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->get();

            return $this->sendResponse($permintaanTerkirim, 'Permintaan terkirim berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil permintaan terkirim: ' . $e->getMessage());

            return $this->sendError('Gagal mengambil permintaan terkirim.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Kirim permintaan pertemanan ke pengguna lain berdasarkan email.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function kirim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $teman = User::where('email', $request->email)->firstOrFail();

            if ($teman->id === $user->id) {
                return $this->sendError(
                    'Tidak bisa berteman dengan diri sendiri.',
                    ['email' => ['Tidak bisa berteman dengan diri sendiri.']],
                    422
                );
            }

            $sudahAda = Pertemanan::query()
                ->where(function ($q) use ($user, $teman) {
                    $q->where('id_user', $user->id)->where('id_teman', $teman->id);
                })
                ->orWhere(function ($q) use ($user, $teman) {
                    $q->where('id_user', $teman->id)->where('id_teman', $user->id);
                })
                ->exists();

            if ($sudahAda) {
                return $this->sendError(
                    'Permintaan pertemanan sudah ada atau kalian sudah berteman.',
                    ['email' => ['Permintaan pertemanan sudah ada atau kalian sudah berteman.']],
                    422
                );
            }

            $pertemanan = new Pertemanan;
            $pertemanan->id_user = $user->id;
            $pertemanan->id_teman = $teman->id;
            $pertemanan->status = 'pending';
            $pertemanan->save();

            // Kirim notifikasi FCM ke teman yang menerima permintaan
            app(FcmService::class)->sendToUser(
                $teman,
                'Permintaan Pertemanan',
                "{$user->name} mengirim permintaan pertemanan.",
                'pertemanan',
                ['pertemanan_id' => (string) $pertemanan->id, 'aksi' => 'permintaan_masuk']
            );

            return $this->sendResponse($pertemanan->load(['user', 'teman']), 'Permintaan pertemanan berhasil dikirim.', 201);
        } catch (Exception $e) {
            Log::error('Gagal kirim permintaan pertemanan: ' . $e->getMessage());

            return $this->sendError('Gagal mengirim permintaan pertemanan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Terima permintaan pertemanan yang masuk.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function terima($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $pertemanan = Pertemanan::where('id', $id)
                ->where('id_teman', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$pertemanan) {
                return $this->sendError('Permintaan pertemanan tidak ditemukan.', [], 404);
            }

            $pertemanan->status = 'accepted';
            $pertemanan->save();

            // Kirim notifikasi FCM ke pengirim permintaan bahwa permintaannya diterima
            $pengirim = User::find($pertemanan->id_user);
            if ($pengirim) {
                app(FcmService::class)->sendToUser(
                    $pengirim,
                    'Pertemanan Diterima',
                    "{$user->name} menerima permintaan pertemanan kamu.",
                    'pertemanan',
                    ['pertemanan_id' => (string) $pertemanan->id, 'aksi' => 'permintaan_diterima']
                );
            }

            return $this->sendResponse($pertemanan->load(['user', 'teman']), 'Permintaan pertemanan berhasil diterima.');
        } catch (Exception $e) {
            Log::error('Gagal terima pertemanan: ' . $e->getMessage());

            return $this->sendError('Gagal menerima permintaan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Multifungsi: batalkan permintaan terkirim, tolak permintaan masuk,
     * atau hapus teman yang sudah accepted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hapus($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $pertemanan = Pertemanan::where('id', $id)
                ->where(function ($q) use ($user) {
                    $q->where('id_user', $user->id)->orWhere('id_teman', $user->id);
                })
                ->first();

            if (!$pertemanan) {
                return $this->sendError('Pertemanan tidak ditemukan.', [], 404);
            }

            // Kirim notifikasi jika status masih pending (tolak permintaan)
            if ($pertemanan->status === 'pending') {
                $penerima = $pertemanan->id_user === $user->id
                    ? User::find($pertemanan->id_teman)
                    : User::find($pertemanan->id_user);

                if ($penerima) {
                    app(FcmService::class)->sendToUser(
                        $penerima,
                        'Permintaan Pertemanan',
                        "{$user->name} membatalkan permintaan pertemanan.",
                        'pertemanan',
                        ['pertemanan_id' => (string) $pertemanan->id, 'aksi' => 'permintaan_dibatalkan']
                    );
                }
            }

            $pertemanan->delete();

            return $this->sendResponse([], 'Pertemanan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus pertemanan: ' . $e->getMessage());

            return $this->sendError('Gagal menghapus.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Cari pengguna berdasarkan email untuk ditambahkan sebagai teman.
     * Endpoint helper untuk mencari user sebelum kirim permintaan.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cariUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $targetUser = User::where('email', $request->email)->first();

            if (!$targetUser) {
                return $this->sendError('Pengguna tidak ditemukan.', ['email' => ['Pengguna dengan email tersebut tidak ditemukan.']], 404);
            }

            if ($targetUser->id === $user->id) {
                return $this->sendError('Tidak bisa berteman dengan diri sendiri.', ['email' => ['Tidak bisa berteman dengan diri sendiri.']], 422);
            }

            // Check jika sudah ada hubungan pertemanan
            $sudahAda = Pertemanan::query()
                ->where(function ($q) use ($user, $targetUser) {
                    $q->where('id_user', $user->id)->where('id_teman', $targetUser->id);
                })
                ->orWhere(function ($q) use ($user, $targetUser) {
                    $q->where('id_user', $targetUser->id)->where('id_teman', $user->id);
                })
                ->first();

            $status = null;
            if ($sudahAda) {
                $status = $sudahAda->status;
            }

            return $this->sendResponse([
                'user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'email' => $targetUser->email,
                ],
                'friendship_status' => $status,
                'can_send_request' => $status === null,
            ], 'Pengguna ditemukan.');
        } catch (Exception $e) {
            Log::error('Gagal cari user: ' . $e->getMessage());

            return $this->sendError('Gagal mencari pengguna.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a success response (same format as AuthController).
     */
    protected function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'statuscode' => $code,
            'msg' => $message,
            'data' => $result,
        ], $code);
    }

    /**
     * Send an error response (same format as AuthController).
     */
    protected function sendError($error, $errorMessages = [], $code = 401)
    {
        return response()->json([
            'statuscode' => $code,
            'msg' => $error,
            'data' => $errorMessages,
        ], $code);
    }
}
