<?php

namespace App\Livewire\Kategori;

use Livewire\Component;

class Hapus extends Component
{
    public $show = false;
    public $id;

    protected $listeners = [
        'hapusKategori' => 'hapushendel',
    ];

    public function hapushendel($id)
    {
        $this->id = $id;
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.kategori.hapus');
    }
}
