<?php

namespace App\Livewire\Component;

use Livewire\Component;

class FiturBelumAda extends Component
{
    public $show = false;

    protected $listeners = [
        'cekfitur' => 'cekhendel',
    ];

    public function cekhendel(){
        $this->show = true;
    }
    public function render()
    {
        return view('livewire.component.fitur-belum-ada');
    }
}
