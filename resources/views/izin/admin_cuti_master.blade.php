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
    <h1 class="h3 mb-0 text-gray-800">Data Cuti</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-3">
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputCuti"><i class="bi bi-plus-lg"></i> Tambah Data Cuti</a>
            </div>
        </div>

        {{-- form cari data cuti --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.cuti') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_cuti_cari" id="nama_cuti_cari" class="form-control" placeholder="Cari Nama Cuti" value="{{ Request('nama_cuti') }}">
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
        <div class="row mt-2">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Cuti</th>
                                <th class="text-center">Nama Cuti</th>
                                <th class="text-center">Jumlah Hari</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuti as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_cuti }}</td>
                                <td class="text-center">{{ $item->nama_cuti }}</td>
                                <td class="text-center">{{ $item->jumlah_hari }} Hari</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditCuti"
                                            data-kode_cuti="{{ $item->kode_cuti }}"
                                            data-nama_cuti="{{ $item->nama_cuti }}"
                                            data-jumlah_hari="{{ $item->jumlah_hari }}"
                                            ><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.cuti.delete', $item->kode_cuti) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
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

<!-- Modal Input Cuti -->
<div class="modal fade" id="modalInputCuti" tabindex="-1" aria-labelledby="modalInputCutiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputCutiLabel">Input Data Cuti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.cuti.store') }}" method="POST" id="formCuti" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_cuti" name="kode_cuti" placeholder=" Kode Cuti">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-calendar3"></i>
                            <input type="text" class="form-control" id="nama_cuti" name="nama_cuti" placeholder=" Nama Cuti">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-calendar-date"></i>
                            <input type="text" class="form-control" id="jumlah_hari" name="jumlah_hari" placeholder=" Jumlah Hari">
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

<!-- Modal Edit Cuti -->
<div class="modal fade" id="modalEditCuti" tabindex="-1" aria-labelledby="modalEditCutiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCutiLabel">Edit Data Cuti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.cuti.update', ['kode_cuti' => 0]) }}" method="POST" id="formEditCuti" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_cuti" name="kode_cuti" placeholder=" Kode Cuti" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-calendar3"></i>
                            <input type="text" class="form-control" id="edit_nama_cuti" name="nama_cuti" placeholder=" Nama Cuti">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-calendar-date"></i>
                            <input type="text" class="form-control" id="edit_jumlah_hari" name="jumlah_hari" placeholder=" Jumlah Hari">
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
        $("#formCuti").submit(function(){
            var kode_cuti = $("#kode_cuti").val();
            var nama_cuti = $("#nama_cuti").val();
            var jumlah_hari = $("#jumlah_hari").val();

            if(kode_cuti==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Cuti Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cuti").focus();
                });
                return false;
            } else if (nama_cuti==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Cuti Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_cuti").focus();
                });
                return false;
            } else if (jumlah_hari==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Hari Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jumlah_hari").focus();
                });
                return false;
            }
        });

        $("#formEditCuti").submit(function(){
            var nama_cuti = $("#edit_nama_cuti").val();
            var jumlah_hari = $("#edit_jumlah_hari").val();

            if (nama_cuti==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Cuti Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#edit_nama_cuti").focus();
                });
                return false;
            } else if (jumlah_hari==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Hari Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#edit_jumlah_hari").focus();
                });
                return false;
            }
        });

        // Send Data to modal edit jam kerja
        $('#modalEditCuti').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var kode_cuti = button.data('kode_cuti');
        var nama_cuti = button.data('nama_cuti');
        var jumlah_hari = button.data('jumlah_hari');

        var modal = $(this);
        modal.find('.modal-body #kode_cuti').val(kode_cuti);
        modal.find('.modal-body #edit_nama_cuti').val(nama_cuti);
        modal.find('.modal-body #edit_jumlah_hari').val(jumlah_hari);

        // Update form action URL with the id
        var formAction = "{{ route('admin.cuti.update', ['kode_cuti' => ':kode_cuti']) }}";
        formAction = formAction.replace(':kode_cuti', kode_cuti);
        $('#formEditCuti').attr('action', formAction);
    });
</script>


@endpush


@endsection
