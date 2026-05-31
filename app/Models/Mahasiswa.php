<?php

namespace App\Models;
use App\Models\AlamatMahasiswa;
use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    public function alamat() {
    return $this->hasOne(AlamatMahasiswa::class, 'mahasiswa_id');
}

    public function sekolah() {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

        public function user()
    {
        return $this->hasOne(User::class);
    }

}


