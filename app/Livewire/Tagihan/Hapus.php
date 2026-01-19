<?php

namespace App\Livewire\Tagihan;

use Livewire\Component;

class Hapus extends Component
{
    public $show = false;
    public $id;

    protected $listeners = [
        'hapusTagihan' => 'hapushendel',
    ];

    public function hapushendel($id)
    {
        $this->id = $id;
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.tagihan.hapus');
    }
}
