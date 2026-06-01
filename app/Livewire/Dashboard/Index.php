<?php

namespace App\Livewire\Dashboard;

use App\Models\BatasHarian;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Tagihan;
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

    public $persentasePemasukan = 0;

    public $persentasePengeluaran = 0;

    public $batasHarian;

    public $totalTerpakai;

    public $persentase;

    public $tagihanBelumBayar;

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

    public function laporan()
    {
        $this->dispatch('bukaLaporan');
    }

    public function filterData()
    {
        $queryPengeluaran = Pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukan = Pemasukan::where('id_user', Auth::user()->id);
        $queryPengeluaranlama = Pengeluaran::where('id_user', Auth::user()->id);
        $queryPemasukanlama = Pemasukan::where('id_user', Auth::user()->id);
        // untuk persentase
        $queryPemasukanLalu = Pemasukan::where('id_user', Auth::user()->id);
        $queryPengeluaranLalu = Pengeluaran::where('id_user', Auth::user()->id);
        switch ($this->filter) {
            case 'hari_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', Carbon::today());
                $queryPemasukan->whereDate('tanggal', Carbon::today());
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::today());
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::today());

                $queryPemasukanLalu->whereDate('tanggal', Carbon::yesterday());
                $queryPengeluaranLalu->whereDate('tanggal_pengeluaran', Carbon::yesterday());
                break;
            case '7_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(7));
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(7));
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->subDays(7));
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->subDays(7));

                $queryPemasukanLalu->whereBetween('tanggal', [
                    Carbon::now()->subDays(14),
                    Carbon::now()->subDays(8),
                ]);
                $queryPengeluaranLalu->whereBetween('tanggal_pengeluaran', [
                    Carbon::now()->subDays(14),
                    Carbon::now()->subDays(8),
                ]);
                break;
            case '30_hari':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->subDays(30));
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->subDays(30));
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->subDays(30));
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->subDays(30));

                $queryPemasukanLalu->whereBetween('tanggal', [
                    Carbon::now()->subDays(60),
                    Carbon::now()->subDays(31),
                ]);
                $queryPengeluaranLalu->whereBetween('tanggal_pengeluaran', [
                    Carbon::now()->subDays(60),
                    Carbon::now()->subDays(31),
                ]);
                break;
            case 'bulan_ini':
                $queryPengeluaran->whereDate('tanggal_pengeluaran', '>=', Carbon::now()->startOfMonth());
                $queryPemasukan->whereDate('tanggal', '>=', Carbon::now()->startOfMonth());
                $queryPengeluaranlama->whereDate('tanggal_pengeluaran', '<', Carbon::now()->startOfMonth());
                $queryPemasukanlama->whereDate('tanggal', '<', Carbon::now()->startOfMonth());

                $queryPemasukanLalu->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                    ->whereYear('tanggal', Carbon::now()->subMonth()->year);
                $queryPengeluaranLalu->whereMonth('tanggal_pengeluaran', Carbon::now()->subMonth()->month)
                    ->whereYear('tanggal_pengeluaran', Carbon::now()->subMonth()->year);
                break;
            case 'tahun_ini':
                $queryPengeluaran->where('tanggal_pengeluaran', '>=', Carbon::now()->startOfYear());
                $queryPemasukan->where('tanggal', '>=', Carbon::now()->startOfYear());
                $queryPengeluaranlama->where('tanggal_pengeluaran', '<', Carbon::now()->startOfYear());
                $queryPemasukanlama->where('tanggal', '<', Carbon::now()->startOfYear());

                $queryPemasukanLalu->whereYear('tanggal', Carbon::now()->subYear()->year);
                $queryPengeluaranLalu->whereYear('tanggal_pengeluaran', Carbon::now()->subYear()->year);
                break;
            default:
                // code...
                break;
        }

        $this->totalPengeluaran = $queryPengeluaran->sum('total');
        $this->totalPemasukan = $queryPemasukan->sum('total');
        $totalPengeluaranlama = $queryPengeluaranlama->sum('total');
        $totalPemasukanlama = $queryPemasukanlama->sum('total');
        $saldoLama = $totalPemasukanlama - $totalPengeluaranlama;
        $totalPemasukanLalu = $queryPemasukanLalu->sum('total');
        $totalPengeluaranLalu = $queryPengeluaranLalu->sum('total');
        // dd($this->totalPengeluaran);
        if ($totalPemasukanLalu > 0) {
            $this->persentasePemasukan = (($this->totalPemasukan - $totalPemasukanLalu) / $totalPemasukanLalu) * 100;
        } else {
            $this->persentasePemasukan = $this->totalPemasukan > 0 ? 100 : 0;
        }
        if ($totalPengeluaranLalu > 0) {
            $this->persentasePengeluaran = (($this->totalPengeluaran - $totalPengeluaranLalu) / $totalPengeluaranLalu) * 100;
        } else {
            $this->persentasePengeluaran = $this->totalPengeluaran > 0 ? 100 : 0;
        }
        $this->selisih = $this->totalSaldo - $saldoLama;
        $this->dispatch('datafilter', $this->filter);
    }

    public function getTotalSaldo()
    {
        $Pengeluaran = Pengeluaran::where('id_user', Auth::user()->id)->sum('total');
        $Pemasukan = Pemasukan::where('id_user', Auth::user()->id)->sum('total');
        $this->totalTagihan = Tagihan::where('id_user', Auth::user()->id)->sum('nominal');
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
        $this->totalTerpakai = Pengeluaran::where('id_user', Auth::user()->id)
            ->whereDate('tanggal_pengeluaran', now())
            ->sum('total');
        $this->batasHarian = BatasHarian::where('id_user', Auth::user()->id)->first();
        $this->tagihanBelumBayar = Tagihan::where('id_user', Auth::user()->id)->where('status', 'belum_dibayar')->count();
        $batas = $this->batasHarian ? $this->batasHarian->batas : 0;
        $this->persentase = $batas > 0 ? min(($this->totalTerpakai / $batas) * 100, 100) : 0;
    }

    public function render()
    {
        $this->filterData();

        return view(
            'livewire.dashboard.index'
        );
    }
}
