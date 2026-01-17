<?php

namespace App\Livewire\Pengeluaran;

use App\Models\kategori;
use App\Models\pengeluaran;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{

    public $show = false;
    public $kategori;
    public $pembayaran = [], $status = [];
    public $id;

    protected $listeners = [
        'editPengeluaran' => 'edithendel'
    ];

    public function edithendel($id)
    {
        $this->id = $id;
        // dd($this->id);
        $this->show = true;
    }

    public function mount()
    {
        
        $this->kategori = kategori::where('id_user', Auth::user()->id)->get();
        $this->pembayaran =['Qris', 'Bank', 'Dana' ,'Gopay', 'Cash'];
        $this->status =['draft', 'approved', 'paid'];
        // dd($this->kategori);
    }
    public function render()
    {
        return view('livewire.pengeluaran.update',[
            'pengeluaran' => pengeluaran::find($this->id),
        ]);
    }
}
