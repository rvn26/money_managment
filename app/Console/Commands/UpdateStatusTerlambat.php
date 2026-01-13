<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStatusTerlambat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-terlambat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $affected = DB::table('tagihans') // ganti dengan nama tabel Anda
            ->where('status', '!=', 'lunas')
            ->where('jatuh_tempo', '<', now()->startOfDay()) // cari yang sudah lewat hari ini
            ->update(['status' => 'terlambat']);

        $this->info("Berhasil mengupdate $affected data tagihan menjadi terlambat.");
    }
}
