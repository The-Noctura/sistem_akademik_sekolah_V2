<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;
    protected $table = 'jadwal';
    
    protected $fillable = ['mengajar_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];
    
    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class);
    }
}
