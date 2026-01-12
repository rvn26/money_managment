<?php

namespace App\Livewire\Pemasukan;

use Livewire\Component;

class Tambah extends Component
{
    public $show = false;
    public $kategori;

    protected $listeners = [
        'tambahpemasukan' => 'show'
    ];

    public function show()
    {
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.pemasukan.tambah');
    }
}
