<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = [
        'nama_sekolah',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'user_id', 
    ];

       public function user()
    {
        return $this->belongsTo(User::class);
    }
}
