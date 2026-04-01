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
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id')->cascadeOnDelete();
            $table->string('nama');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_pinjaman');
            $table->enum('status', ['belum_lunas', 'lunas', 'terlambat'])->default('belum_lunas');
            $table->enum('metode_pembayaran', ['Qris', 'Bank', 'Dana', 'Gopay', 'Cash']);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};
