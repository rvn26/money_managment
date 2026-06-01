<?php

namespace App\Livewire\Hutang;

use App\Livewire\Concerns\WithPeriodeFilter;
use App\Models\Hutang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithPeriodeFilter;

    public ?string $cari = null;

    protected $updatesQueryString = [
        ['cari' => ['except' => '']],
        ['periode' => ['except' => 'bulan_ini']],
        ['bulanCustom' => ['except' => '']],
    ];

    public function tampilTambah(): void
    {
        $this->dispatch('tampiltambah');
    }

    public function edit($id): void
    {
        $this->dispatch('editHutang', $id);
    }

    public function hapus($id): void
    {
        $this->dispatch('hapusHutang', $id);
    }

    public function render()
    {
        $userId = Auth::user()->id;

        $query = Hutang::with(['user', 'teman'])->where('id_user', $userId);

        $this->applyPeriodeScope($query, 'tanggal_pinjaman');

        if ($this->cari) {
            $cari = $this->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                    ->orWhereHas('teman', function ($t) use ($cari) {
                        $t->where('name', 'like', "%{$cari}%")
                            ->orWhere('email', 'like', "%{$cari}%");
                    });
            });
        }

        return view('livewire.hutang.index', [
            'hutang' => $query->latest()->paginate(10),
        ]);
    }
}
