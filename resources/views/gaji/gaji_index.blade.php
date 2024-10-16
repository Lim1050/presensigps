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
    <h1 class="h3 mb-0 text-gray-800">Data Gaji</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputGaji"><i class="bi bi-plus-lg"></i> Tambah Data Gaji</a>
            </div>
        </div>

        {{-- form cari data Gaji --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.gaji') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_gaji_cari" id="nama_gaji_cari" class="form-control" placeholder="Cari Nama Gaji" value="{{ Request('nama_gaji') }}">
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
        </div>
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Gaji</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Jenis Gaji</th>
                                <th class="text-center">Nama Gaji</th>
                                <th class="text-right">Jumlah Gaji</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gaji as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_gaji }}</td>
                                <td class="text-center">{{ $item->jabatan->nama_jabatan }}</td>
                                <td class="text-center">{{ $item->jenisGaji->jenis_gaji }}</td>
                                <td class="text-center">{{ $item->nama_gaji }}</td>
                                <td class="text-right">Rp {{ $item->jumlah_gaji }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        {{-- <a href="{{ route('admin.jabatan.edit', $item->kode_jabatan) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a> --}}
                                        <a href="#" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#modalEditGaji"
                                                data-kode_gaji="{{ $item->kode_gaji }}"
                                                data-kode_jabatan="{{ $item->kode_jabatan }}"
                                                data-kode_jenis_gaji="{{ $item->kode_jenis_gaji }}"
                                                data-nama_gaji="{{ $item->nama_gaji }}"
                                                data-jumlah_gaji="{{ $item->jumlah_gaji }}"
                                                >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <a href="{{ route('admin.gaji.delete', $item->kode_gaji) }}" class="btn btn-danger delete-confirm" title="Delete"><i class="bi bi-trash3"></i></a>
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

<!-- Modal Input Gaji -->
<div class="modal fade" id="modalInputGaji" tabindex="-1" aria-labelledby="modalInputGajiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputGajiLabel">Input Data Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.gaji.store') }}" method="POST" id="formGaji" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_gaji" name="kode_gaji" placeholder=" Kode Gaji">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="kode_jabatan" id="kode_jabatan" class="form-control">
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatan as $item)
                            <option value="{{ $item->kode_jabatan  }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_jenis_gaji" id="kode_jenis_gaji" class="form-control">
                            <option value="">Pilih Jenis Gaji</option>
                            @foreach ($jenis_gaji as $item)
                            <option value="{{ $item->kode_jenis_gaji  }}">{{ $item->jenis_gaji }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <select name="jenis_gaji" id="jenis_gaji" class="form-control">
                            <option value="">Pilih Jenis Gaji</option>
                            <option value="Gaji tetap">Gaji tetap</option>
                            <option value="Tunjangan jabatan">Tunjangan jabatan</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="nama_gaji" name="nama_gaji" placeholder=" Nama Gaji">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash-stack"></i>
                            <input type="number" class="form-control" id="jumlah_gaji" name="jumlah_gaji" placeholder=" Jumlah Gaji">
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

<!-- Modal Edit Gaji -->
<div class="modal fade" id="modalEditGaji" tabindex="-1" aria-labelledby="modalEditGajiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditGajiLabel">Edit Data Gaji</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.gaji.update', ['kode_gaji' => 0]) }}" method="POST" id="formGaji" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="edit_kode_gaji" name="kode_gaji" placeholder=" Kode Gaji" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="kode_jabatan" id="edit_kode_jabatan" class="form-control">
                            <option value="">Pilih Jabatan</option>
                            @foreach ($jabatan as $item)
                            <option value="{{ $item->kode_jabatan  }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_jenis_gaji" id="edit_kode_jenis_gaji" class="form-control">
                            <option value="">Pilih Jenis Gaji</option>
                            @foreach ($jenis_gaji as $item)
                            <option value="{{ $item->kode_jenis_gaji  }}">{{ $item->jenis_gaji }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <select name="jenis_gaji" id="edit_jenis_gaji" class="form-control">
                            <option value="">Pilih Jenis Gaji</option>
                            <option value="Gaji tetap">Gaji tetap</option>
                            <option value="Tunjangan jabatan">Tunjangan jabatan</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="edit_nama_gaji" name="nama_gaji" placeholder=" Nama Gaji">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="number" class="form-control" id="edit_jumlah_gaji" name="jumlah_gaji" placeholder=" Jumlah Gaji">
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
        $("#formGaji").submit(function(){
            var kode_gaji = $("#kode_gaji").val();
            var kode_jabatan = $("#kode_jabatan").val();
            var kode_jenis_gaji = $("#kode_jenis_gaji").val();
            var nama_gaji = $("#nama_gaji").val();
            var jumlah_gaji = $("#jumlah_gaji").val();

            if(kode_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_gaji").focus();
                });
                return false;
            } else if (kode_jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jabatan").focus();
                });
                return false;
            } else if (kode_jenis_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jenis Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jenis_gaji").focus();
                });
                return false;
            }else if (nama_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_gaji").focus();
                });
                return false;
            } else if (jumlah_gaji==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Gaji Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jumlah_gaji").focus();
                });
                return false;
            }
        });

        // Mengisi Data pada Modal Edit Gaji
        $('#modalEditGaji').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var kode_gaji = button.data('kode_gaji');
            var kode_jabatan = button.data('kode_jabatan');
            var kode_jenis_gaji = button.data('kode_jenis_gaji');
            var nama_gaji = button.data('nama_gaji');
            var jumlah_gaji = button.data('jumlah_gaji');

            var modal = $(this);
            modal.find('.modal-body #edit_kode_gaji').val(kode_gaji);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
            modal.find('.modal-body #edit_kode_jenis_gaji').val(kode_jenis_gaji);
            modal.find('.modal-body #edit_nama_gaji').val(nama_gaji);
            modal.find('.modal-body #edit_jumlah_gaji').val(jumlah_gaji);

            // Update URL form action dengan username yang sesuai
            var formAction = "{{ route('admin.gaji.update', ['kode_gaji' => ':kode_gaji']) }}";
            formAction = formAction.replace(':kode_gaji', kode_gaji);
            $('#formEditGaji').attr('action', formAction);
        });
</script>


@endpush


@endsection
