<?php

namespace App\Http\Controllers;

use App\Models\AlamatMahasiswa;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
  public function index()
{
    $kecamatan = AlamatMahasiswa::select(
            'kecamatan',
            DB::raw('AVG(latitude) as latitude'),
            DB::raw('AVG(longitude) as longitude')
        )
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->where('latitude', '!=', '') // Menghindari string kosong
        ->where('longitude', '!=', '') // Menghindari string kosong
        ->whereNotNull('kecamatan')
        ->groupBy('kecamatan')
        ->get();

    return view('mahasiswa.landingpage', compact('kecamatan'));
}
}