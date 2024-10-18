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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jam Kerja Departement</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-4">
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
                <a href="{{ route('admin.konfigurasi.jam-kerja-dept.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Data Jam Kerja</a>
            </div>
        </div>

        {{-- form cari data jam kerja --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.jam.kerja') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_jam_kerja_cari" id="nama_jam_kerja_cari" class="form-control" placeholder="Cari Nama Jam Kerja" value="{{ Request('nama_jam_kerja') }}">
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
                                <td class="text-center">{{ $item->nama_cabang }}</td>
                                <td class="text-center">{{ $item->nama_departemen }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.view', $item->kode_jk_dept) }}" class="btn btn-primary"><i class="bi bi-eye"></i> View</a>
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.edit', $item->kode_jk_dept) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.konfigurasi.jam-kerja-dept.delete', $item->kode_jk_dept) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
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
<script>
    let table = new DataTable('#dataTable');

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

</script>


@endpush


@endsection
