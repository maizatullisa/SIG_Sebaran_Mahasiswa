<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MinatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $user->id)
            ->first();

        return view('mahasiswa.minat', [
            'mahasiswa' => $mahasiswa,
            'minatBakat' => $mahasiswa
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'minat'   => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
        ]);

        DB::table('mahasiswa')
            ->where('user_id', auth()->id())
            ->update([
                'minat'      => $request->minat,
                'jurusan'    => $request->jurusan,
                'updated_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'Minat dan jurusan berhasil disimpan.');
    }

    public function reset()
    {
        DB::table('mahasiswa')
            ->where('user_id', auth()->id())
            ->update([
                'minat'      => null,
                'jurusan'    => null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'Data berhasil direset.');
    }
}