<?php

namespace App\Livewire\Pertemanan;

use Livewire\Component;

class Tambah extends Component
{
    public bool $show = false;

    protected $listeners = [
        'tampiltambahteman' => 'show',
    ];

    public function show(): void
    {
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.pertemanan.tambah');
    }
}
