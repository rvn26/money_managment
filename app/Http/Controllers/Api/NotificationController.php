<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    /**
     * Get daftar notifikasi user (paginated).
     *
     * Query params:
     *  - filter: semua (default) | dibaca | belum_dibaca
     *  - limit: jumlah per halaman (default 15, max 100)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 15), 100);
            $filter = $request->query('filter', 'semua');

            $query = Notifikasi::where('id_user', $user->id);

            match ($filter) {
                'dibaca' => $query->sudahDibaca(),
                'belum_dibaca' => $query->belumDibaca(),
                'semua' => null,
                default => throw new \InvalidArgumentException("Filter '$filter' tidak valid. Gunakan: semua, dibaca, belum_dibaca."),
            };

            $notifikasi = $query->latest()->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Daftar notifikasi berhasil diambil.',
                'filter' => $filter,
                'data' => $notifikasi->items(),
                'pagination' => [
                    'current_page' => $notifikasi->currentPage(),
                    'last_page' => $notifikasi->lastPage(),
                    'per_page' => $notifikasi->perPage(),
                    'total' => $notifikasi->total(),
                ],
            ], 200);
        } catch (\InvalidArgumentException $e) {
            return $this->sendError($e->getMessage(), [], 422);
        } catch (Exception $e) {
            Log::error('Gagal mengambil notifikasi: '.$e->getMessage());

            return $this->sendError('Gagal mengambil notifikasi.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get jumlah notifikasi yang belum dibaca.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function belumDibaca()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $count = Notifikasi::where('id_user', $user->id)
                ->belumDibaca()
                ->count();

            return $this->sendResponse(['count' => $count], 'Jumlah notifikasi belum dibaca.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil jumlah notifikasi: '.$e->getMessage());

            return $this->sendError('Gagal mengambil jumlah notifikasi.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function baca($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $notifikasi = Notifikasi::where('id_user', $user->id)->find($id);

            if (! $notifikasi) {
                return $this->sendError('Notifikasi tidak ditemukan.', [], 404);
            }

            $notifikasi->dibaca_at = now();
            $notifikasi->save();

            return $this->sendResponse($notifikasi, 'Notifikasi ditandai sudah dibaca.');
        } catch (Exception $e) {
            Log::error('Gagal menandai notifikasi: '.$e->getMessage());

            return $this->sendError('Gagal menandai notifikasi.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Tandai semua notifikasi milik user sebagai sudah dibaca.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bacaSemua()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            Notifikasi::where('id_user', $user->id)
                ->belumDibaca()
                ->update(['dibaca_at' => now()]);

            return $this->sendResponse([], 'Semua notifikasi ditandai sudah dibaca.');
        } catch (Exception $e) {
            Log::error('Gagal menandai semua notifikasi: '.$e->getMessage());

            return $this->sendError('Gagal menandai semua notifikasi.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a success response.
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
     * Send an error response.
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
