<?php

namespace App\Livewire\Kategori;

use Livewire\Component;

class Tambah extends Component
{
    public $show = false;

    protected $listeners = [
        'tampil' => 'tampilHendel'
    ];

    public function tampilHendel(){
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.kategori.tambah');
    }
}
