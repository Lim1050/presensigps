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
        width: 100px;
        height: 150px;
        object-fit: cover;
        display: none; /* Initially hide the image */
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Data Cabang</h1>
</div>
<form action="{{ route('admin.cabang.update') }}" method="POST" id="formCabang" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-upc-scan"></i>
            <input type="text" class="form-control" id="kode_cabang" name="kode_cabang" value="{{ $cabang->kode_cabang }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-buildings"></i>
            <input type="text" class="form-control" id="nama_cabang" name="nama_cabang" value="{{ $cabang->nama_cabang }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-geo"></i>
            <input type="text" class="form-control" id="lokasi_kantor" name="lokasi_kantor" value="{{ $cabang->lokasi_kantor }}">
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-broadcast"></i>
            <input type="text" class="form-control" id="radius" name="radius" value="{{ $cabang->radius }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
            </div>
            <div class="form-group">
                <a href="{{ route('admin.cabang') }}"class="btn btn-danger w-100">Batal Edit</a>
            </div>
        </div>
    </div>
</form>
@push('myscript')
<script>
    // Validasi form
        $("#formCabang").submit(function(){
            var kode_cabang = $("#kode_cabang").val();
            var nama_cabang = $("#nama_cabang").val();
            var lokasi_kantor = $("#lokasi_kantor").val();
            var radius = $("#radius").val();

            if(kode_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cabang").focus();
                });
                return false;
            } else if (nama_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_cabang").focus();
                });
                return false;
            } else if (lokasi_kantor==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Lokasi Kantor Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#lokasi_kantor").focus();
                });
                return false;
            } else if (radius==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Radius Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#radius").focus();
                });
                return false;
            }
        });
</script>


@endpush

@endsection
