<?php

namespace App\Livewire\Component;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class KategoriCart extends Component
{
    public $categoryChartData = [];
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
        $queryPengeluaran = Pengeluaran::where('pengeluarans.id_user', Auth::user()->id);
        switch ($this->filter) {
            case 'hari_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', Carbon::today());

                break;
            case '7_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));

                break;
            case '30_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));

                break;
            case 'bulan_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());

                break;
            case 'tahun_ini':
                $queryPengeluaran->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                break;
            default:
                # code...
                break;
        }
        $this->generateCategoryChartData( clone $queryPengeluaran);
    }
    protected function generateCategoryChartData($queryPengeluaran)
    {
        // Ambil pengeluaran per kategori
        $dataKategori = $queryPengeluaran
            ->join('kategoris', 'pengeluarans.id_kategori', '=', 'kategoris.id') // Sesuaikan nama tabel & kolom
            ->selectRaw('kategoris.nama as nama_kategori, SUM(pengeluarans.total) as total')
            ->groupBy('kategoris.nama')
            ->get();

        // Pisahkan menjadi Labels dan Series untuk ApexCharts
        $labels = $dataKategori->pluck('nama_kategori')->toArray();
        $series = $dataKategori->pluck('total')->map(fn($value) => (float) $value)->toArray();

        $this->categoryChartData = [
            'labels' => $labels,
            'series' => $series,
        ];

        // Dispatch event sesuai dengan nama listener di JavaScript Anda
        $this->dispatch('update-category-chart', data: $this->categoryChartData);
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
        $this->categoryChartData = [
           'labels' => [],
            'series' => [],
        ];

        $this->filterData();
    }
    public function render()
    {
        return view('livewire.component.kategori-cart');
    }
}
