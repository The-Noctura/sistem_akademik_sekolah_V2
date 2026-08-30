<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    protected $table = 'rekap_absensi';
    
    protected $fillable = ['siswa_id', 'mengajar_id', 'semester', 'total_hadir', 'total_izin', 'total_sakit', 'total_alpa', 'persentase_hadir'];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
}
