<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            'Teknik Informatika' => '11',
            'Sistem Informasi' => '12',
            'Teknik Kimia' => '13',
            'Teknik Industri' => '14',
            'Teknik Elektro' => '15',
        ];

        $angkatan = ['21', '22', '23'];

        $namaDepan = [
            'Ahmad','Aldo','Andi','Asep','Bagas','Bayu','Bima','Budi',
            'Cahyo','Candra','Daffa','Damar','Deni','Dimas','Edo',
            'Eka','Fadli','Fajar','Farhan','Fauzan','Galih','Gilang',
            'Hafidz','Hanif','Hendra','Ilham','Iqbal','Jaka','Joko',
            'Kevin','Lukman','Mahendra','Miko','Nanda','Naufal',
            'Putra','Rafi','Rama','Rendi','Reza','Rifki','Rio',
            'Rizki','Taufik','Wahyu','Yoga','Yusuf','Zidan',

            'Aisyah','Amelia','Anisa','Aulia','Bella','Citra',
            'Dewi','Dinda','Elsa','Fitri','Gina','Indah',
            'Intan','Kartika','Laila','Maya','Melinda',
            'Nabila','Nadia','Nisa','Novita','Putri',
            'Rani','Rara','Salsa','Sinta','Tika',
            'Wulan','Yuni','Zahra'
        ];

        $namaBelakang = [
            'Abdullah','Aditya','Anugrah','Firmansyah',
            'Gunawan','Halim','Hartanto','Hidayat',
            'Iskandar','Kurniawan','Lesmana',
            'Mahardika','Maulana','Nugraha',
            'Nugroho','Permana','Pradana',
            'Prasetyo','Pratama','Putra',
            'Ramadhan','Saputra','Setiawan',
            'Susanto','Syahputra','Utama',
            'Wijaya','Wicaksono','Yulianto',
            'Zulfikar','Anggraini','Azzahra',
            'Fitriani','Handayani','Kartikasari',
            'Lestari','Maharani','Melati',
            'Nuraini','Oktaviani','Permatasari',
            'Puspitasari','Rahmawati',
            'Safitri','Sari','Utami'
        ];

        foreach ($prodis as $prodi => $kodeProdi) {

            foreach ($angkatan as $th) {

                for ($i = 1; $i <= 7; $i++) {

                    $nim = $th . $kodeProdi . str_pad($i, 3, '0', STR_PAD_LEFT);

                    $nama =
                        $namaDepan[array_rand($namaDepan)] . ' ' .
                        $namaBelakang[array_rand($namaBelakang)];

                    DB::table('mahasiswa')->insert([
                        'nim' => $nim,
                        'nama' => $nama,
                        'prodi' => $prodi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}