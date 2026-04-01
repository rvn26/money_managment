<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Scan extends Component
{
    use WithFileUploads;

    public $show = false;
    public $hasKategori = true;
    public $file;
    public $isScanned = false;
    public $scanResult = [];

    protected $listeners = [
        'tampilScan' => 'show'
    ];

    public function show()
    {
        $this->hasKategori = Kategori::where('id_user', Auth::user()->id)->exists();
        $this->reset(['file', 'isScanned', 'scanResult']);
        $this->show = true;
    }

    public function updatedFile()
    {
        // Validasi file jika diperlukan
        $this->validate([
            'file' => 'image|max:1024', // contoh: maksimal 1MB
        ]);
    }

    public function render()
    {
        return view('livewire.pengeluaran.scan');
    }
}
