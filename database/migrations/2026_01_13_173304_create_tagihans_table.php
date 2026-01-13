<?php

use App\Models\kategori;
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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('kategori')->constrained('kategori_tagihans','id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama');
            $table->decimal('nominal',15,2);
            $table->date('jatuh_tempo');
            $table->enum('status',['belum_dibayar','lunas','terlambat'])->default('belum_dibayar');
            $table->enum('metode_pembayaran',['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])->default('Cash');
            $table->enum('pengulangan',allowed: ['sekali_bayar','bulanan','tahunan'])->default('sekali_bayar');
            $table->string('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
