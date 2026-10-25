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

        $provName = Province::where('code', $request->provinsi)->first()?->name ?? $request->provinsi;
        $kabName  = City::where('code', $request->kabupaten)->first()?->name ?? $request->kabupaten;
        $kecName  = District::where('code', $request->kecamatan)->first()?->name ?? $request->kecamatan;

        
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
        $sekolah = Sekolah::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $sekolah->delete();

        return redirect()->back()->with('success', 'Data sekolah berhasil direset');
    }
}