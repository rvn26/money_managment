<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BatasHarian;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Tagihan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    /**
     * Get dashboard summary data for the authenticated user.
     *
     * Query params:
     * - filter: hari_ini | 7_hari | 30_hari | bulan_ini (default) | tahun_ini
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $filter = $request->query('filter', 'bulan_ini');

            // Total saldo (all time)
            $totalPemasukanAllTime = Pemasukan::where('id_user', $user->id)->sum('total');
            $totalPengeluaranAllTime = Pengeluaran::where('id_user', $user->id)->sum('total');
            $totalSaldo = $totalPemasukanAllTime - $totalPengeluaranAllTime;

            // Filtered totals
            $queryPengeluaran = Pengeluaran::where('id_user', $user->id);
            $queryPemasukan = Pemasukan::where('id_user', $user->id);
            $queryPemasukanLalu = Pemasukan::where('id_user', $user->id);
            $queryPengeluaranLalu = Pengeluaran::where('id_user', $user->id);

            switch ($filter) {
                case 'hari_ini':
                    $queryPengeluaran->whereDate('tanggal_pengeluaran', Carbon::today());
                    $queryPemasukan->whereDate('tanggal', Carbon::today());
                    $queryPemasukanLalu->whereDate('tanggal', Carbon::yesterday());
                    $queryPengeluaranLalu->whereDate('tanggal_pengeluaran', Carbon::yesterday());
                    break;
                case '7_hari':
                    $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));
                    $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(7));
                    $queryPemasukanLalu->whereBetween('tanggal', [Carbon::now()->subDays(14), Carbon::now()->subDays(8)]);
                    $queryPengeluaranLalu->whereBetween('tanggal_pengeluaran', [Carbon::now()->subDays(14), Carbon::now()->subDays(8)]);
                    break;
                case '30_hari':
                    $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));
                    $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(30));
                    $queryPemasukanLalu->whereBetween('tanggal', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)]);
                    $queryPengeluaranLalu->whereBetween('tanggal_pengeluaran', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)]);
                    break;
                case 'bulan_ini':
                    $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());
                    $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->startOfMonth());
                    $queryPemasukanLalu->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                        ->whereYear('tanggal', Carbon::now()->subMonth()->year);
                    $queryPengeluaranLalu->whereMonth('tanggal_pengeluaran', Carbon::now()->subMonth()->month)
                        ->whereYear('tanggal_pengeluaran', Carbon::now()->subMonth()->year);
                    break;
                case 'tahun_ini':
                    $queryPengeluaran->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                    $queryPemasukan->where('tanggal', '>=', Carbon::now()->startOfYear());
                    $queryPemasukanLalu->whereYear('tanggal', Carbon::now()->subYear()->year);
                    $queryPengeluaranLalu->whereYear('tanggal_pengeluaran', Carbon::now()->subYear()->year);
                    break;
            }

            $totalPemasukan = $queryPemasukan->sum('total');
            $totalPengeluaran = $queryPengeluaran->sum('total');
            $totalPemasukanLalu = $queryPemasukanLalu->sum('total');
            $totalPengeluaranLalu = $queryPengeluaranLalu->sum('total');

            // Persentase perubahan
            $persentasePemasukan = $totalPemasukanLalu > 0
                ? (($totalPemasukan - $totalPemasukanLalu) / $totalPemasukanLalu) * 100
                : ($totalPemasukan > 0 ? 100 : 0);

            $persentasePengeluaran = $totalPengeluaranLalu > 0
                ? (($totalPengeluaran - $totalPengeluaranLalu) / $totalPengeluaranLalu) * 100
                : ($totalPengeluaran > 0 ? 100 : 0);

            // Tagihan
            $totalTagihan = Tagihan::where('id_user', $user->id)->sum('nominal');
            $tagihanBelumBayar = Tagihan::where('id_user', $user->id)
                ->where('status', 'belum_dibayar')
                ->count();

            // Batas harian
            $batasHarian = BatasHarian::where('id_user', $user->id)->first();
            $totalTerpakai = Pengeluaran::where('id_user', $user->id)
                ->whereDate('tanggal_pengeluaran', Carbon::today())
                ->sum('total');
            $batas = $batasHarian ? $batasHarian->batas : 0;
            $persentaseBatasHarian = $batas > 0 ? min(($totalTerpakai / $batas) * 100, 100) : 0;

            // Transaksi terbaru (5 terakhir)
            $transaksiTerbaru = Pengeluaran::with('kategori')
                ->where('id_user', $user->id)
                ->orderBy('tanggal_pengeluaran', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Tagihan mendatang (belum dibayar, urut jatuh tempo)
            $tagihanMendatang = Tagihan::with('kategori_tagihan')
                ->where('id_user', $user->id)
                ->where('status', 'belum_dibayar')
                ->orderBy('jatuh_tempo', 'asc')
                ->limit(5)
                ->get();

            // Chart: Balance (pemasukan vs pengeluaran per hari)
            $chartQueryPemasukan = Pemasukan::where('id_user', $user->id);
            $chartQueryPengeluaran = Pengeluaran::where('id_user', $user->id);

            switch ($filter) {
                case 'hari_ini':
                    $chartQueryPemasukan->whereDate('tanggal', Carbon::today());
                    $chartQueryPengeluaran->whereDate('tanggal_pengeluaran', Carbon::today());
                    break;
                case '7_hari':
                    $chartQueryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(7));
                    $chartQueryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));
                    break;
                case '30_hari':
                    $chartQueryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(30));
                    $chartQueryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));
                    break;
                case 'bulan_ini':
                    $chartQueryPemasukan->whereDate('tanggal', '>=', Carbon::now()->startOfMonth());
                    $chartQueryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());
                    break;
                case 'tahun_ini':
                    $chartQueryPemasukan->where('tanggal', '>=', Carbon::now()->startOfYear());
                    $chartQueryPengeluaran->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                    break;
            }

            $dataPemasukan = $chartQueryPemasukan
                ->selectRaw('DATE(tanggal) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $dataPengeluaran = $chartQueryPengeluaran
                ->selectRaw('DATE(tanggal_pengeluaran) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $allDates = $dataPemasukan->pluck('date')
                ->merge($dataPengeluaran->pluck('date'))
                ->unique()
                ->sort()
                ->values();

            $balanceChart = [
                'categories' => $allDates->toArray(),
                'pemasukan' => $allDates->map(fn ($date) => (float) ($dataPemasukan->where('date', $date)->first()->total ?? 0))->toArray(),
                'pengeluaran' => $allDates->map(fn ($date) => (float) ($dataPengeluaran->where('date', $date)->first()->total ?? 0))->toArray(),
            ];

            // Chart: Kategori pengeluaran (pie/donut)
            $chartKategoriQuery = Pengeluaran::where('pengeluarans.id_user', $user->id);

            switch ($filter) {
                case 'hari_ini':
                    $chartKategoriQuery->whereDate('tanggal_pengeluaran', Carbon::today());
                    break;
                case '7_hari':
                    $chartKategoriQuery->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));
                    break;
                case '30_hari':
                    $chartKategoriQuery->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));
                    break;
                case 'bulan_ini':
                    $chartKategoriQuery->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());
                    break;
                case 'tahun_ini':
                    $chartKategoriQuery->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                    break;
            }

            $dataKategori = $chartKategoriQuery
                ->join('kategoris', 'pengeluarans.id_kategori', '=', 'kategoris.id')
                ->selectRaw('kategoris.nama as nama_kategori, kategoris.emoji, kategoris.warna, SUM(pengeluarans.total) as total')
                ->groupBy('kategoris.nama', 'kategoris.emoji', 'kategoris.warna')
                ->get();

            $kategoriChart = [
                'labels' => $dataKategori->pluck('nama_kategori')->toArray(),
                'series' => $dataKategori->pluck('total')->map(fn ($value) => (float) $value)->toArray(),
                'emojis' => $dataKategori->pluck('emoji')->toArray(),
                'colors' => $dataKategori->pluck('warna')->toArray(),
            ];

            return response()->json([
                'statuscode' => 200,
                'msg' => 'Dashboard data berhasil diambil.',
                'data' => [
                    'filter' => $filter,
                    'total_saldo' => $totalSaldo,
                    'total_pemasukan' => $totalPemasukan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'total_tagihan' => $totalTagihan,
                    'persentase_pemasukan' => round($persentasePemasukan, 1),
                    'persentase_pengeluaran' => round($persentasePengeluaran, 1),
                    'tagihan_belum_bayar' => $tagihanBelumBayar,
                    'batas_harian' => [
                        'batas' => $batas,
                        'terpakai' => $totalTerpakai,
                        'persentase' => round($persentaseBatasHarian, 1),
                    ],
                    'transaksi_terbaru' => $transaksiTerbaru,
                    'tagihan_mendatang' => $tagihanMendatang,
                    'charts' => [
                        'balance' => $balanceChart,
                        'kategori' => $kategoriChart,
                    ],
                ],
            ], 200);
        } catch (Exception $e) {
            Log::error('Gagal mengambil data dashboard: '.$e->getMessage());

            return response()->json([
                'statuscode' => 500,
                'msg' => 'Gagal mengambil data dashboard.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }
}
