<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HasilScan extends Component
{
    public $categories = [];
    public $defaultDate;

    public function mount()
    {
        $this->categories = Kategori::where('id_user', Auth::user()->id)
            ->get(['id', 'nama'])
            ->toArray();
        $this->defaultDate = now()->toDateString();
    }

    public function render()
    {
        return view('livewire.pengeluaran.hasil-scan');
    }
}
