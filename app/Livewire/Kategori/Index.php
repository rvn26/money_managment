<?php

namespace App\Livewire\Kategori;

use App\Models\kategori;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function tambahTampil(){
        $this->dispatch('tampil');
    }
    public function render()
    {
        return view('livewire.kategori.index',[
            'kategori' => kategori::where('id_user',Auth::user()->id)->get(),
        ]);
    }
}
