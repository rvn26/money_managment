<?php

namespace App\Livewire\Dashboard;

use App\Models\batas_harian;
use App\Models\pemasukan;
use App\Models\pengeluaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $totalSaldo = 0;
    public $totalPemasukan = 0;
    public $totalPengeluaran = 0;
    public $selisih = 0;
    public $batasHarian, $totalTerpakai, $persentase;

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

    public function mount()
    {
        $this->totalTerpakai = pengeluaran::where('id_user', Auth::user()->id)
            ->whereDate('tanggal_pengeluaran', now())
            ->sum('total');
        $this->batasHarian = batas_harian::where('id_user', Auth::user()->id)->first();
        // dd($this->totalTerpakai);
        $batas = $this->batasHarian ? $this->batasHarian->batas : 0;
        $this->persentase = $batas > 0 ? min(($this->totalTerpakai / $batas) * 100, 100) : 0;
    }
    public function render()
    {
        $this->totalPengeluaran = pengeluaran::where('id_user', Auth::user()->id)->sum('total');
        $this->totalPemasukan = pemasukan::where('id_user', Auth::user()->id)->sum('total');
        $this->totalSaldo = $this->totalPemasukan - $this->totalPengeluaran;
        $tujuhHariLalu = now()->subDays(7);

        // 7hari
        $pemasukanLama = pemasukan::where('id_user', Auth::user()->id)
            ->where('created_at', '<', $tujuhHariLalu)
            ->sum('total');

        $pengeluaranLama = pengeluaran::where('id_user', Auth::user()->id)
            ->where('created_at', '<', $tujuhHariLalu)
            ->sum('total');

        $saldoLama = $pemasukanLama - $pengeluaranLama;
        $this->selisih = $this->totalSaldo - $saldoLama;



        return view(
            'livewire.dashboard.index'
        );
    }
}
