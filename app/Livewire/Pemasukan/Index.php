<?php

namespace App\Livewire\Pemasukan;

use App\Livewire\Concerns\WithPeriodeFilter;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithPeriodeFilter;

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

    public function tampilTambah()
    {
        $this->dispatch('tambahpemasukan');
    }

    public function edit($id)
    {
        $this->dispatch('editpemasukan', $id);
    }

    public function hapus($id)
    {
        $this->dispatch('hapusPemasukan', $id);
    }

    public function render()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("SET lc_time_names = 'id_ID'");
        }

        $query = Pemasukan::with(['user'])
            ->where('id_user', Auth::user()->id);

        $this->applyPeriodeScope($query, 'tanggal');

        if ($this->cari) {
            $cari = $this->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('jenis', 'like', "%{$cari}%")
                    ->orWhere('tanggal', 'like', "%{$cari}%")
                    ->orWhereRaw("DATE_FORMAT(tanggal, '%W') LIKE ?", ["%{$cari}%"])
                    ->orWhereRaw("DATE_FORMAT(tanggal, '%M') LIKE ?", ["%{$cari}%"]);
            });
        }

        return view('livewire.pemasukan.index', [
            'transaksi' => $query->latest()->paginate(10),
        ]);
    }
}
