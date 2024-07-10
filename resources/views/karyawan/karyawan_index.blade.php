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
    <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputKaryawan"><i class="bi bi-plus-lg"></i> Tambah Data Karyawan</a>
            </div>
        </div>

        {{-- form cari data karyawan --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.karyawan') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                    <option {{ Request('kode_departemen') == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                                    @endforeach
                                </select>
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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Foto</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Kantor</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $item)
                            @php
                                $path = Storage::url("uploads/karyawan/".$item->foto)
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $karyawan->firstItem() -1}}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>
                                    <div class="text-center">
                                        @if (empty($item->foto))
                                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
                                        @else
                                            <img src="{{ url($path) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->nama_departemen }}</td>
                                <td>{{ $item->kode_cabang }}</td>
                                <td>{{ $item->no_wa }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.karyawan.edit', $item->nik) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <a href="{{ route('admin.karyawan.delete', $item->nik) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Karyawan -->
<div class="modal fade" id="modalInputKaryawan" tabindex="-1" aria-labelledby="modalInputKaryawanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputKaryawanLabel">Input Data Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.karyawan.store') }}" method="POST" id="formKaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="nik" name="nik" placeholder=" NIK">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder=" Nama Lengkap">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="text" class="form-control" id="no_wa" name="no_wa" placeholder=" Nomor HP">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder=" Jabatan">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="kode_departemen" id="kode_departement" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemen as $item)
                            <option {{ Request('kode_departemen') == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_cabang" id="kode_cabang" class="form-control">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                            <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto">
                            <label class="custom-file-label" for="foto"  aria-describedby="inputGroupFileAddon02"><i class="bi bi-image"></i> Pilih Foto Karyawan</label>
                        </div>
                    </div>
                    <div class="preview-container">
                        <img id="imgPreview" src="#" alt="Your Image">
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

    // preview image
    $(document).ready(function () {
            $('#foto').on('change', function () {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imgPreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imgPreview').hide();
                }
            });
        });

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
        $("#formKaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var no_wa = $("#no_wa").val();
            var jabatan = $("#jabatan").val();
            var kode_departement = $("#kode_departement").val();
            var kode_cabang = $("#kode_cabang").val();
            var foto = $("#foto").val();

            if(nik==""){
                Swal.fire({
                title: 'Oops!',
                text: 'NIK Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nik").focus();
                });
                return false;
            } else if (nama_lengkap==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_lengkap").focus();
                });
                return false;
            } else if (no_wa==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nomor HP Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#no_wa").focus();
                });
                return false;
            } else if (jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jabatan").focus();
                });
                return false;
            } else if (kode_departement==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Departemen Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_departement").focus();
                });
                return false;
            } else if (kode_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cabang").focus();
                });
                return false;
            } else if (foto==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Foto Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#foto").focus();
                });
                return false;
            }
        });
</script>


@endpush


@endsection
