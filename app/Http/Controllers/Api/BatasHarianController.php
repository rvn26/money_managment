<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BatasHarian;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class BatasHarianController extends Controller
{
    /**
     * Get the current batas harian for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $batasHarian = BatasHarian::where('id_user', $user->id)->first();

            $totalTerpakai = Pengeluaran::where('id_user', $user->id)
                ->whereDate('tanggal_pengeluaran', Carbon::today())
                ->sum('total');

            $batas = $batasHarian ? $batasHarian->batas : 0;
            $persentase = $batas > 0 ? min(($totalTerpakai / $batas) * 100, 100) : 0;
            $sisa = max($batas - $totalTerpakai, 0);

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Data batas harian berhasil diambil.',
                'data' => [
                    'id' => $batasHarian?->id,
                    'batas' => $batas,
                    'terpakai' => $totalTerpakai,
                    'sisa' => $sisa,
                    'persentase' => round($persentase, 1),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil batas harian: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal mengambil data batas harian.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Set or update batas harian for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batas' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statuscode' => 422,
                'msg' => 'Validation Error.',
                'data' => $validator->errors(),
            ], 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $batasHarian = BatasHarian::updateOrCreate(
                ['id_user' => $user->id],
                ['batas' => $request->batas]
            );

            $totalTerpakai = Pengeluaran::where('id_user', $user->id)
                ->whereDate('tanggal_pengeluaran', Carbon::today())
                ->sum('total');

            $persentase = $batasHarian->batas > 0
                ? min(($totalTerpakai / $batasHarian->batas) * 100, 100)
                : 0;

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Batas harian berhasil diset.',
                'data' => [
                    'id' => $batasHarian->id,
                    'batas' => $batasHarian->batas,
                    'terpakai' => $totalTerpakai,
                    'sisa' => max($batasHarian->batas - $totalTerpakai, 0),
                    'persentase' => round($persentase, 1),
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal set batas harian: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal menyimpan batas harian.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Delete batas harian for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $batasHarian = BatasHarian::where('id_user', $user->id)->first();

            if (! $batasHarian) {
                return response()->json([
                    'statuscode' => 404,
                    'msg' => 'Batas harian tidak ditemukan.',
                    'data' => [],
                ], 404);
            }

            $batasHarian->delete();

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Batas harian berhasil dihapus.',
                'data' => [],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal hapus batas harian: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal menghapus batas harian.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }
}
