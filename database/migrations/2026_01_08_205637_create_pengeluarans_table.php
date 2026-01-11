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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users','id');
            $table->foreignId('id_kategori')->constrained('kategoris','id');
            $table->date('tanggal_penggeluaran');
            $table->decimal('total', 15, 2);
            $table->text('description');
            $table->string('tujuan')->nullable();
            $table->enum('metode_pembayaran',['Qris','Bank','Dana','Gopay','Cash'])->default('Cash');
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
