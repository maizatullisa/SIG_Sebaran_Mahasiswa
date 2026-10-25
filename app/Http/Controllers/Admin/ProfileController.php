<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
   public function index()
{
    $user = auth()->user();

    return view('admin.profile', compact('user'));
}

 public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    /**
     * Update email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();

        $user->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Email berhasil diperbarui.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ])->withInput();
        }

        // update password baru
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

public function updateAvatar(Request $request)
{
    $request->validate([
        'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user = auth()->user();

    // hapus foto lama
    if ($user->foto &&
        Storage::disk('public')->exists($user->foto)) {

        Storage::disk('public')->delete($user->foto);
    }

    $path = $request->file('foto')
                    ->store('foto-profil', 'public');

    $user->update([
        'foto' => $path,
    ]);

    return back()->with(
        'success',
        'Foto profil berhasil diperbarui.'
    );
}

public function removeAvatar()
{
    $user = auth()->user();

    if ($user->foto &&
        Storage::disk('public')->exists($user->foto)) {

        Storage::disk('public')->delete($user->foto);
    }

    $user->update([
        'foto' => null,
    ]);

    return back()->with(
        'success',
        'Foto profil berhasil dihapus.'
    );
}

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Berhasil logout.');
    }

    }
