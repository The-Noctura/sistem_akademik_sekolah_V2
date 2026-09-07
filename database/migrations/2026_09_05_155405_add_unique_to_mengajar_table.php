<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mengajar', function (Blueprint $table) {
            $table->unique(['guru_id', 'mapel_id', 'kelas_id', 'tahun_ajaran', 'semester'], 'mengajar_unique_kombinasi');
        });
    }

    public function down(): void
    {
        Schema::table('mengajar', function (Blueprint $table) {
            $table->dropUnique('mengajar_unique_kombinasi');
        });
    }
};