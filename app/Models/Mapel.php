<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    
    protected $fillable = ['nama_mapel', 'kode_mapel'];
    
    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }
}
