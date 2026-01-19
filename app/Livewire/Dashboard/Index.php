<?php

namespace App\Livewire\Dashboard;

use App\Models\batas_harian;
use App\Models\pemasukan;
use App\Models\pengeluaran;
use App\Models\tagihan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $totalSaldo = 0;
    public $totalPemasukan = 0;
    public $totalPengeluaran = 0;
    public $totalTagihan = 0;
    public $selisih = 0;
    public $batasHarian, $totalTerpakai, $persentase;
    public $filter = 'bulan_ini';

    public function tampilsetbatas()
    {
        // dd('hallo');
        $this->dispatch('setbatas');
    }
    public function TambahPemasukan()
    {
        $this->dispatch('tambahpemasukan');
    }
    public function TambahPengeluaran()
    {
        $this->dispatch('tampilTambah');
    }
    public function TambahTagihan()
    {
        // dd("hello");
        $this->dispatch('tambahTagihan');
    }
    public function filterData()
    {
        $queryPengeluaran = pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukan = pemasukan::where('id_user', Auth::user()->id);
        $queryPengeluaranlama = pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukanlama = pemasukan::where('id_user', Auth::user()->id);
        switch ($this->filter) {
            case 'hari_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfDay());
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->startOfDay());
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->startOfDay());
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->startOfDay());
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

        $this->totalPengeluaran = $queryPengeluaran->sum('total');
        $this->totalPemasukan = $queryPemasukan->sum('total');
        $totalPengeluaranlama = $queryPengeluaranlama->sum('total');
        $totalPemasukanlama = $queryPemasukanlama->sum('total');
        $saldoLama = $totalPemasukanlama -  $totalPengeluaranlama;
        // dd($saldoLama);
        $this->selisih = $this->totalSaldo - $saldoLama;
    }

    public function getTotalSaldo()
    {
        $Pengeluaran = pengeluaran::where('id_user', Auth::user()->id)->sum('total');
        $Pemasukan = pemasukan::where('id_user', Auth::user()->id)->sum('total');
        $this->totalTagihan = tagihan::where('id_user', Auth::user()->id)->sum('nominal');
        $this->totalSaldo = $Pemasukan - $Pengeluaran;
    }

    public function updated($property)
    {
        if (in_array($property, ['filter'])) {
            $this->filterData();
        }
    }

    public function mount()
    {
        $this->getTotalSaldo();
        $this->filterData();
        $this->totalTerpakai = pengeluaran::where('id_user', Auth::user()->id)
            ->whereDate('tanggal_pengeluaran', now())
            ->sum('total');
        $this->batasHarian = batas_harian::where('id_user', Auth::user()->id)->first();
        $batas = $this->batasHarian ? $this->batasHarian->batas : 0;
        $this->persentase = $batas > 0 ? min(($this->totalTerpakai / $batas) * 100, 100) : 0;
    }
    public function render()
    {
        $this->filterData();

        // $tujuhHariLalu = now()->subDays(7);

        // // 7hari
        // $pemasukanLama = pemasukan::where('id_user', Auth::user()->id)
        //     ->where('created_at', '<', $tujuhHariLalu)
        //     ->sum('total');

        // $pengeluaranLama = pengeluaran::where('id_user', Auth::user()->id)
        //     ->where('created_at', '<', $tujuhHariLalu)
        //     ->sum('total');

        // $saldoLama = $pemasukanLama - $pengeluaranLama;
        // $this->selisih = $this->totalSaldo - $saldoLama;

        return view(
            'livewire.dashboard.index'
        );
    }
}
