<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provinces = Province::all();

        // Ambil data sekolah khusus milik user yang sedang login (Single-row per student)
        $sekolah = Sekolah::where('user_id', Auth::id())->first();

        $mahasiswa = DB::table('mahasiswa')
        ->where('user_id', Auth::id())
        ->first();

        return view('mahasiswa.sekolah', compact('user', 'provinces', 'sekolah', 'mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
        ]);

        // Mengambil Teks Nama Asli Wilayah berdasarkan Code Laravolt agar Nominatim Map bisa melacak teks string-nya
        $provName = Province::where('code', $request->provinsi)->first()?->name ?? $request->provinsi;
        $kabName  = City::where('code', $request->kabupaten)->first()?->name ?? $request->kabupaten;
        // Jika request kecamatan berupa code, cari namanya. Jika sudah string name, gunakan langsung.
        $kecName  = District::where('code', $request->kecamatan)->first()?->name ?? $request->kecamatan;

        // Menggunakan updateOrCreate agar per user hanya memiliki satu data sekolah asal
        Sekolah::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nama_sekolah' => $request->nama_sekolah,
                'provinsi'     => $provName,
                'kabupaten'    => $kabName,
                'kecamatan'    => $kecName,
            ]
        );

        return redirect()->back()->with('success', 'Data sekolah asal berhasil disimpan');
    }

    public function destroy($id)
    {
        // Pastikan hanya bisa menghapus data miliknya sendiri
        $sekolah = Sekolah::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $sekolah->delete();

        return redirect()->back()->with('success', 'Data sekolah berhasil direset');
    }
}