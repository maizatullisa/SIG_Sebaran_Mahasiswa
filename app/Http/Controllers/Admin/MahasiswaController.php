<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\AlamatMahasiswa;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Exports\MahasiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class MahasiswaController extends Controller
{
    public function index(Request $request)
{
    $query = Mahasiswa::query()
    ->leftJoin('alamat_mahasiswas', 'mahasiswa.user_id', '=', 'alamat_mahasiswas.user_id')
    ->leftJoin('sekolahs', 'mahasiswa.user_id', '=', 'sekolahs.user_id')
    ->leftJoin('users', 'mahasiswa.user_id', '=', 'users.id')
    ->select(
        'mahasiswa.*',

        'users.email',
        'users.no_telp',
        'users.foto',

        // alamat
        'alamat_mahasiswas.provinsi',
        'alamat_mahasiswas.kabupaten',
        'alamat_mahasiswas.kecamatan',
        'alamat_mahasiswas.desa',
        'alamat_mahasiswas.detail_alamat',

        // sekolah
        'sekolahs.nama_sekolah',
        'sekolahs.kecamatan as kecamatan_sekolah'
    );

    // Search nama atau NIM
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        });
    }

    // Filter angkatan
    if ($request->filled('angkatan')) {
        $query->where('nim', 'like', $request->angkatan . '%');
    }

    // Filter prodi
    if ($request->filled('prodi')) {
        $query->where('prodi', $request->prodi);
    }

    $mahasiswa = $query
        ->orderBy('nama')
        ->paginate(10)
        ->withQueryString();

    $mahasiswa->getCollection()->transform(function ($m) {

        $m->provinsi = Province::where('code', $m->provinsi)->value('name') ?? $m->provinsi;

        $m->kabupaten = City::where('code', $m->kabupaten)->value('name') ?? $m->kabupaten;

        $m->kecamatan = District::where('code', $m->kecamatan)->value('name') ?? $m->kecamatan;

        $m->desa = Village::where('code', $m->desa)->value('name') ?? $m->desa;

        return $m;
    });

    $prodi = Mahasiswa::select('prodi')
        ->distinct()
        ->orderBy('prodi')
        ->pluck('prodi');

    return view('admin.mahasiswa.index', compact(
        'mahasiswa',
        'prodi'
    ));
}

public function create()
{
    return view('admin.mahasiswa.create');
}

public function store(Request $request)
{
    $request->validate([
        'nim' => 'required|string|max:20|unique:mahasiswa,nim',
        'nama' => 'required|string|max:255',
        'prodi' => 'required|string|max:100',
    ]);

    Mahasiswa::create([
        'nim' => $request->nim,
        'nama' => $request->nama,
        'prodi' => $request->prodi,
    ]);

    return redirect()
        ->route('admin.mahasiswa.index')
        ->with('success', 'Data mahasiswa berhasil ditambahkan.');
}

public function destroy($id)
{
    $mahasiswa = Mahasiswa::findOrFail($id);

    // hapus alamat jika ada
    AlamatMahasiswa::where('user_id', $mahasiswa->user_id)->delete();

    // hapus sekolah jika ada
    Sekolah::where('user_id', $mahasiswa->user_id)->delete();

    // hapus user jika ada
    if ($mahasiswa->user_id) {
        User::where('id', $mahasiswa->user_id)->delete();
    }

    // hapus mahasiswa
    $mahasiswa->delete();

    return redirect()
        ->back()
        ->with('success', 'Data mahasiswa berhasil dihapus');
}

public function export()
{
    if (auth()->user()->role !== 'kaprodi') {
        abort(403);
    }

    return Excel::download(
        new MahasiswaExport(),
        'data_mahasiswa.xlsx'
    );
}

public function edit($id)
{
    $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

    return view('admin.mahasiswa.edit', compact('mahasiswa'));
}

public function update(Request $request, $id)
{
    $mahasiswa = Mahasiswa::findOrFail($id);

    $request->validate([
        'nim'      => 'required|max:20|unique:mahasiswa,nim,' . $mahasiswa->id,
        'nama'     => 'required|max:255',
        'prodi'    => 'required|max:100',
        'email'    => 'nullable|email',
        'no_telp'  => 'nullable|max:20',
    ]);

    // update tabel mahasiswa
    $mahasiswa->update([
        'nim'   => $request->nim,
        'nama'  => $request->nama,
        'prodi' => $request->prodi,
    ]);

    // update tabel users
    if ($mahasiswa->user_id) {

        $user = User::find($mahasiswa->user_id);

        if ($user) {
            $user->update([
                'email'   => $request->email,
                'no_telp' => $request->no_telp,
            ]);
        }
    }

    return back()->with('success', 'Data berhasil diperbarui');
}

}