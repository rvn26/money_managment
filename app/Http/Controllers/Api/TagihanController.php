<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\KategoriTagihan;
use App\Models\Pengeluaran;
use App\Models\Tagihan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TagihanController extends Controller
{
    /**
     * Get all tagihan for the authenticated user (with pagination).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);
            $tagihan = Tagihan::with('kategori_tagihan')
                ->where('id_user', $user->id)
                ->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data tagihan berhasil diambil.',
                'data' => $tagihan->items(),
                'pagination' => [
                    'current_page' => $tagihan->currentPage(),
                    'last_page' => $tagihan->lastPage(),
                    'per_page' => $tagihan->perPage(),
                    'total' => $tagihan->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengambil data tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single tagihan by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tagihan = Tagihan::with('kategori_tagihan')
                ->where('id_user', $user->id)
                ->find($id);

            if (! $tagihan) {
                return $this->sendError('Tagihan tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($tagihan, 'Detail tagihan berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengambil detail tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new tagihan.
     * Requires id_kategori — user must create kategori tagihan first.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategori_tagihans,id',
            'nama' => 'required|min:3',
            'nominal' => 'required|numeric|min:1',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:belum_dibayar,lunas,terlambat',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'pengulangan' => 'required|in:sekali_bayar,bulanan,tahunan',
            'catatan' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Verify kategori tagihan belongs to the authenticated user
            $kategori = KategoriTagihan::where('id', $request->id_kategori)
                ->where('id_user', $user->id)
                ->first();

            if (! $kategori) {
                return $this->sendError(
                    'Kategori tidak ditemukan.',
                    ['id_kategori' => ['Kategori tagihan tidak ditemukan. Silakan buat kategori tagihan terlebih dahulu.']],
                    422
                );
            }

            $tagihan = new Tagihan;
            $tagihan->id_user = $user->id;
            $tagihan->kategori = $request->id_kategori;
            $tagihan->nama = $request->nama;
            $tagihan->nominal = $request->nominal;
            $tagihan->jatuh_tempo = $request->jatuh_tempo;

            // Auto-set status to 'terlambat' if jatuh_tempo has passed and status is 'belum_dibayar'
            if (Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast() && $request->status == 'belum_dibayar') {
                $tagihan->status = 'terlambat';
            } else {
                $tagihan->status = $request->status;
            }

            $tagihan->metode_pembayaran = $request->metode_pembayaran;
            $tagihan->pengulangan = $request->pengulangan;
            $tagihan->catatan = $request->catatan;
            $tagihan->save();

            // Auto-create pengeluaran if status is 'lunas'
            if ($request->status == 'lunas') {
                $kategoriTagihan = Kategori::firstOrCreate(
                    ['nama' => 'Tagihan', 'id_user' => $user->id],
                    ['deskripsi' => 'Pengeluaran dari tagihan']
                );

                $pengeluaran = new Pengeluaran;
                $pengeluaran->id_user = $user->id;
                $pengeluaran->id_kategori = $kategoriTagihan->id;
                $pengeluaran->total = $request->nominal;
                $pengeluaran->tanggal_pengeluaran = Carbon::now(timezone: 'Asia/Jakarta')->format('Y-m-d');
                $pengeluaran->description = $request->catatan;
                $pengeluaran->tujuan = $request->nama;
                $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
                $pengeluaran->status = 'paid';
                $pengeluaran->save();
            }

            return $this->sendResponse($tagihan->load('kategori_tagihan'), 'Tagihan berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan tagihan: '.$e->getMessage());

            return $this->sendError('Gagal menyimpan tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing tagihan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategori_tagihans,id',
            'nama' => 'required|min:3',
            'nominal' => 'required|numeric|min:1',
            'jatuh_tempo' => 'required|date',
            'status' => 'required|in:belum_dibayar,lunas,terlambat',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'pengulangan' => 'required|in:sekali_bayar,bulanan,tahunan',
            'catatan' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tagihan = Tagihan::where('id_user', $user->id)->find($id);

            if (! $tagihan) {
                return $this->sendError('Tagihan tidak ditemukan.', [], 404);
            }

            // Verify kategori tagihan belongs to the authenticated user
            $kategori = KategoriTagihan::where('id', $request->id_kategori)
                ->where('id_user', $user->id)
                ->first();

            if (! $kategori) {
                return $this->sendError(
                    'Kategori tidak ditemukan.',
                    ['id_kategori' => ['Kategori tagihan tidak ditemukan. Silakan buat kategori tagihan terlebih dahulu.']],
                    422
                );
            }

            $oldStatus = $tagihan->getOriginal('status');

            $tagihan->kategori = $request->id_kategori;
            $tagihan->nama = $request->nama;
            $tagihan->nominal = $request->nominal;
            $tagihan->jatuh_tempo = $request->jatuh_tempo;

            // Auto-set status to 'terlambat' if jatuh_tempo has passed and status is 'belum_dibayar'
            if (Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast() && $request->status == 'belum_dibayar') {
                $tagihan->status = 'terlambat';
            } else {
                $tagihan->status = $request->status;
            }

            $tagihan->metode_pembayaran = $request->metode_pembayaran;
            $tagihan->pengulangan = $request->pengulangan;
            $tagihan->catatan = $request->catatan;
            $tagihan->save();

            // Auto-create pengeluaran if status changed to 'lunas'
            if ($request->status == 'lunas' && $oldStatus !== 'lunas') {
                $kategoriTagihan = Kategori::firstOrCreate(
                    ['nama' => 'Tagihan', 'id_user' => $user->id],
                    ['deskripsi' => 'Pengeluaran dari tagihan']
                );

                $pengeluaran = new Pengeluaran;
                $pengeluaran->id_user = $user->id;
                $pengeluaran->id_kategori = $kategoriTagihan->id;
                $pengeluaran->total = $request->nominal;
                $pengeluaran->tanggal_pengeluaran = Carbon::now(timezone: 'Asia/Jakarta')->format('Y-m-d');
                $pengeluaran->description = $request->catatan;
                $pengeluaran->tujuan = $request->nama;
                $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
                $pengeluaran->status = 'paid';
                $pengeluaran->save();
            }

            return $this->sendResponse($tagihan->load('kategori_tagihan'), 'Tagihan berhasil diupdate.');
        } catch (Exception $e) {
            Log::error('Gagal update tagihan: '.$e->getMessage());

            return $this->sendError('Gagal mengupdate tagihan.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a tagihan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tagihan = Tagihan::where('id_user', $user->id)->find($id);

            if (! $tagihan) {
                return $this->sendError('Tagihan tidak ditemukan.', [], 404);
            }

            $tagihan->delete();

            return $this->sendResponse([], 'Tagihan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus tagihan: '.$e->getMessage());

            return $this->sendError('Gagal menghapus tagihan.', ['error' => $e->getMessage()], 500);
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
