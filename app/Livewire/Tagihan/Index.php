<?php

namespace App\Livewire\Tagihan;

use App\Models\tagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $cari;

    protected $updatesQueryString = [
        ['search' => ['except' => '']],
    ];

    public function mount()
    {
        $this->cari = request()->query('search', $this->cari);
    }
    public function tampilTambah()
    {
        // dd("hello");
        $this->dispatch('tambahTagihan');
    }
    public function edit($id)
    {
        // dd("hello");
        $this->dispatch('editTagihan', $id);
    }
    public function render()
    {
        DB::statement("SET lc_time_names = 'id_ID'");
        return view('livewire.tagihan.index', [
            'tagihan' => $this->cari === null ?
                tagihan::with(['user'])->where('id_user', Auth::user()->id)->latest()->paginate(10) :
                tagihan::with(['user'])
                ->where('id_user', Auth::user()->id)
                ->when($this->cari, function ($query) {
                    $query->where(function ($q) {
                        $q->where('kategori', 'like', '%' . $this->cari . '%')
                            ->orWhere('jatuh_tempo', 'like', '%' . $this->cari . '%')
                            ->orWhereRaw("DATE_FORMAT(jatuh_tempo, '%W') LIKE ?", ["%{$this->cari}%"])
                            ->orWhereRaw("DATE_FORMAT(jatuh_tempo, '%M') LIKE ?", ["%{$this->cari}%"]);
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
