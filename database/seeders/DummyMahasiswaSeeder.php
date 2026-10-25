<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            'Teknik Informatika' => '11',
            'Sistem Informasi'   => '22',
            'Teknik Industri'    => '33',
            'Teknik Elektro'     => '44',
            'Teknik Kimia'       => '55',
        ];

        $trendData = [

            'Teknik Informatika' => [
                22 => 10,
                23 => 20,
                24 => 35,
                26 => 60
            ],

            'Sistem Informasi' => [
                22 => 30,
                23 => 28,
                24 => 25,
                26 => 20
            ],

            'Teknik Industri' => [
                22 => 15,
                23 => 15,
                24 => 15,
                26 => 15
            ],

            'Teknik Elektro' => [
                22 => 8,
                23 => 15,
                24 => 25,
                26 => 40
            ],

            'Teknik Kimia' => [
                22 => 25,
                23 => 18,
                24 => 12,
                26 => 5
            ]
        ];

        foreach ($trendData as $prodi => $angkatanData) {

            $kodeProdi = $prodis[$prodi];

            foreach ($angkatanData as $tahun => $jumlahMahasiswa) {

                for ($i = 1; $i <= $jumlahMahasiswa; $i++) {

                    $nim = $tahun .
                        $kodeProdi .
                        str_pad($i, 4, '0', STR_PAD_LEFT);

                    DB::table('mahasiswa')->insert([
                        'nim' => $nim,
                        'nama' => "{$prodi} {$tahun}-{$i}",
                        'prodi' => $prodi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}