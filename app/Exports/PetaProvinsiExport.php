<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PetaProvinsiExport implements FromCollection, WithHeadings
{
    protected $provinsi;

    public function __construct($provinsi)
    {
        $this->provinsi = $provinsi;
    }

    public function collection()
    {
        return DB::table('alamat_mahasiswas')
            ->join(
                'mahasiswa',
                'alamat_mahasiswas.user_id',
                '=',
                'mahasiswa.user_id'
            )
            ->where('alamat_mahasiswas.provinsi', $this->provinsi)

            ->select(
                'alamat_mahasiswas.provinsi',
                'alamat_mahasiswas.kabupaten',
                'alamat_mahasiswas.kecamatan',
                'mahasiswa.prodi',
                DB::raw('COUNT(*) as jumlah_mahasiswa')
            )

            ->groupBy(
                'alamat_mahasiswas.provinsi',
                'alamat_mahasiswas.kabupaten',
                'alamat_mahasiswas.kecamatan',
                'mahasiswa.prodi'
            )

            ->orderBy('alamat_mahasiswas.kabupaten')
            ->orderBy('alamat_mahasiswas.kecamatan')
            ->orderBy('mahasiswa.prodi')

            ->get();
    }

    public function headings(): array
    {
        return [
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Prodi',
            'Jumlah Mahasiswa'
        ];
    }
}