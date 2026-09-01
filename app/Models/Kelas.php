<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;
    protected $table = 'kelas';
    
    protected $fillable = ['nama_kelas', 'tingkat', 'wali_kelas_id', 'tahun_ajaran'];
    
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }
    
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
    
    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }
    
    public function jadwal()
    {
        return $this->hasManyThrough(Jadwal::class, Mengajar::class);
    }
}
