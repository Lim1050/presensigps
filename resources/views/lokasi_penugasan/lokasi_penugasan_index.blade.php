@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('master.lokasi-penugasan'))
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
    <h1 class="h3 mb-0 text-gray-800">Lokasi Penugasan</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputLokasiPenugasan"><i class="bi bi-plus-lg"></i> Tambah Lokasi Penugasan</a>
            </div>
        </div>

        {{-- form cari Lokasi Penugasan --}}
        <form action="{{ route('admin.lokasi.penugasan') }}" method="GET">
            <div class="row mt-2">
                <div class="col-3">
                    <div class="form-group">
                        <input type="text" name="nama_lokasi_penugasan" id="nama_lokasi_penugasan_cari" class="form-control" placeholder="Cari Nama Lokasi Penugasan" value="{{ Request('nama_lokasi_penugasan') }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <input type="number" name="jumlah_jam_kerja" id="jumlah_jam_kerja_cari" class="form-control" placeholder="Cari Jumlah Jam Kerja" value="{{ Request('jumlah_jam_kerja') }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <input type="number" name="jumlah_hari_kerja" id="jumlah_hari_kerja_cari" class="form-control" placeholder="Cari Jumlah Hari Kerja" value="{{ Request('jumlah_hari_kerja') }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="kode_cabang" id="kode_cabang_cari" class="form-control">
                            <option value="">Pilih Kantor Cabang</option>
                            @foreach ($cabang as $item)
                            <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
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

        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Lokasi Penugasan</th>
                                <th class="text-center">Nama Lokasi Penugasan</th>
                                <th class="text-center">Koordinat</th>
                                <th class="text-center">Radius</th>
                                <th class="text-center">Jumlah Jam Kerja</th>
                                <th class="text-center">Jumlah Hari Kerja</th>
                                <th class="text-center">Nama Cabang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lokasi_penugasan as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_lokasi_penugasan }}</td>
                                <td class="text-center">{{ $item->nama_lokasi_penugasan }}</td>
                                <td class="text-center">{{ $item->lokasi_penugasan }}</td>
                                <td class="text-center">{{ $item->radius }} meter</td>
                                <td class="text-center">{{ $item->jumlah_jam_kerja }} jam</td>
                                <td class="text-center">{{ $item->jumlah_hari_kerja }} hari</td>
                                <td class="text-center">{{ $item->cabang->nama_cabang }}</td>
                                <td class="text-center" class="text-center">
                                    <div class="btn-group ">
                                        <a href="{{ route('admin.lokasi.penugasan.edit', $item->kode_lokasi_penugasan) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button class="btn btn-danger delete-confirm" data-kode="{{ $item->kode_lokasi_penugasan }}" data-nama="{{ $item->nama_lokasi_penugasan }}">
                                            <i class="bi bi-trash3"></i> Delete
                                        </button>
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

<!-- Modal Input LokasiPenugasan -->
<div class="modal fade" id="modalInputLokasiPenugasan" tabindex="-1" aria-labelledby="modalInputLokasiPenugasanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputLokasiPenugasanLabel">Input Data Lokasi Penugasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.lokasi.penugasan.store') }}" method="POST" id="formLokasiPenugasan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_lokasi_penugasan" name="kode_lokasi_penugasan" placeholder=" Kode Lokasi Penugasan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-pin-map-fill"></i>
                            <input type="text" class="form-control" id="nama_lokasi_penugasan" name="nama_lokasi_penugasan" placeholder=" Nama Lokasi Penugasan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-geo"></i>
                            <input type="text" class="form-control" id="lokasi_penugasan" name="lokasi_penugasan" placeholder=" Koordinat pada Google Maps">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-broadcast"></i>
                            <input type="text" class="form-control" id="radius" name="radius" placeholder=" Radius">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-clock"></i>
                            <input type="number" class="form-control" id="jumlah_jam_kerja" name="jumlah_jam_kerja" placeholder=" Jumlah Jam Kerja">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-calendar"></i>
                            <input type="number" class="form-control" id="jumlah_hari_kerja" name="jumlah_hari_kerja" placeholder=" Jumlah Hari Kerja">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="kode_cabang" id="kode_cabang" class="form-control">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                            <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
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

    $(document).ready(function() {
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data Lokasi Penugasan " + nama + " akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.lokasi.penugasan.delete', '') }}/" + kode,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Data Lokasi Penugasan telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });

    // Validasi form
        $("#formLokasiPenugasan").submit(function(){
            var kode_lokasi_penugasan = $("#kode_lokasi_penugasan").val();
            var nama_lokasi_penugasan = $("#nama_lokasi_penugasan").val();

            if(kode_lokasi_penugasan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Lokasi Penugasan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_lokasi_penugasan").focus();
                });
                return false;
            } else if (nama_lokasi_penugasan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Lokasi Penugasan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_lokasi_penugasan").focus();
                });
                return false;
            }
        });
</script>


@endpush

@endif
@endsection
