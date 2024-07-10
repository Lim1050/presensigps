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
    <h1 class="h3 mb-0 text-gray-800">Edit Data Departemen</h1>
</div>
<form action="{{ route('admin.departemen.update') }}" method="POST" id="formDepartemen" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-upc-scan"></i>
            <input type="text" class="form-control" id="kode_departemen" name="kode_departemen" value="{{ $departemen->kode_departemen }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="icon-placeholder">
            <i class="bi bi-building"></i>
            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" value="{{ $departemen->nama_departemen }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
            </div>
            <div class="form-group">
                <a href="{{ route('admin.departemen') }}"class="btn btn-danger w-100">Batal Edit</a>
            </div>
        </div>
    </div>
</form>
@push('myscript')
<script>
    // Validasi form
        $("#formDepartemen").submit(function(){
            var kode_departemen = $("#kode_departemen").val();
            var nama_departemen = $("#nama_departemen").val();

            if(kode_departemen==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Departemen Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_departemen").focus();
                });
                return false;
            } else if (nama_departemen==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Departemen Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_departemen").focus();
                });
                return false;
            }
        });
</script>


@endpush

@endsection
