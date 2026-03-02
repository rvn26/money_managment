<?php

namespace App\Livewire\Kategori;

use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function tambahTampil(){
        $this->dispatch('tampil');
    }

    public function hapus($id){
        $this->dispatch('hapusKategori', $id);
    }
    public function render()
    {
        return view('livewire.kategori.index',[
            'kategori' => Kategori::where('id_user',Auth::user()->id)->get(),
        ]);
    }
}
