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
        Schema::create('tefa_products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('jurusan_code', 20); // TPM, TGM, TKRO, EIND, MKA, TEKS, TJKT, PPLG, MM/BP
            $table->enum('kategori', ['barang', 'jasa'])->default('barang');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->string('foto')->nullable();
            $table->enum('status_stok', ['tersedia', 'habis'])->default('tersedia');
            $table->string('nomor_wa', 25)->nullable(); // e.g. 6281234567890
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tefa_products');
    }
};

