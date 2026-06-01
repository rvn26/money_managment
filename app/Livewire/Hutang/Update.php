<?php

namespace App\Livewire\Hutang;

use App\Models\Hutang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Update extends Component
{
    public bool $show = false;

    public ?int $id = null;

    public ?Hutang $hutang = null;

    /** @var list<string> */
    public array $statusOptions = ['belum_lunas', 'lunas', 'terlambat'];

    /** @var list<string> */
    public array $pembayaranOptions = ['Cash', 'Qris', 'Bank', 'Dana', 'Gopay'];

    protected $listeners = [
        'editHutang' => 'editHandler',
    ];

    public function editHandler($id): void
    {
        $hutang = Hutang::with('teman')
            ->where('id', $id)
            ->where('id_user', Auth::user()->id)
            ->first();

        if (! $hutang) {
            $this->show = false;

            return;
        }

        $this->id = $hutang->id;
        $this->hutang = $hutang;
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.hutang.update');
    }
}
