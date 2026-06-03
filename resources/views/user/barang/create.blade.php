@extends('layouts.user')
@section('title', 'Tambah Barang')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Barang</h1>
    <a href="{{ route('user.barang.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('user.barang.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Nama Barang --}}
        <div class="form-group">
            <label class="form-label" for="nama_barang">Nama Barang</label>
            <input type="text"
                   id="nama_barang"
                   name="nama_barang"
                   class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                   value="{{ old('nama_barang') }}"
                   placeholder="Nama barang">
            @error('nama_barang')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Kategori Barang --}}
        <div class="form-group">
            <label class="form-label" for="kategori_barang">Kategori Barang</label>
            <input type="text"
                   id="kategori_barang"
                   name="kategori_barang"
                   class="form-control {{ $errors->has('kategori_barang') ? 'is-invalid' : '' }}"
                   value="{{ old('kategori_barang') }}"
                   placeholder="Contoh: Laptop, Aksesoris">
            @error('kategori_barang')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Stok --}}
        <div class="form-group">
            <label class="form-label" for="stok">Stok</label>
            <input type="number"
                   id="stok"
                   name="stok"
                   class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
                   value="{{ old('stok', 1) }}"
                   min="0">
            @error('stok')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Kondisi Barang --}}
        <div class="form-group">
            <label class="form-label" for="kondisi_barang">Kondisi Barang</label>
            <select id="kondisi_barang"
                    name="kondisi_barang"
                    class="form-control {{ $errors->has('kondisi_barang') ? 'is-invalid' : '' }}">
                <option value="Baik" {{ old('kondisi_barang') === 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ old('kondisi_barang') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ old('kondisi_barang') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            @error('kondisi_barang')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Gambar Barang --}}
        <div class="form-group">
            <label class="form-label" for="gambar">Gambar Barang</label>
            <input type="file"
                   id="gambar"
                   name="gambar"
                   accept="image/png,image/jpeg,image/webp"
                   class="form-control {{ $errors->has('gambar') ? 'is-invalid' : '' }}">
            @error('gambar')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('user.barang.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection
