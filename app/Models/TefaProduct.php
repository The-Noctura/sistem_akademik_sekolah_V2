<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefaProduct extends Model
{
    use HasFactory;

    protected $table = 'tefa_products';

    protected $fillable = [
        'nama_produk',
        'jurusan_code',
        'kategori',
        'deskripsi',
        'harga',
        'foto',
        'status_stok',
        'nomor_wa',
    ];

    public static array $jurusanMap = [
        'TPM'  => 'Teknik Pemesinan',
        'TGM'  => 'Desain Gambar Mesin',
        'TKRO' => 'Teknik Kendaraan Ringan Otomotif',
        'EIND' => 'Teknik Elektronika Industri',
        'MKA'  => 'Mekatronika',
        'TEKS' => 'Teknologi Penyempurnaan Tekstil',
        'TJKT' => 'Teknik Jaringan Komputer & Telekomunikasi',
        'PPLG' => 'Pengembangan Perangkat Lunak & Gim',
        'MM'   => 'Broadcasting & Perfilman',
    ];

    public function getJurusanNameAttribute(): string
    {
        return self::$jurusanMap[strtoupper($this->jurusan_code)] ?? $this->jurusan_code;
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getWhatsappUrlAttribute(): string
    {
        $wa = preg_replace('/[^0-9]/', '', $this->nomor_wa ?? '6281234567890');
        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }

        $text = "Halo Admin TEFA SMKN 1 Katapang, saya tertarik untuk memesan / menanyakan produk:\n" .
                "• Produk: {$this->nama_produk}\n" .
                "• Jurusan: {$this->jurusan_name}\n" .
                "• Harga: {$this->formatted_harga}\n\n" .
                "Apakah produk/jasa ini masih tersedia?";

        return 'https://wa.me/' . $wa . '?text=' . rawurlencode($text);
    }
}

