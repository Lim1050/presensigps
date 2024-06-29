@extends('layouts.admin.admin_master')
@section('content')

<style>
    .icon-placeholder {
        position: relative;
        /* display: inline-block; */
    }

    .icon-placeholder input {
        padding-left: 25px; /* Adjust padding to make room for the icon */
    }

    .icon-placeholder .bi {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        /* color: #ccc; Icon color */
    }

    .preview-container {
            margin-top: 20px;
    }
    .preview-container img {
        width: 100px;
        height: 150px;
        object-fit: cover;
        display: none; /* Initially hide the image */
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<form action="{{ route('karyawan.update') }}" method="POST" id="formKaryawan" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-upc-scan"></i>
            <input type="text" class="form-control" id="nik" name="nik" value="{{ $karyawan->nik }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-person-fill"></i>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ $karyawan->nama_lengkap }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-telephone-fill"></i>
            <input type="text" class="form-control" id="no_wa" name="no_wa" value="{{ $karyawan->no_wa }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-person-vcard"></i>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $karyawan->jabatan }}">
        </div>
    </div>
    <div class="form-group">
        <select name="kode_departemen" id="kode_departement" class="form-control">
            <option value="">Pilih Departemen</option>
            @foreach ($departemen as $item)
            <option {{ $karyawan->kode_departemen == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
            @endforeach
        </select>
    </div>
    <div class="input-group mb-3">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="foto" name="foto" onchange="previewImage()">
            <input type="hidden" name="old_foto" value="{{ $karyawan->foto }}">
            <label class="custom-file-label" for="foto" aria-describedby="inputGroupFileAddon02">
                <i class="bi bi-image"></i> Pilih Foto Karyawan
            </label>
        </div>
    </div>
    <div class="row">
        <img id="showImage" src="{{ Storage::url('uploads/karyawan/' . $karyawan->foto) }}">
        <div class="preview-container">
            @php
                $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
            @endphp
            <img id="imgPreview" src="{{ $path }}" alt="Your Image">
        </div>
    </div>




    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
            </div>
            <div class="form-group">
                <a href="{{ route('karyawan') }}"class="btn btn-danger w-100">Batal Edit</a>
            </div>
        </div>
    </div>
</form>
@push('myscript')
<script>
        // preview image
    function previewImage() {
    const fileInput = document.getElementById('foto');
    const imgPreview = document.getElementById('imgPreview');
    const showImage = document.getElementById('showImage');

    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
            showImage.src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        // Jika tidak ada file baru yang dipilih, tetap tampilkan foto lama
        imgPreview.src = "{{ Storage::url('uploads/karyawan/' . $karyawan->foto) }}";
        showImage.src = "{{ Storage::url('uploads/karyawan/' . $karyawan->foto) }}";
    }
}
</script>


@endpush

@endsection
