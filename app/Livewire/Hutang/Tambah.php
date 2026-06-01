<?php

namespace App\Livewire\Hutang;

use App\Models\Pertemanan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tambah extends Component
{
    public bool $show = false;

    public ?string $errorMessage = null;

    protected $listeners = [
        'tampiltambah' => 'show',
    ];

    public function show(): void
    {
        $this->show = true;
    }

    /**
     * Daftar teman aktif (status accepted) dari user saat ini.
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    public function getTemanProperty()
    {
        $userId = Auth::user()->id;

        $idTeman = Pertemanan::query()
            ->where('status', 'accepted')
            ->where(function ($q) use ($userId) {
                $q->where('id_user', $userId)->orWhere('id_teman', $userId);
            })
            ->get()
            ->map(fn ($p) => $p->id_user === $userId ? $p->id_teman : $p->id_user)
            ->unique();

        return User::whereIn('id', $idTeman)->orderBy('name')->get(['id', 'name', 'email']);
    }

    public function render()
    {
        return view('livewire.hutang.tambah', [
            'temanList' => $this->teman,
        ]);
    }
}
