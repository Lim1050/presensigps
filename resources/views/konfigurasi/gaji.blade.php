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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Jenis Gaji</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputJenisGaji"><i class="bi bi-plus-lg"></i> Tambah Data Jenis Gaji</a>
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
                                <th class="text-center">Kode Jenis Gaji</th>
                                <th class="text-center">Jenis Gaji</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($konfigurasiGaji as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->kode_jenis_gaji }}</td>
                                <td class="text-center">{{ $item->jenis_gaji }}</td>
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
                                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditJenisGaji"
                                            data-kode_jenis_gaji="{{ $item->kode_jenis_gaji }}"
                                            data-jenis_gaji="{{ $item->jenis_gaji }}"
                                            data-keterangan="{{ $item->keterangan }}"
                                            data-is_active="{{ $item->is_active }}"
                                            ><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.konfigurasi.jenis.gaji.delete', $item->kode_jenis_gaji) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
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

<!-- Modal Input Jenis Gaji -->
<div class="modal fade" id="modalInputJenisGaji" tabindex="-1" aria-labelledby="modalInputJenisGajiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputJenisGajiLabel">Input Data Jenis Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.konfigurasi.jenis.gaji.store') }}" method="POST" id="formJenisGaji" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jenis_gaji" name="kode_jenis_gaji" placeholder=" Kode Jenis Gaji">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="jenis_gaji" name="jenis_gaji" placeholder=" Nama Jenis Gaji">
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
<div class="modal fade" id="modalEditJenisGaji" tabindex="-1" role="dialog" aria-labelledby="modalEditJenisGajiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJenisGajiLabel">Edit Jenis Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFormJenisGaji" method="POST" action="{{ route('admin.konfigurasi.jenis.gaji.update', ['kode_jenis_gaji' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_jenis_gaji" name="kode_jenis_gaji" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="jenis_gaji" name="jenis_gaji">
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

    // Validasi form
        $("#formJenisGaji").submit(function(){
            var kode_jenis_gaji = $("#kode_jenis_gaji").val();
            var jenis_gaji = $("#jenis_gaji").val();
            var keterangan = $("#keterangan").val();
            var is_active = $("#is_active").val();


            if(kode_jenis_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Jenis Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jenis_gaji").focus();
                });
                return false;
            } else if (jenis_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jenis Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jenis_gaji").focus();
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
        $('#modalEditJenisGaji').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var kode_jenis_gaji = button.data('kode_jenis_gaji');
        var jenis_gaji = button.data('jenis_gaji');
        var keterangan = button.data('keterangan');
        var is_active = button.data('is_active');

        var modal = $(this);
        modal.find('.modal-body #kode_jenis_gaji').val(kode_jenis_gaji);
        modal.find('.modal-body #jenis_gaji').val(jenis_gaji);
        modal.find('.modal-body #keterangan').val(keterangan);
        modal.find('.modal-body #is_active').val(is_active);

        // Update form action URL with the id
        var formAction = "{{ route('admin.konfigurasi.jenis.gaji.update', ['kode_jenis_gaji' => ':kode_jenis_gaji']) }}";
        formAction = formAction.replace(':kode_jenis_gaji', kode_jenis_gaji);
        $('#editFormJenisGaji').attr('action', formAction);
    });
</script>


@endpush


@endsection