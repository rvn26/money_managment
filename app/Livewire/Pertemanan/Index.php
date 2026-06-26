<?php

namespace App\Livewire\Pertemanan;

use App\Models\Pertemanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Index extends Component
{
    public ?string $cari = null;

    /** @var 'teman'|'masuk'|'terkirim' */
    public string $tab = 'teman';

    public bool $showKonfirmasiModal = false;

    public ?int $idPertemananYangDipilih = null;

    public ?string $tipeAksi = null; // 'hapus' | 'batalkan' | 'tolak'

    public ?string $namaTarget = null;

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

    public function konfirmasiHapus(int $id, string $nama): void
    {
        $this->idPertemananYangDipilih = $id;
        $this->namaTarget = $nama;
        $this->tipeAksi = 'hapus';
        $this->showKonfirmasiModal = true;
    }

    public function konfirmasiBatalkan(int $id, string $nama): void
    {
        $this->idPertemananYangDipilih = $id;
        $this->namaTarget = $nama;
        $this->tipeAksi = 'batalkan';
        $this->showKonfirmasiModal = true;
    }

    public function konfirmasiTolak(int $id, string $nama): void
    {
        $this->idPertemananYangDipilih = $id;
        $this->namaTarget = $nama;
        $this->tipeAksi = 'tolak';
        $this->showKonfirmasiModal = true;
    }

    public function batalAksi(): void
    {
        $this->showKonfirmasiModal = false;
        $this->idPertemananYangDipilih = null;
        $this->namaTarget = null;
        $this->tipeAksi = null;
    }

    public function eksekusiAksi(): void
    {
        if (! $this->idPertemananYangDipilih) {
            return;
        }

        try {
            $user = Auth::user();
            $pertemanan = Pertemanan::where('id', $this->idPertemananYangDipilih)
                ->where(function ($q) use ($user) {
                    $q->where('id_user', $user->id)->orWhere('id_teman', $user->id);
                })
                ->firstOrFail();

            $pertemanan->delete();

            $message = '';
            if ($this->tipeAksi === 'hapus') {
                $message = 'Pertemanan berhasil dihapus';
            } elseif ($this->tipeAksi === 'batalkan') {
                $message = 'Permintaan pertemanan berhasil dibatalkan';
            } elseif ($this->tipeAksi === 'tolak') {
                $message = 'Permintaan pertemanan berhasil ditolak';
            }

            session()->flash('message', $message);
        } catch (\Exception $e) {
            Log::error('Gagal memproses aksi pertemanan: '.$e->getMessage());
            session()->flash('error', 'Gagal memproses permintaan, silakan coba lagi');
        }

        $this->batalAksi();
    }
}
