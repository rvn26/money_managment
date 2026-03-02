<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pengeluaran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PengeluaranController extends Controller
{
    /**
     * Get all pengeluaran for the authenticated user (with pagination).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);
            $pengeluaran = Pengeluaran::with('kategori')
                ->where('id_user', $user->id)
                ->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg'        => 'Data pengeluaran berhasil diambil.',
                'data'       => $pengeluaran->items(),
                'pagination' => [
                    'current_page' => $pengeluaran->currentPage(),
                    'last_page'    => $pengeluaran->lastPage(),
                    'per_page'     => $pengeluaran->perPage(),
                    'total'        => $pengeluaran->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil pengeluaran: ' . $e->getMessage());
            return $this->sendError('Gagal mengambil data pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single pengeluaran by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pengeluaran = Pengeluaran::with('kategori')
                ->where('id_user', $user->id)
                ->find($id);

            if (!$pengeluaran) {
                return $this->sendError('Pengeluaran tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($pengeluaran, 'Detail pengeluaran berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail pengeluaran: ' . $e->getMessage());
            return $this->sendError('Gagal mengambil detail pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new pengeluaran.
     * Requires id_kategori — user must create kategori pengeluaran first.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Verify kategori belongs to the authenticated user
            $kategori = Kategori::where('id', $request->id_kategori)
                ->where('id_user', $user->id)
                ->first();

            if (!$kategori) {
                return $this->sendError(
                    'Kategori tidak ditemukan.',
                    ['id_kategori' => ['Kategori pengeluaran tidak ditemukan. Silakan buat kategori pengeluaran terlebih dahulu.']],
                    422
                );
            }

            $pengeluaran = new Pengeluaran;
            $pengeluaran->id_user = $user->id;
            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->total = $request->total;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();

            return $this->sendResponse($pengeluaran->load('kategori'), 'Pengeluaran berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());
            return $this->sendError('Gagal menyimpan pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing pengeluaran.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pengeluaran = Pengeluaran::where('id_user', $user->id)->find($id);

            if (!$pengeluaran) {
                return $this->sendError('Pengeluaran tidak ditemukan.', [], 404);
            }

            // Verify kategori belongs to the authenticated user
            $kategori = Kategori::where('id', $request->id_kategori)
                ->where('id_user', $user->id)
                ->first();

            if (!$kategori) {
                return $this->sendError(
                    'Kategori tidak ditemukan.',
                    ['id_kategori' => ['Kategori pengeluaran tidak ditemukan. Silakan buat kategori pengeluaran terlebih dahulu.']],
                    422
                );
            }

            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->total = $request->total;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();

            return $this->sendResponse($pengeluaran->load('kategori'), 'Pengeluaran berhasil diupdate.');
        } catch (Exception $e) {
            Log::error('Gagal update pengeluaran: ' . $e->getMessage());
            return $this->sendError('Gagal mengupdate pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a pengeluaran.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pengeluaran = Pengeluaran::where('id_user', $user->id)->find($id);

            if (!$pengeluaran) {
                return $this->sendError('Pengeluaran tidak ditemukan.', [], 404);
            }

            $pengeluaran->delete();

            return $this->sendResponse([], 'Pengeluaran berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus pengeluaran: ' . $e->getMessage());
            return $this->sendError('Gagal menghapus pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a success response (same format as AuthController).
     */
    protected function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'statuscode' => $code,
            'msg'        => $message,
            'data'       => $result,
        ], $code);
    }

    /**
     * Send an error response (same format as AuthController).
     */
    protected function sendError($error, $errorMessages = [], $code = 401)
    {
        return response()->json([
            'statuscode' => $code,
            'msg'        => $error,
            'data'       => $errorMessages,
        ], $code);
    }
}
