<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;

class Index extends Component
{
    public $show = false;

    public function tampilTambah(){
        $this->dispatch('tampilTambah');
    }
    public function render()
    {
        return view('livewire.pengeluaran.index');
    }
}
