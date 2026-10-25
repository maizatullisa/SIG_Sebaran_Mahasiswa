<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use App\Models\AlamatMahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AlamatMahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        $provinces = Province::all();

        $alamat = AlamatMahasiswa::where('user_id', Auth::id())->first();

         $mahasiswa = DB::table('mahasiswa')
        ->where('user_id', Auth::id())
        ->first();


        return view(
            'mahasiswa.alamat',
            compact(
                'user',
                'mahasiswa',
                'provinces',
                'alamat',
                'mahasiswa' // <── Variabel ini dikirim ke view
            )
        );
    }

    public function store(Request $request)
{
    // Validasi: Pastikan semua kolom wajib diisi
    $request->validate([
        'provinsi'      => 'required',
        'kabupaten'     => 'required',
        'kecamatan'     => 'required',
        'desa'          => 'required',
        'detail_alamat' => 'required',
        'latitude'      => 'required',
        'longitude'     => 'required',
    ]);

    AlamatMahasiswa::updateOrCreate(
        ['user_id' => Auth::id()],
        [
            'provinsi'      => $request->provinsi,
            'kabupaten'     => $request->kabupaten,
            'kecamatan'     => $request->kecamatan,
            'desa'          => $request->desa,
            'detail_alamat' => $request->detail_alamat,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
        ]
    );

    return back()->with('success', 'Alamat berhasil disimpan');
}

    // Fungsi opsional untuk tombol hapus/reset (jika Anda ingin memakainya)
    public function destroy()
    {
        AlamatMahasiswa::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Alamat berhasil direset.');
    }
}