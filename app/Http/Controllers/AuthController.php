<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('mahasiswa.register');
    }

    public function register(Request $request)
{
    $mahasiswa = DB::table('mahasiswa')
        ->where('nim', $request->nim)
        ->where('nama', $request->nama)
        ->first();

    if (!$mahasiswa) {
        return back()->with('error', 'NIM atau Nama tidak ditemukan!');
    }

    $user = User::create([
        'email' => $request->email,
        'role' => 'mahasiswa',
        'password' => Hash::make($request->password),
    ]);

    DB::table('mahasiswa')
        ->where('id', $mahasiswa->id)
        ->update([
            'user_id' => $user->id
        ]);

    return redirect('/login')->with('success', 'Daftar berhasil');
}

    public function showLogin()
{
    return view('mahasiswa.login');
}

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
}

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');
}
}
