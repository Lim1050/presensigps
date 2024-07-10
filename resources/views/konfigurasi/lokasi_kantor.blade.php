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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Lokasi Kantor</h1>
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
                <form action="{{ route('admin.update.lokasi.kantor') }}" method="POST" id="form_lokasi_kantor" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-geo"></i>
                            <input type="text" class="form-control" id="lokasi_kantor" name="lokasi_kantor" value="{{ $lokasi_kantor->lokasi_kantor }}" placeholder=" Lokasi Kantor">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-broadcast"></i>
                            <input type="text" class="form-control" id="radius" name="radius" value="{{ $lokasi_kantor->radius }}" placeholder=" Radius">
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

@push('myscript')
    <script>
        $(function(){
            $("#form_lokasi_kantor").submit(function(e){
                var bulan = $("#lokasi_kantor").val();
                var radius = $("#radius").val();
                var nik = $("#nik").val();
                if(lokasi_kantor==""){
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
        });
    </script>
@endpush
