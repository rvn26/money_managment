<?php

namespace App\Livewire\Component;

use Livewire\Component;

class LaporanModal extends Component
{
    public bool $show = false;

    /** @var list<string> */
    public array $sections = ['pemasukan', 'pengeluaran', 'hutang'];

    /** 'bulan_ini'|'setahun'|'custom' */
    public string $periode = 'bulan_ini';

    public ?string $tanggalDari = null;

    public ?string $tanggalSampai = null;

    protected $listeners = [
        'bukaLaporan' => 'buka',
    ];

    public function mount(): void
    {
        $this->tanggalDari = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSampai = now()->endOfMonth()->format('Y-m-d');
    }

    public function buka(): void
    {
        $this->resetErrorBag();
        $this->show = true;
    }

    public function tutup(): void
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.component.laporan-modal');
    }
}
