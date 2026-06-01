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
        Schema::table('hutangs', function (Blueprint $table) {
            $table->foreignId('id_teman')
                ->nullable()
                ->after('id_user')
                ->constrained('users', 'id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hutangs', function (Blueprint $table) {
            $table->dropForeign(['id_teman']);
            $table->dropColumn('id_teman');
        });
    }
};
