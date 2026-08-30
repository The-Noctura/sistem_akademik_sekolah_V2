<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';
    
    protected $fillable = ['siswa_id', 'mengajar_id', 'jenis', 'nilai', 'tanggal_input', 'diinput_oleh'];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
    
    public function diinputOleh()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }
}
