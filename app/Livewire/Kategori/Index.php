<?php

namespace App\Livewire\Kategori;

use Livewire\Component;

class Index extends Component
{
    public function tambahTampil(){
        $this->dispatch('tampil');
    }
    public function render()
    {
        return view('livewire.kategori.index');
    }
}
