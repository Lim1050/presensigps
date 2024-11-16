@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('master.departemen'))

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
    <h1 class="h3 mb-0 text-gray-800">Data Departemen</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                {{-- @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif --}}
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputDepartemen"><i class="bi bi-plus-lg"></i> Tambah Data Departemen</a>
            </div>
        </div>

        {{-- form cari data departemen --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.departemen') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_departemen_cari" id="nama_departemen_cari" class="form-control" placeholder="Cari Nama Departemen" value="{{ Request('nama_departemen') }}">
                            </div>
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
        </div> --}}
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Departemen</th>
                                <th class="text-center">Nama Departemen</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departemen as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_departemen }}</td>
                                <td>{{ $item->nama_departemen }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="{{ route('admin.departemen.edit', $item->kode_departemen) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.departemen.delete', $item->kode_departemen) }}" class="btn btn-danger delete-confirm" data-nama="{{ $item->nama_departemen }}"><i class="bi bi-trash3"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $departemen->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Departemen -->
<div class="modal fade" id="modalInputDepartemen" tabindex="-1" aria-labelledby="modalInputDepartemenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputDepartemenLabel">Input Data Departemen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.departemen.store') }}" method="POST" id="formDepartemen" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_departemen" name="kode_departemen" placeholder=" Kode Departemen">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-building"></i>
                            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" placeholder=" Nama Departemen">
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
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif
<script>
    let table = new DataTable('#dataTable');

    $(".delete-confirm").click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        var nama = $(this).data('nama');
        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data Departemen " + nama + " Akan Dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!"
        }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
        });
    });

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

@endif
@endsection
