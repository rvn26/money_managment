<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class KategoriPengeluaranController extends Controller
{
    /**
     * Get all kategori pengeluaran for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);
            $kategori = Kategori::where('id_user', $user->id)->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data kategori pengeluaran berhasil diambil.',
                'data' => $kategori->items(),
                'pagination' => [
                    'current_page' => $kategori->currentPage(),
                    'last_page' => $kategori->lastPage(),
                    'per_page' => $kategori->perPage(),
                    'total' => $kategori->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil kategori pengeluaran: '.$e->getMessage());

            return $this->sendError('Gagal mengambil data kategori pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single kategori pengeluaran by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kategori = Kategori::where('id_user', $user->id)->find($id);

            if (! $kategori) {
                return $this->sendError('Kategori pengeluaran tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($kategori, 'Detail kategori pengeluaran berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail kategori pengeluaran: '.$e->getMessage());

            return $this->sendError('Gagal mengambil detail kategori pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new kategori pengeluaran.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'emoji' => 'nullable|string|max:10',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $kategori = new Kategori;
            $kategori->id_user = $user->id;
            $kategori->nama = $request->nama;
            $kategori->emoji = $request->emoji;
            $kategori->warna = $request->warna;
            $kategori->deskripsi = $request->deskripsi;
            $kategori->save();

            return $this->sendResponse($kategori, 'Kategori pengeluaran berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan kategori pengeluaran: '.$e->getMessage());

            return $this->sendError('Gagal menyimpan kategori pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing kategori pengeluaran.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'emoji' => 'nullable|string|max:10',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kategori = Kategori::where('id_user', $user->id)->find($id);

            if (! $kategori) {
                return $this->sendError('Kategori pengeluaran tidak ditemukan.', [], 404);
            }

            $kategori->nama = $request->nama;
            $kategori->emoji = $request->emoji;
            $kategori->warna = $request->warna;
            $kategori->deskripsi = $request->deskripsi;
            $kategori->save();

            return $this->sendResponse($kategori, 'Kategori pengeluaran berhasil diupdate.');
        } catch (Exception $e) {
            Log::error('Gagal update kategori pengeluaran: '.$e->getMessage());

            return $this->sendError('Gagal mengupdate kategori pengeluaran.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a kategori pengeluaran.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kategori = Kategori::where('id_user', $user->id)->find($id);

            if (! $kategori) {
                return $this->sendError('Kategori pengeluaran tidak ditemukan.', [], 404);
            }

            $kategori->delete();

            return $this->sendResponse([], 'Kategori pengeluaran berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus kategori pengeluaran: '.$e->getMessage());

            return $this->sendError('Gagal menghapus kategori pengeluaran.', ['error' => $e->getMessage()], 500);
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
