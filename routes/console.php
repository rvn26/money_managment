<?php

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
    $count = DB::table('tagihans') // Pastikan nama tabel sesuai
        ->where('status', '!=', 'lunas')
        ->where('jatuh_tempo', '<', Carbon::now('Asia/Jakarta')->startOfDay())
        ->update(['status' => 'terlambat']);
    Log::info("Sistem mengupdate $count tagihan menjadi terlambat pada " . now());
})->dailyAt('00:01');
