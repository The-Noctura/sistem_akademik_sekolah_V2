<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;
    protected $table = 'mapel';
    
    protected $fillable = ['nama_mapel', 'kode_mapel'];
    
    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }
}
