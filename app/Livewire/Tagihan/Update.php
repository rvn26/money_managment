<?php

namespace App\Livewire\Tagihan;

use App\Models\kategori;
use App\Models\kategori_tagihan;
use App\Models\tagihan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{

    public $show = false, $id;
    public $kategori, $tagihan;
    public $pembayaran = [], $status = [], $pengulangan = [];

    protected $listeners = [
        'editTagihan' => 'edithendel',
    ];

    public function edithendel($id)
    {
        $this->id = $id;
        $this->tagihan = tagihan::find($this->id);
        $this->show = true;
    }

    public function mount()
    {
        $this->kategori = kategori_tagihan::where('id_user', Auth::user()->id)->get();
        // $this->kategori = ['Gaji', 'Bonus', 'Penjualan', 'Investasi', 'Lain-lain'];
        $this->pembayaran = ['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'];
        $this->status = ['Belum_Dibayar', 'Lunas'];
        $this->pengulangan = ['Sekali_bayar', 'Bulanan', 'Tahunan'];
        // dd($this->kategori);
    }
    public function render()
    {
        return view('livewire.tagihan.update');
    }
}
