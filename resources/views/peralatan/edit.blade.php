@extends('layouts.app')
@section('title', 'Edit Peralatan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Peralatan</h1>
    <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('peralatan.update', $peralatan) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">Kode Peralatan</label>
            <input type="text" name="kode_peralatan"
                   class="form-control {{ $errors->has('kode_peralatan') ? 'is-invalid' : '' }}"
                   value="{{ old('kode_peralatan', $peralatan->kode_peralatan) }}">
            @error('kode_peralatan')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Peralatan</label>
            <input type="text" name="nama_peralatan"
                   class="form-control {{ $errors->has('nama_peralatan') ? 'is-invalid' : '' }}"
                   value="{{ old('nama_peralatan', $peralatan->nama_peralatan) }}">
            @error('nama_peralatan')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori" class="form-control"
                   value="{{ old('kategori', $peralatan->kategori) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Stok</label>
            <input type="number" name="stok"
                   class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
                   value="{{ old('stok', $peralatan->stok) }}" min="0">
            @error('stok')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Kondisi</label>
            <select name="kondisi" class="form-control">
                <option value="baik"         {{ old('kondisi', $peralatan->kondisi) == 'baik'         ? 'selected' : '' }}>Baik</option>
                <option value="rusak_ringan" {{ old('kondisi', $peralatan->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_berat"  {{ old('kondisi', $peralatan->kondisi) == 'rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control"
                      rows="3">{{ old('deskripsi', $peralatan->deskripsi) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Foto Peralatan</label>
            @if($peralatan->foto)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/peralatan/'.$peralatan->foto) }}"
                         class="preview-img" id="preview">
                </div>
            @else
                <img id="preview" src="#" class="preview-img" style="display:none; margin-bottom:10px;">
            @endif
            <div class="upload-area" onclick="document.getElementById('foto').click()">
                <input type="file" id="foto" name="foto" accept="image/*"
                       onchange="previewImage(this)">
                <p>Klik untuk ganti foto (kosongkan jika tidak diganti)</p>
            </div>
            @error('foto')<span class="invalid-feedback">{{ $message }}</span>@enderror
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
        reader.onload = e => {
            const prev = document.getElementById('preview');
            prev.src = e.target.result;
            prev.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
