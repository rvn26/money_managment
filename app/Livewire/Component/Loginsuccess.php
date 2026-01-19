<?php

namespace App\Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Loginsuccess extends Component
{
    public $show = false;
    public $userName = '';

    public function mount()
    {
        if (session()->has('login_success')) {
            $this->show = true;
            $this->userName = Auth::user()->name;
        }
    }
    public function render()
    {
        return view('livewire.component.loginsuccess');
    }
}
