<?php

namespace App\Livewire\Kategoritagihan;

use App\Models\kategori_tagihan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function tambahTampil()
    {
        $this->dispatch('tampil');
    }

    public function hapus($id)
    {
        $this->dispatch('hapusKategoritagihan', $id);
    }
    public function render()
    {
        return view('livewire.kategoritagihan.index', [
            'kategori' => kategori_tagihan::where('id_user', Auth::user()->id)->get(),
        ]);
    }
}
