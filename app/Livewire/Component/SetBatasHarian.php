<?php

namespace App\Livewire\Component;

use App\Models\batas_harian;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SetBatasHarian extends Component
{
    public $show = false;
    public $batasHarian, $batasHarianid;

    protected $listeners = [
        'setbatas' => 'sethendel'
    ];

    public function sethendel(){
        $this->batasHarian = batas_harian::where('id_user',Auth::user()->id)->first();
        $this->show = true;
    }

    public function simpan(){

    }
    public function render()
    {
        return view('livewire.component.set-batas-harian');
    }
}
