<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('nama_barang', 'like', "%$q%")
                   ->orWhere('kategori_barang', 'like', "%$q%");
            });
        }

        $barangs = $query->latest()->paginate(10);
        return view($this->viewPath('index'), compact('barangs'));
    }

    public function create()
    {
        return view($this->viewPath('create'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang'     => 'required|string|max:100',
            'kategori_barang' => 'required|string|max:100',
            'stok'            => 'required|integer|min:0',
            'kondisi_barang'  => 'required|string|max:50',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'kategori_barang.required' => 'Kategori wajib diisi.',
            'stok.required'            => 'Stok wajib diisi.',
            'stok.min'                 => 'Stok tidak boleh negatif.',
            'kondisi_barang.required'  => 'Kondisi barang wajib diisi.',
            'gambar.image'             => 'File harus berupa gambar.',
            'gambar.mimes'             => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'gambar.max'               => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets'), $filename);
            $data['gambar'] = $filename;
        }

        Barang::create($data);

        return redirect()->route($this->routePrefix() . 'barang.index')
                         ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view($this->viewPath('show'), compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view($this->viewPath('edit'), compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'nama_barang'     => 'required|string|max:100',
            'kategori_barang' => 'required|string|max:100',
            'stok'            => 'required|integer|min:0',
            'kondisi_barang'  => 'required|string|max:50',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_barang.required'     => 'Nama barang wajib diisi.',
            'kategori_barang.required' => 'Kategori wajib diisi.',
            'stok.required'            => 'Stok wajib diisi.',
            'stok.min'                 => 'Stok tidak boleh negatif.',
            'kondisi_barang.required'  => 'Kondisi barang wajib diisi.',
            'gambar.image'             => 'File harus berupa gambar.',
            'gambar.mimes'             => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'gambar.max'               => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets'), $filename);
            $data['gambar'] = $filename;

            if ($barang->gambar && file_exists(public_path('assets/' . $barang->gambar))) {
                @unlink(public_path('assets/' . $barang->gambar));
            }
        }

        $barang->update($data);

        return redirect()->route($this->routePrefix() . 'barang.index')
                         ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar && file_exists(public_path('assets/' . $barang->gambar))) {
            @unlink(public_path('assets/' . $barang->gambar));
        }

        $barang->delete();

        return redirect()->route($this->routePrefix() . 'barang.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Deteksi prefix route berdasarkan role user yang login.
     * Admin → '' | User → 'user.'
     */
    private function routePrefix(): string
    {
        return auth()->user()->role === 'admin' ? '' : 'user.';
    }

    /**
     * Deteksi path view berdasarkan role user yang login.
     * Admin → 'barang.{page}' | User → 'user.barang.{page}'
     */
    private function viewPath(string $page): string
    {
        $prefix = auth()->user()->role === 'admin' ? 'barang' : 'user.barang';
        return "{$prefix}.{$page}";
    }
}
