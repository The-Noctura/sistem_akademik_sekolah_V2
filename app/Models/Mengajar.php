<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mengajar extends Model
{
    protected $table = 'mengajar';
    
    protected $fillable = ['guru_id', 'mapel_id', 'kelas_id', 'tahun_ajaran', 'semester'];
    
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
    
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
    
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    
    public function rekapNilai()
    {
        return $this->hasMany(RekapNilai::class);
    }
    
    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class);
    }
}
