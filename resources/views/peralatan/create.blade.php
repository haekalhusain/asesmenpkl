@extends('layouts.app')
@section('title', 'Tambah Peralatan')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Peralatan</h1>
    <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('peralatan.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Kode Peralatan --}}
        <div class="form-group">
            <label class="form-label" for="kode_peralatan">Kode Peralatan</label>
            <input type="text"
                   id="kode_peralatan"
                   name="kode_peralatan"
                   class="form-control {{ $errors->has('kode_peralatan') ? 'is-invalid' : '' }}"
                   value="{{ old('kode_peralatan') }}"
                   placeholder="Contoh: KMP-001">
            @error('kode_peralatan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nama Peralatan --}}
        <div class="form-group">
            <label class="form-label" for="nama_peralatan">Nama Peralatan</label>
            <input type="text"
                   id="nama_peralatan"
                   name="nama_peralatan"
                   class="form-control {{ $errors->has('nama_peralatan') ? 'is-invalid' : '' }}"
                   value="{{ old('nama_peralatan') }}"
                   placeholder="Nama peralatan">
            @error('nama_peralatan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Kategori --}}
        <div class="form-group">
            <label class="form-label" for="kategori">
                Kategori
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">(opsional)</span>
            </label>
            <input type="text"
                   id="kategori"
                   name="kategori"
                   class="form-control"
                   value="{{ old('kategori') }}"
                   placeholder="Contoh: Komputer, Jaringan">
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

        {{-- Kondisi --}}
        <div class="form-group">
            <label class="form-label" for="kondisi">Kondisi</label>
            <select id="kondisi"
                    name="kondisi"
                    class="form-control {{ $errors->has('kondisi') ? 'is-invalid' : '' }}">
                <option value="baik"         {{ old('kondisi') === 'baik'         ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ old('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat"  {{ old('kondisi') === 'rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            @error('kondisi')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label" for="deskripsi">
                Deskripsi
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">(opsional)</span>
            </label>
            <textarea id="deskripsi"
                      name="deskripsi"
                      class="form-control"
                      rows="3"
                      placeholder="Deskripsi peralatan">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Foto --}}
        <div class="form-group">
            <label class="form-label">Foto Peralatan</label>
            <div class="upload-area" onclick="document.getElementById('foto').click()">
                <input type="file"
                       id="foto"
                       name="foto"
                       accept="image/*"
                       onchange="previewImage(this)">
                <div id="upload-placeholder">
                    <p style="font-size:1.5rem; color:var(--text-muted);">&#9650;</p>
                    <p>Klik untuk upload foto</p>
                    <p>JPG, PNG, WEBP &mdash; Maks 2MB</p>
                </div>
                <img id="preview"
                     src="#"
                     alt="Preview foto"
                     class="preview-img"
                     style="display:none; margin:0 auto;">
            </div>
            @error('foto')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('upload-placeholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
