<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) =>
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('no_pengguna', 'like', "%$q%")
            );
        }

        $penggunas = $query->latest()->paginate(10);
        return view('pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(StorePenggunaRequest $request)
    {
        User::create([
            ...$request->validated(),
            'password' => Hash::make($request->validated()['password']),
            'role'     => 'user',
        ]);

        return redirect()->route('pengguna.index')
                         ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $pengguna)
    {
        $pengguna->load(['peminjamans.peralatan']);
        return view('pengguna.show', compact('pengguna'));
    }

    public function edit(User $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(UpdatePenggunaRequest $request, User $pengguna)
    {
        $data = $request->safe()->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated()['password']);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        $pengguna->delete();

        return redirect()->route('pengguna.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}
