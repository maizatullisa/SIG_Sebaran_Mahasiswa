<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlamatMahasiswa;
use Illuminate\Support\Facades\DB;
use App\Exports\PetaProvinsiExport;
use Maatwebsite\Excel\Facades\Excel;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class PetaMahasiswaController extends Controller
{
   public function index()
{
    $mahasiswaProvinsi = DB::table('alamat_mahasiswas')
        ->join(
            'indonesia_provinces',
            'alamat_mahasiswas.provinsi',
            '=',
            'indonesia_provinces.code'
        )
        ->select(
            'indonesia_provinces.name',
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('indonesia_provinces.name')
        ->get();

    return view('admin.peta.index', [
        'mahasiswaProvinsi' => $mahasiswaProvinsi
    ]);
}

    public function provinsi()
{
    return DB::table('alamat_mahasiswas')
        ->join(
            'indonesia_provinces',
            'alamat_mahasiswas.provinsi',
            '=',
            'indonesia_provinces.code'
        )
        ->select(
            'indonesia_provinces.code',
            'indonesia_provinces.name',
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy(
            'indonesia_provinces.code',
            'indonesia_provinces.name'
        )
        ->get();
}

public function kabupaten($kodeProvinsi)
{
    // Mengambil nama kabupaten murni beserta hitungan agregat jumlah mahasiswa
    $data = DB::table('alamat_mahasiswas')
        ->where('provinsi', $kodeProvinsi)
        ->whereNotNull('kabupaten')
        ->select(
            'kabupaten as name',
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('kabupaten')
        ->get();

    return response()->json(array_values($data->toArray()));
}

// public function mahasiswaByProvinsi($kodeProvinsi)
// {
//     return DB::table('alamat_mahasiswas')
//         ->where('provinsi', $kodeProvinsi)
//         ->whereNotNull('latitude')
//         ->whereNotNull('longitude')
//         ->select(
//             'id',
//             'latitude',
//             'longitude',
//             'kabupaten',
//             'kecamatan'
//         )
//         ->get();
// }

public function mahasiswaByProvinsi($kodeProvinsi)
{
    $data = DB::table('alamat_mahasiswas')
        ->leftJoin('mahasiswa', 'alamat_mahasiswas.user_id', '=', 'mahasiswa.user_id')
        ->leftJoin('sekolahs', 'alamat_mahasiswas.user_id', '=', 'sekolahs.user_id')
        
        ->where('alamat_mahasiswas.provinsi', $kodeProvinsi)
        ->whereNotNull('alamat_mahasiswas.latitude')
        ->whereNotNull('alamat_mahasiswas.longitude')
        
        ->select(
            
            'alamat_mahasiswas.id',
            'alamat_mahasiswas.latitude',
            'alamat_mahasiswas.longitude',
            'alamat_mahasiswas.kabupaten',
            'alamat_mahasiswas.kecamatan',
            'alamat_mahasiswas.desa',
            'alamat_mahasiswas.detail_alamat',
            
            
            'mahasiswa.nim',
            'mahasiswa.nama',
            'mahasiswa.prodi',
            'mahasiswa.minat',
            'mahasiswa.jurusan',
            
            
            'sekolahs.nama_sekolah',
            'sekolahs.kecamatan as kecamatan_sekolah'
        )
        ->get();

    return response()->json(array_values($data->toArray()));
}

// public function exportProvinsi($kodeProvinsi)
// {
//     if (auth()->user()->role !== 'kaprodi') {
//         abort(403);
//     }

//     return Excel::download(
//         new PetaProvinsiExport($kodeProvinsi),
//         'sebaran_mahasiswa_'.$kodeProvinsi.'.xlsx'
//     );
// }

}