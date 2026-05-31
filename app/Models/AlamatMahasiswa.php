<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatMahasiswa extends Model
{
    /**
     * Properti fillable untuk mendefinisikan kolom mana saja
     * yang boleh diisi menggunakan mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'detail_alamat',
        'latitude',
        'longitude',
    ];

    /**
     * Hubungkan kembali relasi ke model User jika diperlukan
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}