<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    
    protected $fillable = ['siswa_id', 'mengajar_id', 'tanggal', 'status'];

    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
}
