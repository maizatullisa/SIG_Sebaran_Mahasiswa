<?php

namespace App\Models;
use App\Models\AlamatMahasiswa;
use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'jurusan',
        'minat',
        'user_id',
        'sekolah_id',
    ];


    public function alamat() {
    return $this->hasOne(AlamatMahasiswa::class, 'mahasiswa_id', 'id');
}

    public function sekolah() {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

        public function user()
    {
        return $this->hasOne(User::class);
    }

    // Tambahkan relasi ke tabel city (Laravolt)
    public function kabupaten() {
        // Asumsi tabel alamat punya kolom 'kabupaten_id'
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'kabupaten_id', 'id');
    }


    }
    
