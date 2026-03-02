<?php

namespace App\Livewire\Kategoritagihan;

use App\Models\KategoriTagihan;
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
            'kategori' => KategoriTagihan::where('id_user', Auth::user()->id)->get(),
        ]);
    }
}
