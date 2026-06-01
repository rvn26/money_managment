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
        Schema::create('pertemanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('id_teman')->constrained('users', 'id')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->timestamps();

            $table->unique(['id_user', 'id_teman']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemanans');
    }
};
