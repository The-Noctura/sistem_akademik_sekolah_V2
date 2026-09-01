<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;
    protected $table = 'siswa';
    
    protected $fillable = ['user_id', 'nis', 'nama', 'kelas_id', 'jenis_kelamin', 'tanggal_lahir'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
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
