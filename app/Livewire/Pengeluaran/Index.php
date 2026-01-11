<?php

namespace App\Livewire\Pengeluaran;

use App\Models\pengeluaran;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $show = false;
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
        $this->dispatch('tampilTambah');
    }
    public function render()
    {
        return view('livewire.pengeluaran.index', [
            // Contoh di Laravel (Eager Loading)
            'transaksi' => $this->cari === null ?
                pengeluaran::with(['user', 'kategori'])->latest()->paginate(10) :
                pengeluaran::with(['user', 'kategori'])->when($this->cari, function ($query) {
                    $query->where(function ($q) {
                        $q->where('tujuan', 'like', '%' . $this->cari . '%')
                            ->orWhere('tanggal_pengeluaran', 'like', '%' . $this->cari . '%')
                            ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%W') LIKE ?", ["%{$this->cari}%"])
                            ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%M') LIKE ?", ["%{$this->cari}%"]);
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
