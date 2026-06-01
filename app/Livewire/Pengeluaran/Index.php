<?php

namespace App\Livewire\Pengeluaran;

use App\Livewire\Concerns\WithPeriodeFilter;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithPeriodeFilter;

    public $show = false;

    public $cari;

    protected $updatesQueryString = [
        ['cari' => ['except' => '']],
        ['periode' => ['except' => 'bulan_ini']],
        ['bulanCustom' => ['except' => '']],
    ];

    public function mount()
    {
        $this->cari = request()->query('search', $this->cari);
    }

    public function tampilScan()
    {
        $this->dispatch('tampilScan');
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

        $query = Pengeluaran::with(['user', 'kategori'])
            ->where('id_user', Auth::user()->id);

        $this->applyPeriodeScope($query, 'tanggal_pengeluaran');

        if ($this->cari) {
            $cari = $this->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('tujuan', 'like', "%{$cari}%")
                    ->orWhere('tanggal_pengeluaran', 'like', "%{$cari}%")
                    ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%W') LIKE ?", ["%{$cari}%"])
                    ->orWhereRaw("DATE_FORMAT(tanggal_pengeluaran, '%M') LIKE ?", ["%{$cari}%"]);
            });
        }

        return view('livewire.pengeluaran.index', [
            'transaksi' => $query->latest()->paginate(10),
        ]);
    }
}
