<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaprodiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'kaprodi');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

            });
        }

        $kaprodi = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.kaprodi.index',
            compact('kaprodi')
        );
    }

        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kaprodi',
        ]);

        return back()->with('success', 'Kaprodi berhasil ditambahkan');
    }

        public function update(Request $request, $id)
    {
        $kaprodi = User::where('role', 'kaprodi')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $kaprodi->id,
        ]);

        $kaprodi->name = $request->name;
        $kaprodi->email = $request->email;

        if ($request->filled('password')) {
            $kaprodi->password = Hash::make($request->password);
        }

        $kaprodi->save();

        return back()->with('success', 'Data kaprodi berhasil diperbarui');
    }

        public function destroy($id)
    {
        $kaprodi = User::where('role', 'kaprodi')->findOrFail($id);

        $kaprodi->delete();

        return back()->with('success', 'Kaprodi berhasil dihapus');
    }
    
    }