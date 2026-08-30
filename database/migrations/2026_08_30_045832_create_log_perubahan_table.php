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
        Schema::create('log_perubahan', function (Blueprint $table) {
            $table->id();
            $table->string('tabel');
            $table->unsignedBigInteger('record_id');
            $table->enum('aksi', ['insert', 'update', 'delete']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('waktu');
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_perubahan');
    }
};
