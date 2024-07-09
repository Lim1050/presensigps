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
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jam Kerja</h1>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif
                <form action="#" method="POST" id="formKaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-clock"></i>
                            <input type="text" class="form-control" id="jam_masuk" name="jam_masuk" placeholder=" Jam Masuk">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-clock"></i>
                            <input type="text" class="form-control" id="jam_keluar" name="jam_keluar" placeholder=" Jam Pulang">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-up"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
