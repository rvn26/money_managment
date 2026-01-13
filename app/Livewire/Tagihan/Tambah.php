<?php

namespace App\Livewire\Tagihan;

use App\Models\kategori_tagihan;
use Livewire\Component;

class Tambah extends Component
{
    public $show = false;
    public $kategori;

    protected $listeners = [
        'tambahTagihan' => 'show'
    ];

    public function show()
    {
        $this->show = true;
    }

    public function tampilTambah()
    {
        $this->show = true;
    }

    public function mount()
    {
        $this->kategori = kategori_tagihan::all();
        // dd($this->kategori);
    }
    public function render()
    {
        return view('livewire.tagihan.tambah');
    }
}
