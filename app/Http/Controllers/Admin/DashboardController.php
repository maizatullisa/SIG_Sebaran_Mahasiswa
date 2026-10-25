<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\AlamatMahasiswa;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();

        $totalAdmin = User::where('role', 'admin')->count();

        $totalKaprodi = User::where('role', 'kaprodi')->count();

 

  $provinsiTerbanyak = DB::table('alamat_mahasiswas')
    ->join(
        'indonesia_provinces',
        'alamat_mahasiswas.provinsi',
        '=',
        'indonesia_provinces.code'
    )
    ->selectRaw(
        'indonesia_provinces.name as provinsi,
         COUNT(*) as total'
    )
    ->groupBy('indonesia_provinces.name')
    ->orderByDesc('total')
    ->take(3)
    ->get();
        
       foreach ($provinsiTerbanyak as $provinsi) {

       $provinsi->persen = $totalMahasiswa > 0
        ? round(($provinsi->total / $totalMahasiswa) * 100, 1)
        : 0;
}

        return view('admin.dashboard.index', compact(
            'totalMahasiswa',
            'totalKaprodi',
            'totalAdmin',
            'provinsiTerbanyak'
        ));
    }
}