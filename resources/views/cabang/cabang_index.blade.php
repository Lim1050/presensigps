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

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Kantor Cabang</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputCabang"><i class="bi bi-plus-lg"></i> Tambah Data Kantor Cabang</a>
            </div>
        </div>

        {{-- form cari data departemen --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.cabang') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            {{-- <div class="form-group">
                                <input type="text" name="nama_departemen_cari" id="nama_departemen_cari" class="form-control" placeholder="Cari Nama Departemen" value="{{ Request('nama_departemen') }}">
                            </div> --}}
                            <select name="kode_cabang" class="form-control" id="">
                                <option value="">Semua Cabang</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Kode Cabang</th>
                                <th>Nama Cabang</th>
                                <th>Lokasi Cabang</th>
                                <th>Radius</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cabang as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_cabang }}</td>
                                <td>{{ $item->nama_cabang }}</td>
                                <td class="text-center">{{ $item->lokasi_kantor }}</td>
                                <td class="text-center">{{ $item->radius }} meter</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="{{ route('admin.cabang.edit', $item->kode_cabang) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.cabang.delete', $item->kode_cabang) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $departemen->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Cabang -->
<div class="modal fade" id="modalInputCabang" tabindex="-1" aria-labelledby="modalInputCabangLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputCabangLabel">Input Data Kantor Cabang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.cabang.store') }}" method="POST" id="formCabang" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_cabang" name="kode_cabang" placeholder=" Kode Cabang">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-buildings"></i>
                            <input type="text" class="form-control" id="nama_cabang" name="nama_cabang" placeholder=" Nama Cabang">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-geo"></i>
                            <input type="text" class="form-control" id="lokasi_kantor" name="lokasi_kantor" placeholder=" Lokasi Cabang">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-broadcast"></i>
                            <input type="text" class="form-control" id="radius" name="radius" placeholder=" Radius">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('myscript')
<script>

    $(".delete-confirm").click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data Ini Akan Dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!"
        }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
            Swal.fire({
            title: "Terhapus!",
            text: "Data Sudah dihapus.",
            icon: "success"
            });
        }
        });
    });

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
