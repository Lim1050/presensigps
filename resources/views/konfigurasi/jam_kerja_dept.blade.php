@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('konfigurasi.jam-kerja-departemen'))
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
    @if (Auth::user()->role == 'admin-cabang')
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jam Kerja Departemen Cabang {{ $nama_cabang->nama_cabang}}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jam Kerja Departemen</h1>
    @endif
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-4">
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
                <a href="{{ route('admin.konfigurasi.jam-kerja-dept.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Data Jam Kerja</a>
            </div>
        </div>

        {{-- form cari data jam kerja --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.jam-kerja-dept') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $item)
                                        <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ strtoupper($item->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                        <option {{ Request('kode_departemen') == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ strtoupper($item->nama_departemen) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger w-100">
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
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">No</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">Departemen</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jam_kerja_dept as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_jk_dept }}</td>
                                <td class="text-center">{{ $item->cabang->nama_cabang }}</td>
                                <td class="text-center">{{ $item->departemen->nama_departemen }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.view', $item->kode_jk_dept) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.edit', $item->kode_jk_dept) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.delete', $item->kode_jk_dept) }}"
                                            class="btn btn-danger delete-confirm"
                                            data-kode="{{ $item->kode_jk_dept }}"
                                            data-cabang="{{ $item->cabang->nama_cabang }}"
                                            data-departemen="{{ $item->departemen->nama_departemen }}"
                                            ><i class="bi bi-trash3"></i> Delete</a>
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

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Info',
                text: "{{ session('info') }}",
                icon: 'info',
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
        var kode = $(this).data('kode');
        var cabang = $(this).data('cabang');
        var departemen = $(this).data('departemen');
        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data Jam Kerja dengan kode " + kode + " Cabang " + cabang + " Departemen " + departemen + " Ini Akan Dihapus!",
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

</script>


@endpush

@endif
@endsection
