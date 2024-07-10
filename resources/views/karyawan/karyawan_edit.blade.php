@extends('layouts.admin.admin_master')
@section('content')

<style>
    .icon-placeholder {
        position: relative;
        /* display: inline-block; */
    }

    .icon-placeholder input {
        padding-left: 30px; /* Adjust padding to make room for the icon */
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
        width: 100px !important;
        height: 150px !important;
        object-fit: cover;
        display: none; /* Initially hide the image */
    }
    #showImage {
    width: 100px;
    height: 150px;
    object-fit: cover;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Data Karyawan</h1>
</div>
<form action="{{ route('admin.karyawan.update') }}" method="POST" id="formKaryawan" enctype="multipart/form-data">
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
        <label for=""><i class="bi bi-building"></i> Departemen</label>
        <select name="kode_departemen" id="kode_departement" class="form-control">
            <option value="">Pilih Departemen</option>
            @foreach ($departemen as $item)
            <option {{ $karyawan->kode_departemen == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <select name="kode_cabang" id="kode_cabang" class="form-control">
            <option value="">Pilih Cabang</option>
            @foreach ($cabang as $item)
            <option {{ $karyawan->kode_cabang == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
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
    <div class="input-group mb-3">
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
                <a href="{{ route('admin.karyawan') }}"class="btn btn-danger w-100">Batal Edit</a>
            </div>
        </div>
    </div>
</form>
@push('myscript')
<script>

// Validasi form
        $("#formKaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var no_wa = $("#no_wa").val();
            var jabatan = $("#jabatan").val();
            var kode_departement = $("#kode_departement").val();
            var kode_cabang = $("#kode_cabang").val();
            var foto = $("#foto").val();

            if(nik==""){
                Swal.fire({
                title: 'Oops!',
                text: 'NIK Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nik").focus();
                });
                return false;
            } else if (nama_lengkap==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_lengkap").focus();
                });
                return false;
            } else if (no_wa==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nomor HP Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#no_wa").focus();
                });
                return false;
            } else if (jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jabatan").focus();
                });
                return false;
            } else if (kode_departement==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Departemen Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_departement").focus();
                });
                return false;
            } else if (kode_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cabang").focus();
                });
                return false;
            }
        });

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
