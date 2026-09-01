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
        Schema::table('mengajar', function (Blueprint $table) {
            $table->unique(['guru_id', 'mapel_id', 'kelas_id', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mengajar', function (Blueprint $table) {
            $table->dropUnique(['guru_id', 'mapel_id', 'kelas_id', 'semester', 'tahun_ajaran']);
        });
    }
};
