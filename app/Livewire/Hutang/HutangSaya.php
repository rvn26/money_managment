<?php

namespace App\Livewire\Hutang;

use App\Livewire\Concerns\WithPeriodeFilter;
use App\Models\Hutang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HutangSaya extends Component
{
    use WithPagination;
    use WithPeriodeFilter;

    public ?string $cari = null;

    protected $updatesQueryString = [
        ['cari' => ['except' => '']],
        ['periode' => ['except' => 'bulan_ini']],
        ['bulanCustom' => ['except' => '']],
    ];

    public function render()
    {
        $userId = Auth::user()->id;

        $query = Hutang::with(['user'])->where('id_teman', $userId);

        $this->applyPeriodeScope($query, 'tanggal_pinjaman');

        if ($this->cari) {
            $cari = $this->cari;
            $query->whereHas('user', function ($u) use ($cari) {
                $u->where('name', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%");
            });
        }

        $hutang = $query->latest()->paginate(10);

        $totalAktif = (clone $query)
            ->where('status', '!=', 'lunas')
            ->sum('jumlah');

        return view('livewire.hutang.hutang-saya', [
            'hutang' => $hutang,
            'totalAktif' => $totalAktif,
        ]);
    }
}
