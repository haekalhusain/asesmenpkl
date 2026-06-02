<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        // ── Validasi (dipindah dari StorePenggunaRequest) ──────────────────
        $data = $request->validate([
            'no_pengguna' => 'required|string|max:20|unique:users,no_pengguna',
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|max:150|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:500',
        ], [
            'no_pengguna.required' => 'No pengguna wajib diisi.',
            'no_pengguna.unique'   => 'No pengguna sudah terdaftar.',
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah terdaftar.',
            'password.required'    => 'Password wajib diisi.',
            'password.min'         => 'Password minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
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

    public function update(Request $request, User $pengguna)
    {
        // ── Validasi (dipindah dari UpdatePenggunaRequest) ─────────────────
        $data = $request->validate([
            'no_pengguna' => [
                'required', 'string', 'max:20',
                Rule::unique('users', 'no_pengguna')->ignore($pengguna->id),
            ],
            'name'     => 'required|string|max:100',
            'email'    => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($pengguna->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:500',
        ], [
            'no_pengguna.unique' => 'No pengguna sudah digunakan.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $update = collect($data)->except('password')->toArray();

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $pengguna->update($update);

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
