<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    
    protected $fillable = ['user_id', 'nip', 'nama', 'no_hp'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }
    
    public function waliKelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }
}
