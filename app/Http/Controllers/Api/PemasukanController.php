<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PemasukanController extends Controller
{
    /**
     * Get all pemasukan for the authenticated user (with pagination).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);
            $pemasukan = Pemasukan::where('id_user', $user->id)->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg'        => 'Data pemasukan berhasil diambil.',
                'data'       => $pemasukan->items(),
                'pagination' => [
                    'current_page' => $pemasukan->currentPage(),
                    'last_page'    => $pemasukan->lastPage(),
                    'per_page'     => $pemasukan->perPage(),
                    'total'        => $pemasukan->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil pemasukan: ' . $e->getMessage());
            return $this->sendError('Gagal mengambil data pemasukan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single pemasukan by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pemasukan = Pemasukan::where('id_user', $user->id)->find($id);

            if (!$pemasukan) {
                return $this->sendError('Pemasukan tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($pemasukan, 'Detail pemasukan berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail pemasukan: ' . $e->getMessage());
            return $this->sendError('Gagal mengambil detail pemasukan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new pemasukan.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'           => 'required|date|before_or_equal:today',
            'jenis'             => 'required|in:gaji,bonus,penjualan,investasi,lain-lain',
            'total'             => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'            => 'required|in:pending,lunas',
            'deskripsi'         => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $pemasukan = new Pemasukan;
            $pemasukan->id_user = $user->id;
            $pemasukan->tanggal = $request->tanggal;
            $pemasukan->jenis = $request->jenis;
            $pemasukan->total = $request->total;
            $pemasukan->metode_pembayaran = $request->metode_pembayaran;
            $pemasukan->status = $request->status;
            $pemasukan->deskripsi = $request->deskripsi;
            $pemasukan->save();

            return $this->sendResponse($pemasukan, 'Pemasukan berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan pemasukan: ' . $e->getMessage());
            return $this->sendError('Gagal menyimpan pemasukan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing pemasukan.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'           => 'required|date|before_or_equal:today',
            'jenis'             => 'required|in:gaji,bonus,penjualan,investasi,lain-lain',
            'total'             => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'            => 'required|in:pending,lunas',
            'deskripsi'         => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pemasukan = Pemasukan::where('id_user', $user->id)->find($id);

            if (!$pemasukan) {
                return $this->sendError('Pemasukan tidak ditemukan.', [], 404);
            }

            $pemasukan->tanggal = $request->tanggal;
            $pemasukan->jenis = $request->jenis;
            $pemasukan->total = $request->total;
            $pemasukan->metode_pembayaran = $request->metode_pembayaran;
            $pemasukan->status = $request->status;
            $pemasukan->deskripsi = $request->deskripsi;
            $pemasukan->save();

            return $this->sendResponse($pemasukan, 'Pemasukan berhasil diupdate.');
        } catch (Exception $e) {
            Log::error('Gagal update pemasukan: ' . $e->getMessage());
            return $this->sendError('Gagal mengupdate pemasukan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a pemasukan.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pemasukan = Pemasukan::where('id_user', $user->id)->find($id);

            if (!$pemasukan) {
                return $this->sendError('Pemasukan tidak ditemukan.', [], 404);
            }

            $pemasukan->delete();

            return $this->sendResponse([], 'Pemasukan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus pemasukan: ' . $e->getMessage());
            return $this->sendError('Gagal menghapus pemasukan.', ['error' => $e->getMessage()], 500);
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
