<?php

namespace App\Livewire\Kategoritagihan;

use Livewire\Component;

class Hapus extends Component
{

    public $show = false;
    public $id;

    protected $listeners = [
        'hapusKategoritagihan' => 'hapushendel',
    ];

    public function hapushendel($id)
    {
        $this->id = $id;
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.kategoritagihan.hapus');
    }
}
