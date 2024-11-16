@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('master.jabatan'))
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
    <h1 class="h3 mb-0 text-gray-800">Data Jabatan</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputJabatan"><i class="bi bi-plus-lg"></i> Tambah Data Jabatan</a>
            </div>
        </div>

        {{-- form cari data Jabatan --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.jabatan') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_jabatan_cari" id="nama_jabatan_cari" class="form-control" placeholder="Cari Nama jabatan" value="{{ Request('nama_jabatan') }}">
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
                                <th class="text-center">Kode Jabatan</th>
                                <th class="text-center">Nama Jabatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jabatan as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_jabatan }}</td>
                                <td>{{ $item->nama_jabatan }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        {{-- <a href="{{ route('admin.jabatan.edit', $item->kode_jabatan) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a> --}}
                                        <a href="#" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#modalEditJabatan"
                                                data-kode_jabatan="{{ $item->kode_jabatan }}"
                                                data-nama_jabatan="{{ $item->nama_jabatan }}"
                                                >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <a href="{{ route('admin.jabatan.delete', $item->kode_jabatan) }}" class="btn btn-danger delete-confirm" title="Delete" data-nama="{{ $item->nama_jabatan }}"><i class="bi bi-trash3"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $jabatan->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Jabatan -->
<div class="modal fade" id="modalInputJabatan" tabindex="-1" aria-labelledby="modalInputJabatanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputJabatanLabel">Input Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.jabatan.store') }}" method="POST" id="formJabatan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jabatan" name="kode_jabatan" placeholder=" Kode Jabatan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" placeholder=" Nama Jabatan">
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

<!-- Modal Edit Jabatan -->
<div class="modal fade" id="modalEditJabatan" tabindex="-1" aria-labelledby="modalEditJabatanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJabatanLabel">Edit Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.jabatan.update', ['kode_jabatan' => 0]) }}" method="POST" id="formJabatan" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="edit_kode_jabatan" name="kode_jabatan" placeholder=" Kode Jabatan" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="edit_nama_jabatan" name="nama_jabatan" placeholder=" Nama Jabatan">
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
        text: "Data Jabatan " + nama + " Akan Dihapus!",
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
        $("#formJabatan").submit(function(){
            var kode_jabatan = $("#kode_jabatan").val();
            var nama_jabatan = $("#nama_jabatan").val();

            if(kode_jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jabatan").focus();
                });
                return false;
            } else if (nama_jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_jabatan").focus();
                });
                return false;
            }
        });

        // Mengisi Data pada Modal Edit Jabatan
$('#modalEditJabatan').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var kode_jabatan = button.data('kode_jabatan');
    var nama_jabatan = button.data('nama_jabatan');

    var modal = $(this);
    modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
    modal.find('.modal-body #edit_nama_jabatan').val(nama_jabatan);

    // Update URL form action dengan username yang sesuai
    var formAction = "{{ route('admin.jabatan.update', ['kode_jabatan' => ':kode_jabatan']) }}";
    formAction = formAction.replace(':kode_jabatan', kode_jabatan);
    $('#formEditJabatan').attr('action', formAction);
});
</script>


@endpush

@endif
@endsection
