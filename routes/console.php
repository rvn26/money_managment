<?php

use App\Models\Tagihan;
use App\Models\User;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Ambil tagihan yang akan diupdate menjadi terlambat
    $tagihanTerlambat = Tagihan::with('user')
        ->where('status', '!=', 'lunas')
        ->where('jatuh_tempo', '<', Carbon::now('Asia/Jakarta')->startOfDay())
        ->get();

    if ($tagihanTerlambat->isEmpty()) {
        Log::info('Tidak ada tagihan yang perlu diupdate menjadi terlambat pada '.now());

        return;
    }

    // Update status menjadi terlambat
    DB::table('tagihans')
        ->where('status', '!=', 'lunas')
        ->where('jatuh_tempo', '<', Carbon::now('Asia/Jakarta')->startOfDay())
        ->update(['status' => 'terlambat']);

    Log::info("Sistem mengupdate {$tagihanTerlambat->count()} tagihan menjadi terlambat pada ".now());

    // Kirim notifikasi FCM ke masing-masing user
    $fcmService = app(FcmService::class);
    $groupedByUser = $tagihanTerlambat->groupBy('id_user');

    foreach ($groupedByUser as $userId => $tagihans) {
        $user = User::find($userId);
        if (! $user) {
            continue;
        }

        $count = $tagihans->count();
        if ($count === 1) {
            $tagihan = $tagihans->first();
            $nominal = number_format($tagihan->nominal, 0, ',', '.');
            $pesan = "Tagihan \"{$tagihan->nama}\" sebesar Rp{$nominal} sudah melewati jatuh tempo.";
        } else {
            $pesan = "Kamu memiliki {$count} tagihan yang sudah melewati jatuh tempo.";
        }

        $fcmService->sendToUser(
            $user,
            'Tagihan Terlambat',
            $pesan,
            'tagihan',
            ['aksi' => 'tagihan_terlambat', 'jumlah_tagihan' => (string) $count]
        );
    }
})->dailyAt('00:01');

// Pengingat tagihan H-1 sebelum jatuh tempo
Schedule::call(function () {
    $besok = Carbon::now('Asia/Jakarta')->addDay()->startOfDay();
    $besokAkhir = Carbon::now('Asia/Jakarta')->addDay()->endOfDay();

    $tagihanBesok = Tagihan::with('user')
        ->where('status', '!=', 'lunas')
        ->whereBetween('jatuh_tempo', [$besok, $besokAkhir])
        ->get();

    if ($tagihanBesok->isEmpty()) {
        return;
    }

    Log::info("Mengirim pengingat untuk {$tagihanBesok->count()} tagihan yang jatuh tempo besok.");

    $fcmService = app(FcmService::class);
    $groupedByUser = $tagihanBesok->groupBy('id_user');

    foreach ($groupedByUser as $userId => $tagihans) {
        $user = User::find($userId);
        if (! $user) {
            continue;
        }

        $count = $tagihans->count();
        if ($count === 1) {
            $tagihan = $tagihans->first();
            $nominal = number_format($tagihan->nominal, 0, ',', '.');
            $pesan = "Tagihan \"{$tagihan->nama}\" sebesar Rp{$nominal} jatuh tempo besok.";
        } else {
            $pesan = "Kamu memiliki {$count} tagihan yang jatuh tempo besok.";
        }

        $fcmService->sendToUser(
            $user,
            'Pengingat Tagihan',
            $pesan,
            'tagihan',
            ['aksi' => 'tagihan_pengingat', 'jumlah_tagihan' => (string) $count]
        );
    }
})->dailyAt('08:00');
