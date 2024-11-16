@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('konfigurasi.jenis-potongan'))
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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jenis Potongan</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputJenisPotongan"><i class="bi bi-plus-lg"></i> Tambah Data Jenis Potongan</a>
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
                                <th class="text-center">Kode Jenis Potongan</th>
                                <th class="text-center">Jenis Potongan</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($konfigurasiPotongan as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_jenis_potongan }}</td>
                                <td class="text-center">{{ $item->jenis_potongan }}</td>
                                <td class="text-center">{{ $item->keterangan }}</td>
                                <td class="text-center">
                                    @if ($item->is_active == 1)
                                        <span class="badge bg-success" style="color: white"><i class="bi bi-check2"></i></span>
                                    @else
                                        <span class="badge bg-danger" style="color: white"><i class="bi bi-x-lg"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditJenisPotongan"
                                            data-kode_jenis_potongan="{{ $item->kode_jenis_potongan }}"
                                            data-jenis_potongan="{{ $item->jenis_potongan }}"
                                            data-keterangan="{{ $item->keterangan }}"
                                            data-is_active="{{ $item->is_active }}"
                                            ><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.konfigurasi.jenis.potongan.delete', $item->kode_jenis_potongan) }}"
                                            class="btn btn-danger delete-confirm"
                                            data-jenis_potongan="{{ $item->jenis_potongan }}"
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

<!-- Modal Input Jenis Potongan -->
<div class="modal fade" id="modalInputJenisPotongan" tabindex="-1" aria-labelledby="modalInputJenisPotonganLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputJenisPotonganLabel">Input Data Jenis Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.konfigurasi.jenis.potongan.store') }}" method="POST" id="formJenisPotongan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jenis_potongan" name="kode_jenis_potongan" placeholder=" Kode Jenis Potongan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="jenis_potongan" name="jenis_potongan" placeholder=" Nama Jenis Potongan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-list-columns-reverse"></i>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder=" Keterangan">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="">Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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

<!-- Modal -->
<div class="modal fade" id="modalEditJenisPotongan" tabindex="-1" role="dialog" aria-labelledby="modalEditJenisPotonganLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJenisPotonganLabel">Edit Jenis Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFormJenisPotongan" method="POST" action="{{ route('admin.konfigurasi.jenis.potongan.update', ['kode_jenis_potongan' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jenis_potongan" name="kode_jenis_potongan" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="jenis_potongan" name="jenis_potongan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-list-columns-reverse"></i>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="">Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
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
        var jenis_potongan = $(this).data('jenis_potongan');

        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data jenis potongan " + jenis_potongan + " akan dihapus!",
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
        $("#formJenisPotongan").submit(function(){
            var kode_jenis_potongan = $("#kode_jenis_potongan").val();
            var jenis_potongan = $("#jenis_potongan").val();
            var keterangan = $("#keterangan").val();
            var is_active = $("#is_active").val();


            if(kode_jenis_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Jenis Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jenis_potongan").focus();
                });
                return false;
            } else if (jenis_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jenis Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jenis_potongan").focus();
                });
                return false;
            } else if (keterangan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Keterangan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#keterangan").focus();
                });
                return false;
            } else if (is_active==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Status Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#is_active").focus();
                });
                return false;
            }
        });

    // Send Data to modal edit jam kerja
        $('#modalEditJenisPotongan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var kode_jenis_potongan = button.data('kode_jenis_potongan');
        var jenis_potongan = button.data('jenis_potongan');
        var keterangan = button.data('keterangan');
        var is_active = button.data('is_active');

        var modal = $(this);
        modal.find('.modal-body #kode_jenis_potongan').val(kode_jenis_potongan);
        modal.find('.modal-body #jenis_potongan').val(jenis_potongan);
        modal.find('.modal-body #keterangan').val(keterangan);
        modal.find('.modal-body #is_active').val(is_active);

        // Update form action URL with the id
        var formAction = "{{ route('admin.konfigurasi.jenis.potongan.update', ['kode_jenis_potongan' => ':kode_jenis_potongan']) }}";
        formAction = formAction.replace(':kode_jenis_potongan', kode_jenis_potongan);
        $('#editFormJenisPotongan').attr('action', formAction);
    });
</script>


@endpush

@endif
@endsection
