<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id');
            $table->date('tanggal');
            $table->enum('jenis', ['gaji', 'bonus', 'penjualan', 'investasi', 'lain-lain'])->default('gaji');
            $table->decimal('total', 15, 2);
            $table->enum('metode_pembayaran', ['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])->default('Cash');
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            $table->String('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
