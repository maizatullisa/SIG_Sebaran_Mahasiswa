<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromCollection, WithHeadings
{

public function collection()
{
    return DB::table('mahasiswa')
        ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id')
        ->leftJoin('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
        ->leftJoin('sekolahs', 'mahasiswa.user_id', '=', 'sekolahs.user_id')
        ->select(
            'mahasiswa.nim',
            'mahasiswa.nama',
            'mahasiswa.prodi',
            'mahasiswa.jurusan',
            'mahasiswa.minat',

            'users.email',
            'users.no_telp',

            'sekolahs.nama_sekolah',

            'alamat_mahasiswas.provinsi',
            'alamat_mahasiswas.kabupaten',
            'alamat_mahasiswas.kecamatan',
            'alamat_mahasiswas.desa',
            'alamat_mahasiswas.detail_alamat',
            'alamat_mahasiswas.latitude',
            'alamat_mahasiswas.longitude'
        )
        ->orderBy('mahasiswa.nama')
        ->get();
}

public function headings(): array
{
    return [
        'NIM',
        'Nama',
        'Prodi',
        'Jurusan',
        'Minat',

        'Email',
        'No Telp',

        'Nama Sekolah',

        'Provinsi',
        'Kabupaten',
        'Kecamatan',
        'Desa',
        'Alamat Lengkap',

        'Latitude',
        'Longitude'
    ];
}

}