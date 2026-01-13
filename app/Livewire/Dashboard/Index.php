<?php

namespace App\Livewire\Dashboard;

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


    public function mount()
    {


        // dd($this->totalPemasukan);
    }
    public function render()
    {
        $this->totalPengeluaran = pengeluaran::where('id_user', Auth::user()->id)->sum('total');
        $this->totalPemasukan = pemasukan::where('id_user', Auth::user()->id)->sum('total');
        $this->totalSaldo = $this->totalPemasukan - $this->totalPengeluaran;
        $tujuhHariLalu = now()->subDays(7);

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
            // 'totalpengeluaran' => $this->totalPengeluaran,
            // 'totalpemasukan' => $this->totalPemasukan,
        );
    }
}
