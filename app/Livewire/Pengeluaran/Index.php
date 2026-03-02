<?php

namespace App\Livewire\Pengeluaran;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public function edit($id)
    {
        $this->dispatch('editPengeluaran', $id);
    }
    public function hapus($id)
    {
        $this->dispatch('hapusPengeluaran', $id);
    }

    public function render()
    {
        DB::statement("SET lc_time_names = 'id_ID'");
        return view('livewire.pengeluaran.index', [
            // Contoh di Laravel (Eager Loading)
            'transaksi' => $this->cari === null ?
                Pengeluaran::with(['user', 'kategori'])->where('id_user', Auth::user()->id)->latest()->paginate(10) :
                Pengeluaran::with(['user', 'kategori'])
                ->where('id_user', Auth::user()->id)
                ->when($this->cari, function ($query) {
                    $query->where(function ($q) {
                        $q->where('tujuan', 'like', '%' . $this->cari . '%')
                            ->orWhere('tanggal_pengeluaran', 'like', '%' . $this->cari . '%')
                            ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%W') LIKE ?", ["%{$this->cari}%"])
                            ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%M') LIKE ?", ["%{$this->cari}%"]);
                    });
                })
                ->latest()
                ->paginate(8),
        ]);
    }
}
