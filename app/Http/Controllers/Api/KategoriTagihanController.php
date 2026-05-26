<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriTagihan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class KategoriTagihanController extends Controller
{
    /**
     * Get all kategori tagihan for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);
            $kategoriTagihan = KategoriTagihan::where('id_user', $user->id)->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data kategori tagihan berhasil diambil.',
                'data' => $kategoriTagihan->items(),
                'pagination' => [
                    'current_page' => $kategoriTagihan->currentPage(),
                    'last_page' => $kategoriTagihan->lastPage(),
                    'per_page' => $kategoriTagihan->perPage(),
                    'total' => $kategoriTagihan->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil kategori tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengambil data kategori tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single kategori tagihan by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kategoriTagihan = KategoriTagihan::where('id_user', $user->id)->find($id);

            if (! $kategoriTagihan) {
                return $this->sendError('Kategori tagihan tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($kategoriTagihan, 'Detail kategori tagihan berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail kategori tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengambil detail kategori tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new kategori tagihan.
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

            $kategoriTagihan = new KategoriTagihan;
            $kategoriTagihan->id_user = $user->id;
            $kategoriTagihan->nama = $request->nama;
            $kategoriTagihan->emoji = $request->emoji;
            $kategoriTagihan->warna = $request->warna;
            $kategoriTagihan->deskripsi = $request->deskripsi;
            $kategoriTagihan->save();

            return $this->sendResponse($kategoriTagihan, 'Kategori tagihan berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan kategori tagihan: '.$e->getMessage());

            return $this->sendError('Gagal menyimpan kategori tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing kategori tagihan.
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
            $kategoriTagihan = KategoriTagihan::where('id_user', $user->id)->find($id);

            if (! $kategoriTagihan) {
                return $this->sendError('Kategori tagihan tidak ditemukan.', [], 404);
            }

            $kategoriTagihan->nama = $request->nama;
            $kategoriTagihan->emoji = $request->emoji;
            $kategoriTagihan->warna = $request->warna;
            $kategoriTagihan->deskripsi = $request->deskripsi;
            $kategoriTagihan->save();

            return $this->sendResponse($kategoriTagihan, 'Kategori tagihan berhasil diupdate.');
        } catch (Exception $e) {
            Log::error('Gagal update kategori tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengupdate kategori tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a kategori tagihan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kategoriTagihan = KategoriTagihan::where('id_user', $user->id)->find($id);

            if (! $kategoriTagihan) {
                return $this->sendError('Kategori tagihan tidak ditemukan.', [], 404);
            }

            $kategoriTagihan->delete();

            return $this->sendResponse([], 'Kategori tagihan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus kategori tagihan: '.$e->getMessage());

            return $this->sendError('Gagal menghapus kategori tagihan.', ['error' => $e->getMessage()], 500);
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
