<?php

namespace App\Livewire\Hutang;

use Livewire\Component;

class Hapus extends Component
{
    public bool $show = false;

    public ?int $id = null;

    protected $listeners = [
        'hapusHutang' => 'hapusHandler',
    ];

    public function hapusHandler($id): void
    {
        $this->id = (int) $id;
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.hutang.hapus');
    }
}
