<?php

namespace App\Livewire\Pemasukan;

use Livewire\Component;

class Hapus extends Component
{
    public $show = false;
    public $id;

    protected $listeners = [
        'hapusPemasukan' => 'hapushendel',
    ];

    public function hapushendel($id)
    {
        $this->id = $id;
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.pemasukan.hapus');
    }
}
