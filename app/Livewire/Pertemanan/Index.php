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
        $user = Auth::user();

        $temanList = Pertemanan::with(['user', 'teman'])
            ->where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('id_user', $user->id)->orWhere('id_teman', $user->id);
            })
            ->when($this->cari, function ($q) use ($user) {
                $cari = $this->cari;
                $q->where(function ($qq) use ($cari, $user) {
                    $qq->whereHas('user', function ($u) use ($cari, $user) {
                        $u->where('id', '!=', $user->id)
                            ->where(function ($w) use ($cari) {
                                $w->where('name', 'like', "%{$cari}%")
                                    ->orWhere('email', 'like', "%{$cari}%");
                            });
                    })->orWhereHas('teman', function ($t) use ($cari, $user) {
                        $t->where('id', '!=', $user->id)
                            ->where(function ($w) use ($cari) {
                                $w->where('name', 'like', "%{$cari}%")
                                    ->orWhere('email', 'like', "%{$cari}%");
                            });
                    });
                });
            })
            ->latest()
            ->get()->map(function ($pertemanan) use ($user) {
                $userAsli = $pertemanan->user;
                $temanAsli = $pertemanan->teman;

                if ($pertemanan->id_teman === $user->id) {
                    $pertemanan->id_user = $temanAsli->id;
                    $pertemanan->id_teman = $userAsli->id;

                    $pertemanan->setRelation('user', $temanAsli);
                    $pertemanan->setRelation('teman', $userAsli);
                }
                // dd($pertemanan);
                return $pertemanan;
            });
        $permintaanMasuk = Pertemanan::with('user')
            ->where('id_teman', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $permintaanTerkirim = Pertemanan::with('teman')
            ->where('id_user', $user->id)
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
