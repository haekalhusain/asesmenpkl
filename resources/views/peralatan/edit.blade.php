@extends('layouts.app')
@section('title', 'Edit Peralatan')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Peralatan</h1>
    <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('peralatan.update', $peralatan) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        {{-- Kode Peralatan --}}
        <div class="form-group">
            <label class="form-label" for="kode_peralatan">Kode Peralatan</label>
            <input type="text"
                   id="kode_peralatan"
                   name="kode_peralatan"
                   class="form-control {{ $errors->has('kode_peralatan') ? 'is-invalid' : '' }}"
                   value="{{ old('kode_peralatan', $peralatan->kode_peralatan) }}">
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
                   value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}">
            @error('nama_peralatan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Kategori --}}
        <div class="form-group">
            <label class="form-label" for="kategori">Kategori</label>
            <input type="text"
                   id="kategori"
                   name="kategori"
                   class="form-control"
                   value="{{ old('kategori', $peralatan->kategori) }}">
        </div>

        {{-- Stok --}}
        <div class="form-group">
            <label class="form-label" for="stok">Stok</label>
            <input type="number"
                   id="stok"
                   name="stok"
                   class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
                   value="{{ old('stok', $peralatan->stok) }}"
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
                <option value="baik"         {{ old('kondisi', $peralatan->kondisi) === 'baik'         ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ old('kondisi', $peralatan->kondisi) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat"  {{ old('kondisi', $peralatan->kondisi) === 'rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
            </select>
            @error('kondisi')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi"
                      name="deskripsi"
                      class="form-control"
                      rows="3">{{ old('deskripsi', $peralatan->deskripsi) }}</textarea>
        </div>

        {{-- Foto --}}
        <div class="form-group">
            <label class="form-label">Foto Peralatan</label>
            @if($peralatan->foto)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/'.$peralatan->foto) }}"
                         alt="{{ $peralatan->nama_peralatan }}"
                         id="preview"
                         class="preview-img"
                         style="width:80px; height:80px;">
                </div>
            @else
                <img id="preview" src="#" alt="Preview" class="preview-img"
                     style="display:none; margin-bottom:10px; width:80px; height:80px;">
            @endif
            <div class="upload-area" onclick="document.getElementById('foto').click()">
                <input type="file"
                       id="foto"
                       name="foto"
                       accept="image/*"
                       onchange="previewImage(this)">
                <p>Klik untuk ganti foto &mdash; kosongkan jika tidak diganti</p>
            </div>
            @error('foto')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection
