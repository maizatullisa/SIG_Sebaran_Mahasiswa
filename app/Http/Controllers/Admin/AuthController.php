<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (
            Auth::attempt($credentials) &&
            in_array(auth()->user()->role, ['admin', 'kaprodi'])
        ) {
            $request->session()->regenerate();

            return redirect('/admin/dashboard');
        }

        Auth::logout();

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
}