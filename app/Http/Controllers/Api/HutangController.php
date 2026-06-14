<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\HasPeriodeFilter;
use App\Http\Controllers\Controller;
use App\Models\Hutang;
use App\Models\Pertemanan;
use App\Models\User;
use App\Services\FcmService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class HutangController extends Controller
{
    use HasPeriodeFilter;

    /**
     * Get all hutang for the authenticated user (with pagination and filter).
     *
     * Query params:
     * - periode: 'semua' | 'bulan_ini' | 'minggu_ini' | 'custom' (default: 'bulan_ini')
     * - bulan_custom: Format Y-m (contoh: 2024-06) - untuk periode 'custom'
     * - limit: Jumlah data per halaman (default: 10, max: 100)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);

            $query = Hutang::with(['user', 'teman'])
                ->where('id_user', $user->id);

            // Apply periode filter (default: bulan_ini)
            $this->applyPeriodeFilter($query, 'tanggal_pinjaman', $request);

            $hutang = $query->latest('tanggal_pinjaman')->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data hutang berhasil diambil.',
                'data' => $hutang->items(),
                'pagination' => [
                    'current_page' => $hutang->currentPage(),
                    'last_page' => $hutang->lastPage(),
                    'per_page' => $hutang->perPage(),
                    'total' => $hutang->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil hutang: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal mengambil data hutang.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single hutang by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $hutang = Hutang::with(['user', 'teman'])
                ->where('id_user', $user->id)
                ->find($id);

            if (! $hutang) {
                return $this->sendError('Hutang tidak ditemukan.', [], 404);
            }

            return $this->sendResponse($hutang, 'Detail hutang berhasil diambil.');
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail hutang: '.$e->getMessage());

            return $this->sendError('Gagal mengambil detail hutang.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new hutang.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_teman' => 'nullable|exists:users,id',
            'nama' => 'nullable|max:255|required_without:id_teman',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_pinjaman' => 'required|date',
            'metode_pembayaran' => ['required', Rule::in(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])],
            'catatan' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Jika id_teman dipilih, pastikan benar-benar teman aktif (status accepted).
            if ($request->filled('id_teman')) {
                $isTeman = Pertemanan::query()
                    ->where('status', 'accepted')
                    ->where(function ($q) use ($user, $request) {
                        $q->where(function ($qq) use ($user, $request) {
                            $qq->where('id_user', $user->id)
                                ->where('id_teman', $request->id_teman);
                        })->orWhere(function ($qq) use ($user, $request) {
                            $qq->where('id_user', $request->id_teman)
                                ->where('id_teman', $user->id);
                        });
                    })
                    ->exists();

                if (! $isTeman) {
                    return $this->sendError(
                        'Pengguna tersebut bukan teman kamu.',
                        ['id_teman' => ['Pengguna tersebut bukan teman kamu.']],
                        422
                    );
                }
            }

            $hutang = new Hutang;
            $hutang->id_user = $user->id;
            $hutang->id_teman = $request->id_teman;

            if ($request->id_teman != null) {
                $teman = User::find($request->id_teman);
                $hutang->nama = $teman->name;
                // dd($hutang->nama);
            } else {
                $hutang->nama = $request->nama ?: null;
            }

            $hutang->jumlah = $request->jumlah;
            $hutang->tanggal_pinjaman = $request->tanggal_pinjaman;
            $hutang->metode_pembayaran = $request->metode_pembayaran;
            $hutang->status = 'belum_lunas';
            $hutang->catatan = $request->catatan;
            $hutang->save();

            // Kirim notifikasi FCM ke teman jika hutang terkait teman
            if ($hutang->id_teman) {
                $temanUser = User::find($hutang->id_teman);
                if ($temanUser) {
                    $jumlahFormatted = number_format($hutang->jumlah, 0, ',', '.');
                    app(FcmService::class)->sendToUser(
                        $temanUser,
                        'Hutang Baru',
                        "{$user->name} mencatat hutang kamu sebesar Rp{$jumlahFormatted}.",
                        'hutang',
                        ['hutang_id' => (string) $hutang->id, 'aksi' => 'hutang_baru']
                    );
                }
            }

            return $this->sendResponse($hutang->load(['user', 'teman']), 'Hutang berhasil ditambahkan.', 201);
        } catch (Exception $e) {
            Log::error('Gagal simpan hutang: '.$e->getMessage());

            return $this->sendError('Gagal menyimpan hutang.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing hutang.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_pinjaman' => 'required|date',
            'metode_pembayaran' => ['required', Rule::in(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])],
            'status' => ['required', Rule::in(['belum_lunas', 'lunas', 'terlambat'])],
            'catatan' => 'nullable|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $hutang = Hutang::where('id_user', $user->id)->find($id);

            if (! $hutang) {
                return $this->sendError('Hutang tidak ditemukan.', [], 404);
            }

            $hutang->jumlah = $request->jumlah;
            $hutang->tanggal_pinjaman = $request->tanggal_pinjaman;
            $hutang->metode_pembayaran = $request->metode_pembayaran;
            $hutang->status = $request->status;
            $hutang->catatan = $request->catatan;
            $hutang->save();

            // Kirim notifikasi FCM ke teman jika hutang terkait teman
            if ($hutang->id_teman) {
                $temanUser = User::find($hutang->id_teman);
                if ($temanUser) {
                    $statusLabel = match ($hutang->status) {
                        'lunas' => 'lunas',
                        'terlambat' => 'terlambat',
                        default => 'belum lunas',
                    };
                    $jumlahFormatted = number_format($hutang->jumlah, 0, ',', '.');
                    app(FcmService::class)->sendToUser(
                        $temanUser,
                        'Update Hutang',
                        "{$user->name} mengubah status hutang Rp{$jumlahFormatted} menjadi {$statusLabel}.",
                        'hutang',
                        ['hutang_id' => (string) $hutang->id, 'aksi' => 'hutang_update']
                    );
                }
            }

            return $this->sendResponse($hutang->load(['user', 'teman']), 'Hutang berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Gagal update hutang: '.$e->getMessage());

            return $this->sendError('Gagal mengupdate hutang.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a hutang.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $hutang = Hutang::where('id_user', $user->id)->find($id);

            if (! $hutang) {
                return $this->sendError('Hutang tidak ditemukan.', [], 404);
            }

            $hutang->delete();

            return $this->sendResponse([], 'Hutang berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal hapus hutang: '.$e->getMessage());

            return $this->sendError('Gagal menghapus hutang.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get "Hutang Saya" — daftar hutang dimana user saat ini yang berhutang ke teman.
     * Endpoint tambahan khusus untuk melihat hutang dari sisi yang berhutang.
     *
     * Query params:
     * - periode: 'semua' | 'bulan_ini' | 'minggu_ini' | 'custom' (default: 'bulan_ini')
     * - bulan_custom: Format Y-m (contoh: 2024-06) - untuk periode 'custom'
     * - limit: Jumlah data per halaman (default: 10, max: 100)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hutangSaya(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $limit = min((int) $request->query('limit', 10), 100);

            // Hutang saya = hutang dimana teman lain yang mencatat, dan id_teman = user saat ini
            $query = Hutang::with(['user', 'teman'])
                ->where('id_teman', $user->id);

            // Apply periode filter (default: bulan_ini)
            $this->applyPeriodeFilter($query, 'tanggal_pinjaman', $request);

            $hutang = $query->latest('tanggal_pinjaman')->paginate($limit);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data hutang saya berhasil diambil.',
                'data' => $hutang->items(),
                'pagination' => [
                    'current_page' => $hutang->currentPage(),
                    'last_page' => $hutang->lastPage(),
                    'per_page' => $hutang->perPage(),
                    'total' => $hutang->total(),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil hutang saya: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal mengambil data hutang saya.',
                'error' => $e->getMessage(),
            ], 500);
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
