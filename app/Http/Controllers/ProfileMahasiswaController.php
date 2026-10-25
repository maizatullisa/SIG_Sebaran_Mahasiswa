<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileMahasiswaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $user->id)
            ->first();

        return view('mahasiswa.profile', compact('user', 'mahasiswa'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($request->input('action_type') === 'update_password') {
            
            $request->validate([
                'old_password' => 'required',
                'password'     => 'required|min:8', 
            ], [
                'old_password.required' => 'Password lama wajib diisi.',
                'password.required'     => 'Password baru wajib diisi.',
                'password.min'          => 'Password baru minimal harus 8 karakter.',
            ]);

            
            if (!Hash::check($request->old_password, $user->password)) {
                
                return redirect()->back()->withErrors(['old_password' => 'Gagal! Password lama yang Anda masukkan salah.']);
            }

            
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($request->password)
                ]);

            return redirect()->back()->with('success', 'Password akun berhasil diperbarui!');
        }

    
        if ($request->input('action_type') === 'update_profile') {
            
            $request->validate([
                'email'   => 'required|email|unique:users,email,' . $user->id,
                'no_telp' => 'nullable|regex:/^[0-9]+$/|min:10|max:15',
                'foto'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.unique'   => 'Email sudah digunakan oleh akun lain.',
                'foto.max'       => 'Ukuran foto maksimal adalah 2MB.'
            ]);

            $data = [
                'email'   => $request->email,
                'no_telp' => $request->no_telp,
            ];

            
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFoto = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->store('foto-profil', 'public');
                $data['foto'] = basename($path);
            }

            
            DB::table('users')
                ->where('id', $user->id)
                ->update($data);

            return redirect()->back()->with('success', 'Profile berhasil diperbarui');
        }
        

        return redirect()->back();
    }
}