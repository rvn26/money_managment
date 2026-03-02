<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Pengeluaran;
use Livewire\Component;

class Hapus extends Component
{
    public $show = false;
    public $id;

    protected $listeners = [
        'hapusPengeluaran' => 'hapushendel',
    ];

    public function hapushendel($id)
    {
        $this->id = $id;
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.pengeluaran.hapus');
    }
}
