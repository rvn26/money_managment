<?php

namespace App\Livewire\Tagihan;

use App\Livewire\Concerns\WithPeriodeFilter;
use App\Models\Tagihan;
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
        $this->dispatch('tambahTagihan');
    }

    public function edit($id)
    {
        $this->dispatch('editTagihan', $id);
    }

    public function hapus($id)
    {
        $this->dispatch('hapusTagihan', $id);
    }

    public function render()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("SET lc_time_names = 'id_ID'");
        }

        $query = Tagihan::with(['user'])->where('id_user', Auth::user()->id);

        $this->applyPeriodeScope($query, 'jatuh_tempo');

        if ($this->cari) {
            $cari = $this->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kategori', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%")
                    ->orWhere('jatuh_tempo', 'like', "%{$cari}%")
                    ->orWhereRaw("DATE_FORMAT(jatuh_tempo, '%W') LIKE ?", ["%{$cari}%"])
                    ->orWhereRaw("DATE_FORMAT(jatuh_tempo, '%M') LIKE ?", ["%{$cari}%"]);
            });
        }

        return view('livewire.tagihan.index', [
            'tagihan' => $query->latest()->paginate(10),
        ]);
    }
}
