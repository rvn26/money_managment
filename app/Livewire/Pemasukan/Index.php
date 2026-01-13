<?php

namespace App\Livewire\Pemasukan;

use App\Models\pemasukan;
use App\Models\pengeluaran;
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
        $this->dispatch('tambahpemasukan');
    }

    public function render()
    {
        DB::statement("SET lc_time_names = 'id_ID'");
        return view('livewire.pemasukan.index', [
            'transaksi' => $this->cari === null ?
                pemasukan::with(['user'])->where('id_user',Auth::user()->id)->latest()->paginate(10) :
                pemasukan::with(['user'])
                ->where('id_user',Auth::user()->id)
                ->when($this->cari, function ($query) {
                    $query->where(function ($q) {
                        $q->where('jenis', 'like', '%' . $this->cari . '%')
                            ->orWhere('tanggal', 'like', '%' . $this->cari . '%')
                            ->orWhereRaw("DATE_FORMAT(tanggal, '%W') LIKE ?", ["%{$this->cari}%"])
                            ->orWhereRaw("DATE_FORMAT(tanggal, '%M') LIKE ?", ["%{$this->cari}%"]);
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
