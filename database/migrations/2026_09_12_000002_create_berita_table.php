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
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori', 50)->default('Umum'); // Prestasi, PPDB, Kerjasama, Akademik, Kegiatan
            $table->string('thumbnail')->nullable();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->enum('status', ['publikasi', 'draft'])->default('publikasi');
            $table->foreignId('penulis_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};

