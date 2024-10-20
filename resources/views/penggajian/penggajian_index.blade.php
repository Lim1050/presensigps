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
    <h1 class="h3 mb-0 text-gray-800">Penggajian Karyawan</h1>
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
                <a href="{{ route('admin.penggajian.create') }}" class="btn btn-primary mb-3">Tambah Penggajian</a>
            </div>
        </div>

        {{-- form cari data Gaji --}}
        {{-- <div class="row">
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
        </div> --}}
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Bulan</th>
                                <th class="text-center">Jumlah Hari</th>
                                <th class="text-center">Jumlah Masuk</th>
                                <th class="text-center">Jumlah Tidak Masuk</th>
                                <th class="text-center">Gaji</th>
                                <th class="text-center">Potongan</th>
                                <th class="text-center">Total Gaji</th>
                                <th class="text-center">Tanggal Gaji</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penggajian as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->nik }}</td>
                                <td class="text-center">{{ $item->karyawan->nama_lengkap }}</td>
                                <td class="text-center">{{$item->bulan }}</td>
                                <td class="text-center">{{$item->jumlah_hari_dalam_bulan }}</td>
                                <td class="text-center">{{$item->jumlah_hari_masuk }}</td>
                                <td class="text-center">{{$item->jumlah_hari_tidak_masuk }}</td>
                                <td class="text-center">{{ number_format($item->gaji, 2) }}</td>
                                <td class="text-center">
                                    {{ number_format($item->potongan, 2) }}
                                </td>
                                <td class="text-center">{{ number_format($item->total_gaji, 2) }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_gaji)->translatedFormat('d F Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.penggajian.show', $item->id) }}" class="btn btn-info" title="Lihat"><i class="bi bi-list"></i></a>
                                    <a href="{{ route('admin.penggajian.edit', $item->id) }}" class="btn btn-warning" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="{{ route('admin.penggajian.delete', $item->id) }}" title="Delete" class="btn btn-danger delete-confirm"><i class="bi bi-trash"></i></a>
                                    {{-- <form action="#" method="GET" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form> --}}
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

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $("#formGaji").submit(function(){
            var kode_gaji = $("#kode_gaji").val();
            var kode_jabatan = $("#kode_jabatan").val();
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
            } else if (nama_gaji==""){
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
            var nama_gaji = button.data('nama_gaji');
            var jumlah_gaji = button.data('jumlah_gaji');

            var modal = $(this);
            modal.find('.modal-body #edit_kode_gaji').val(kode_gaji);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
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
