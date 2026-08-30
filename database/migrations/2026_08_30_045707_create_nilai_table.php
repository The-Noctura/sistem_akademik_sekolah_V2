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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('mengajar_id')->constrained('mengajar')->onDelete('cascade');
            $table->foreignId('diinput_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('jenis', ['tugas', 'uts', 'uas']);
            $table->decimal('nilai', 5, 2);
            $table->timestamp('tanggal_input');
            $table->timestamps();
            
            $table->unique(['siswa_id', 'mengajar_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
