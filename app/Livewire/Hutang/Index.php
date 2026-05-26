<?php

namespace App\Livewire\Hutang;

use App\Models\Hutang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $cari;

    protected $updatesQueryString = [
        ['search' => ['except' => '']],
    ];

    public function tampilTambah()
    {
        $this->dispatch('tampiltambah');
    }

    public function edit($id)
    {
        dd("hello");
        $this->dispatch('editHutang', $id);
    }
    public function hapus($id)
    {
        // dd("hello");
        $this->dispatch('hapusHutang', $id);
    }

    public function render()
    {
        return view('livewire.hutang.index', [

            'hutang' => $this->cari === null ?
                Hutang::with(['user'])->where('id_user', Auth::user()->id)->latest()->paginate(10) :
                Hutang::with(['user'])->where('id_user', Auth::user()->id)->where('nama', 'like', "%{$this->cari}%")->latest()->paginate(10),

        ]);
    }
}
