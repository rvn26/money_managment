<?php

namespace App\Livewire\Pertemanan;

use App\Models\Pertemanan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public ?string $cari = null;

    /** @var 'teman'|'masuk'|'terkirim' */
    public string $tab = 'teman';

    protected $queryString = [
        'tab' => ['except' => 'teman'],
        'cari' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->cari = request()->query('cari', $this->cari);
        $this->tab = request()->query('tab', $this->tab);
    }

    public function gantiTab(string $tab): void
    {
        $this->tab = in_array($tab, ['teman', 'masuk', 'terkirim'], true) ? $tab : 'teman';
    }

    public function tampilTambah(): void
    {
        $this->dispatch('tampiltambahteman');
    }

    public function render()
    {
        $userId = Auth::user()->id;

        $temanList = Pertemanan::with(['user', 'teman'])
            ->where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('id_user', $userId)->orWhere('id_teman', $userId);
            })
            ->when($this->cari, function ($q) use ($userId) {
                $cari = $this->cari;
                $q->where(function ($qq) use ($cari, $userId) {
                    $qq->whereHas('user', function ($u) use ($cari, $userId) {
                        $u->where('id', '!=', $userId)
                            ->where(function ($w) use ($cari) {
                                $w->where('name', 'like', "%{$cari}%")
                                    ->orWhere('email', 'like', "%{$cari}%");
                            });
                    })->orWhereHas('teman', function ($t) use ($cari, $userId) {
                        $t->where('id', '!=', $userId)
                            ->where(function ($w) use ($cari) {
                                $w->where('name', 'like', "%{$cari}%")
                                    ->orWhere('email', 'like', "%{$cari}%");
                            });
                    });
                });
            })
            ->latest()
            ->get();

        $permintaanMasuk = Pertemanan::with('user')
            ->where('id_teman', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $permintaanTerkirim = Pertemanan::with('teman')
            ->where('id_user', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('livewire.pertemanan.index', [
            'temanList' => $temanList,
            'permintaanMasuk' => $permintaanMasuk,
            'permintaanTerkirim' => $permintaanTerkirim,
        ]);
    }
}
