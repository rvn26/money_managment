<?php

namespace App\Livewire\Pemasukan;

use App\Models\Pemasukan;
use Livewire\Component;

class Update extends Component
{
    public $show = false, $id;
    public $kategori =[], $pembayaran = [], $status = [], $pemasukan;

    protected $listeners = [
        'editpemasukan' => 'edithendel',
    ];

    public function edithendel($id)
    {
        $this->id = $id;
        $this->pemasukan = Pemasukan::find($this->id);
        $this->show = true;
    }

    public function mount()
    {
        
        $this->kategori = ['Gaji', 'Bonus', 'Penjualan', 'Investasi', 'Lain-lain'];
        $this->pembayaran = ['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'];
        $this->status = ['draft', 'approved', 'paid'];
    }
    public function render()
    {
        return view('livewire.pemasukan.update'
        );
    }
}
