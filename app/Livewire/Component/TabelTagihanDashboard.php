<?php

namespace App\Livewire\Component;

use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TabelTagihanDashboard extends Component
{

    public function render()
    {
        return view('livewire.component.tabel-tagihan-dashboard', [
            'tagihanterdekat' => Tagihan::where('id_user',Auth::user()->id)->// Opsional: hanya yang belum lunas
                where('jatuh_tempo', '>=', now()) // Hanya ambil tanggal hari ini ke depan
                ->orderBy('jatuh_tempo', 'asc') // Urutkan dari yang paling dekat
                ->take(3) // Batasi hanya 3 data
                ->get(),
        ]);
    }
}
