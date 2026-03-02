<?php

namespace App\Livewire\Component;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BalanceCart extends Component
{

    public $chartData = [];
    public $filter;
    protected $listeners = [
        'datafilter' => 'filterhendel'
    ];

    public function filterhendel($data)
    {
        // dd('helom'); 
        $this->filter = $data;
        $this->filterData();
    }
    public function filterData()
    {
        $queryPengeluaran = Pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukan = Pemasukan::where('id_user', Auth::user()->id);
        $queryPengeluaranlama = Pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukanlama = Pemasukan::where('id_user', Auth::user()->id);
        switch ($this->filter) {
            case 'hari_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', Carbon::today());
                $queryPemasukan->whereDate('tanggal', Carbon::today());
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::today());
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::today());
                break;
            case '7_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(7));
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->subDays(7));
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->subDays(7));
                break;
            case '30_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(30));
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->subDays(30));
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->subDays(30));
                break;
            case 'bulan_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->startOfMonth());
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->startOfMonth());
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->startOfMonth());
                break;
            case 'tahun_ini':
                $queryPengeluaran->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                $queryPemasukan->where('tanggal', '>=', Carbon::now()->startOfYear());
                $queryPengeluaranlama->where('tanggal_pengeluaran', '<', Carbon::now()->startOfYear());
                $queryPemasukanlama->where('tanggal', '<', Carbon::now()->startOfYear());
                break;
            default:
                # code...
                break;
        }
        $this->generateChartData(clone $queryPemasukan, clone $queryPengeluaran);
    }
    protected function generateChartData($queryPemasukan, $queryPengeluaran)
    {
        // 1. Ambil total Pemasukan per hari
        $dataPemasukan = $queryPemasukan
            ->selectRaw('DATE(tanggal) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Ambil total Pengeluaran per hari
        $dataPengeluaran = $queryPengeluaran
            ->selectRaw('DATE(tanggal_pengeluaran) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Gabungkan semua tanggal unik dari kedua data untuk sumbu X (kategori)
        $allDates = $dataPemasukan->pluck('date')
            ->merge($dataPengeluaran->pluck('date'))
            ->unique()
            ->sort()
            ->values();

            // jika ada data null set ke 0
        $pemasukanSeries = $allDates->map(function ($date) use ($dataPemasukan) {
            return (float) ($dataPemasukan->where('date', $date)->first()->total ?? 0);
        });

        $pengeluaranSeries = $allDates->map(function ($date) use ($dataPengeluaran) {
            return (float) ($dataPengeluaran->where('date', $date)->first()->total ?? 0);
        });
        // dd($allDates);

        $this->chartData = [
            'categories' => $allDates->toArray(),
            'pemasukan'   => $pemasukanSeries->toArray(),
            'pengeluaran' => $pengeluaranSeries->toArray(),
        ];

        $this->dispatch('updateChart', data: $this->chartData);
    }
    public function updated($property)
    {
        if (in_array($property, ['filter'])) {
            $this->filterData();
        }
    }

    public function mount()
    {
        // $this->setFilter($this->filter);
        $this->chartData = [
            'categories' => [],
            'pemasukan' => [],
            'pengeluaran' => []
        ];

        $this->filterData();
    }

    public function render()
    {
        return view('livewire.component.balance-cart');
    }
}
