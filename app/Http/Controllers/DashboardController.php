<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ambil data mahasiswa berdasarkan user_id
        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $user->id)
            ->first();

             $alamat = DB::table('alamat_mahasiswas')
            ->where('user_id', $user->id)
            ->first();

            $sekolah = DB::table('sekolahs')
            ->where('user_id', $user->id)
            ->first();

               // minat langsung dari tabel mahasiswa
            $minat = $mahasiswa->minat ?? null;

        return view('mahasiswa.dashboard', compact('mahasiswa', 'user', 'alamat', 'sekolah', 'minat'));
    }
}