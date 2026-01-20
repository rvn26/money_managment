<?php

namespace App\Livewire\Component;

use App\Models\pemasukan;
use App\Models\pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Volt\Compilers\Mount;

class TabelTransaksiTerbaru extends Component
{
    public $transaksi;

    public function Mount()
    {
        DB::statement("SET lc_time_names = 'id_ID'");
        $pemasukan = pemasukan::whereDate('created_at', '>=', Carbon::now()->startOfMonth())->where('id_user',Auth::user()->id)->latest()->limit(5)->get()->map(function ($item) {
            return [
                'tanggal_buat'          => $item->created_at,
                'nama'                  => $item->jenis ?? 'Pemasukan Kas',
                'metode_pembayaran'     => $item->metode_pembayaran,
                'total'                 => $item->total,
                'tanggal_transaksi'     => $item->tanggal,
                'status'                => $item->status, // Biasanya pemasukan langsung lunas
                'jenis'                 => 'Pemasukan',
            ];
        });
        $pengeluaran = pengeluaran::whereDate('created_at', '>=', Carbon::now()->startOfMonth())->where('id_user',Auth::user()->id)->latest()->limit(5)->get()->map(function ($item) {
            return [
                'tanggal_buat'          => $item->created_at,
                'nama'                  => $item->tujuan ?? 'pembelian',
                'metode_pembayaran'     => $item->metode_pembayaran,
                'total'                 => $item->total,
                'tanggal_transaksi'     => $item->tanggal_pengeluaran,
                'status'                => $item->status, // Biasanya pemasukan langsung lunas
                'jenis'                 => 'Pengeluaran',
            ];
        });

        $this->transaksi = $pemasukan->concat($pengeluaran)
            ->sortByDesc('tanggal_buat')
            ->take(5);
    }
    public function render()
    {
        return view('livewire.component.tabel-transaksi-terbaru');
    }
}
