<?php

namespace App\Livewire\Hutang;

use Livewire\Component;

class Tambah extends Component
{

    public $show = false;
    public $errorMessage ;
    protected $listeners = [
        'tampiltambah' => 'show'
    ];
    public function show(){
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.hutang.tambah');
    }
}
