<?php

namespace App\Livewire\Pengeluaran;

use App\Models\kategori;
use Livewire\Component;

class Tambah extends Component
{
    public $show = false;
    public $kategori;

    protected $listeners = [
        'tampilTambah' => 'show'
    ];

    public function show(){
        $this->show = true;
    }

    public function tampilTambah()
    {
        $this->show = true;
    }

    public function mount(){
        $this->kategori = kategori::all();
        // dd($this->kategori);
    }
    public function render()
    {
        return view('livewire.pengeluaran.tambah');
    }
}
