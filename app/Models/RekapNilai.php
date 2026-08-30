<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    protected $table = 'rekap_nilai';
    
    protected $fillable = ['siswa_id', 'mengajar_id', 'semester', 'rata_rata', 'nilai_akhir', 'predikat'];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
}
