<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    
    protected $fillable = ['mengajar_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
}
