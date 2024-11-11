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

    #edit_imgPreview {
    display: block; /* Pastikan gambar ditampilkan */
    visibility: visible; /* Pastikan gambar terlihat */
    opacity: 1; /* Pastikan gambar tidak transparan */
    max-width: 100%; /* Gambar disesuaikan dengan lebar container */
    height: auto; /* Menjaga rasio gambar */
}
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
</div>

<!-- DataTables Example -->
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputKaryawan"><i class="bi bi-plus-lg"></i> Tambah Data Karyawan</a>
            </div>
        </div>

        {{-- form cari data karyawan --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.karyawan') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-2">
                            <div class="form-group">
                                <input type="text" name="nama_karyawan" id="cari_nama_karyawan" class="form-control" placeholder="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <select name="kode_jabatan" id="cari_kode_jabatan" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatan as $item)
                                    <option {{ Request('kode_jabatan') == $item->kode_jabatan ? 'selected' : '' }} value="{{ $item->kode_jabatan }}">{{ $item->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <select name="kode_departemen" id="cari_kode_departemen" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                    <option {{ Request('kode_departemen') == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <select name="kode_cabang" id="cari_kode_cabang" class="form-control">
                                    <option value="">Pilih Kantor Cabang</option>
                                    @foreach ($cabang as $item)
                                    <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <select name="kode_lokasi_penugasan" id="cari_kode_lokasi_penugasan" class="form-control">
                                    <option value="">Pilih Lokasi Penugasan</option>
                                    @foreach ($lokasi_penugasan as $item)
                                    <option {{ Request('kode_lokasi_penugasan') == $item->kode_lokasi_penugasan ? 'selected' : '' }} value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
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
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">NIK</th>
                                <th class="text-center">Nama Karyawan</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Departemen</th>
                                <th class="text-center">Lokasi Penugasan</th>
                                <th class="text-center">Kantor</th>
                                <th class="text-center">No HP</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $item)
                            @php
                                $path = Storage::url("uploads/karyawan/".$item->foto)
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
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
                                <td>{{ $item->jabatan->nama_jabatan }}</td>
                                <td>{{ $item->departemen->nama_departemen }}</td>
                                <td>{{ $item->lokasiPenugasan->nama_lokasi_penugasan ?? ''}}</td>
                                <td>{{ $item->cabang->nama_cabang }}</td>
                                <td>{{ $item->no_wa }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        {{-- <a href="{{ route('admin.karyawan.edit', Crypt::encrypt($item->nik)) }}" class="btn btn-warning" title="Edit"><i class="bi bi-pencil-square"></i></a> --}}

                                        <a href="#" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#modalEditKaryawan"
                                            data-nik="{{ $item->nik }}"
                                            data-nama_lengkap="{{ $item->nama_lengkap }}"
                                            data-foto="{{ $item->foto }}"
                                            data-kode_jabatan="{{ $item->kode_jabatan }}"
                                            {{-- data-password="{{ $item->password }}" --}}
                                            data-kode_departemen="{{ $item->kode_departemen }}"
                                            data-kode_lokasi_penugasan="{{ $item->kode_lokasi_penugasan }}"
                                            data-kode_cabang="{{ $item->kode_cabang }}"
                                            data-no_wa="{{ $item->no_wa }}"
                                        ><i class="bi bi-pencil-square"
                                        ></i></a>

                                        <a href="{{ route('admin.karyawan.setting', Crypt::encrypt($item->nik)) }}" class="btn btn-secondary" title="Setting"><i class="bi bi-gear"></i></a>
                                        <a href="{{ route('admin.karyawan.reset.password', Crypt::encrypt($item->nik)) }}" class="btn btn-primary" title="Reset Password"><i class="bi bi-key"></i></a>
                                        <a href="{{ route('admin.karyawan.delete', Crypt::encrypt($item->nik)) }}"
                                            class="btn btn-danger delete-confirm"
                                            title="Delete"
                                            data-nama="{{ $item->nama_lengkap }}"
                                            data-nik="{{ $item->nik }}"><i class="bi bi-trash3"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $karyawan->links('vendor.pagination.bootstrap-5') }} --}}
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
                    {{-- <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder=" Jabatan">
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <select name="kode_jabatan" id="kode_jabatan" class="form-control">
                            <option value="">Pilih jabatan</option>
                            @foreach ($jabatan as $item)
                            <option {{ Request('kode_jabatan') == $item->kode_jabatan ? 'selected' : '' }} value="{{ $item->kode_jabatan }}">{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
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
                    <div class="form-group">
                        <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control">
                            <option value="">Pilih Lokasi Penugasan</option>
                            @foreach ($lokasi_penugasan as $item)
                            <option {{ Request('kode_lokasi_penugasan') == $item->kode_lokasi_penugasan ? 'selected' : '' }} value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
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

<!-- Modal Edit Karyawan -->
<div class="modal fade" id="modalEditKaryawan" tabindex="-1" aria-labelledby="modalEditKaryawanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditKaryawanLabel">Edit Data Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.karyawan.update', ['nik' => 0]) }}" method="POST" id="formEditKaryawan" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="edit_nik" name="nik" placeholder=" NIK">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" placeholder=" Nama Lengkap">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="text" class="form-control" id="edit_no_wa" name="no_wa" placeholder=" Nomor HP">
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
                        <select name="kode_departemen" id="edit_kode_departemen" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemen as $item)
                            <option value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="kode_cabang" id="edit_kode_cabang" class="form-control">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                            <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="kode_lokasi_penugasan" id="edit_kode_lokasi_penugasan" class="form-control">
                            <option value="">Pilih Lokasi Penugasan</option>
                            @foreach ($lokasi_penugasan as $item)
                            <option value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="editfoto" name="foto" accept="image/*">
                            <label class="custom-file-label" for="edit_foto"  aria-describedby="inputGroupFileAddon02"><i class="bi bi-image"></i> Pilih Foto Karyawan</label>
                        </div>
                    </div>
                    <div class="preview-container">
                        <img id="edit_imgPreview" src="#" alt="Your Image" style="max-width: 100%; height: auto;">
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-bar-up"></i> Perbarui</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('cari_kode_cabang');
        const lokasiSelect = document.getElementById('cari_kode_lokasi_penugasan');

        cabangSelect.addEventListener('change', function() {
            const selectedCabang = this.value;

            // Tampilkan semua lokasi penugasan yang sesuai dengan cabang yang dipilih
            Array.from(lokasiSelect.options).forEach(option => {
                if (option.dataset.cabang === selectedCabang || selectedCabang === "") {
                    option.style.display = 'block'; // Tampilkan option
                } else {
                    option.style.display = 'none'; // Sembunyikan option
                }
            });

            // Reset pilihan lokasi penugasan jika tidak ada yang sesuai
            if (!Array.from(lokasiSelect.options).some(option => option.style.display === 'block')) {
                lokasiSelect.value = ""; // Reset
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('kode_cabang');
        const lokasiSelect = document.getElementById('kode_lokasi_penugasan');

        cabangSelect.addEventListener('change', function() {
            const selectedCabang = this.value;

            // Tampilkan semua lokasi penugasan yang sesuai dengan cabang yang dipilih
            Array.from(lokasiSelect.options).forEach(option => {
                if (option.dataset.cabang === selectedCabang || selectedCabang === "") {
                    option.style.display = 'block'; // Tampilkan option
                } else {
                    option.style.display = 'none'; // Sembunyikan option
                }
            });

            // Reset pilihan lokasi penugasan jika tidak ada yang sesuai
            if (!Array.from(lokasiSelect.options).some(option => option.style.display === 'block')) {
                lokasiSelect.value = ""; // Reset
            }
        });
    });
</script>

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

    $(function(){
        $("#nik").mask("000000000");
        $("#no_wa").mask("0000000000000");
    });

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
        var namaKaryawan = $(this).data('nama');
        var nik = $(this).data('nik');
        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data karyawan " + namaKaryawan + " dengan NIK " + nik + " Ini Akan Dihapus!",
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
        $("#formKaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var no_wa = $("#no_wa").val();
            var kode_jabatan = $("#kode_jabatan").val();
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
            } else if (kode_jabatan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jabatan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jabatan").focus();
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
            } else if (kode_lokasi_penugasan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Lokasi Penugasan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_lokasi_penugasan").focus();
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


        // Mengisi Data pada Modal Edit Karyawan
        $('#modalEditKaryawan').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var nik = button.data('nik');
            var nama_lengkap = button.data('nama_lengkap');
            var foto = button.data('foto');
            var kode_jabatan = button.data('kode_jabatan');
            var kode_departemen = button.data('kode_departemen');
            var kode_lokasi_penugasan = button.data('kode_lokasi_penugasan');
            var kode_cabang = button.data('kode_cabang');
            var no_wa = button.data('no_wa');
            // var password = button.data('password');

            var modal = $(this);
            modal.find('.modal-body #edit_nik').val(nik);
            modal.find('.modal-body #edit_nama_lengkap').val(nama_lengkap);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
            modal.find('.modal-body #edit_kode_departemen').val(kode_departemen);
            modal.find('.modal-body #edit_kode_lokasi_penugasan').val(kode_lokasi_penugasan);
            modal.find('.modal-body #edit_kode_cabang').val(kode_cabang);
            modal.find('.modal-body #edit_no_wa').val(no_wa);

            // Pratinjau gambar jika ada
            if (foto) {
                modal.find('.modal-body #edit_imgPreview').attr('src', '{{ Storage::url('uploads/karyawan/') }}' + foto);
            } else {
                modal.find('.modal-body #edit_imgPreview').attr('src', '#');
            }

            // Update URL form action dengan username yang sesuai
            var formAction = "{{ route('admin.karyawan.update', ['nik' => ':nik']) }}";
            formAction = formAction.replace(':nik', nik);
            $('#formEditKaryawan').attr('action', formAction);

            // Tambahkan filter lokasi penugasan
            filterLokasiPenugasan(kode_cabang);
        });

        // Fungsi filter lokasi penugasan
        function filterLokasiPenugasan(selectedCabang) {
            const lokasiSelect = $('#edit_kode_lokasi_penugasan');

            // Tampilkan semua lokasi penugasan yang sesuai dengan cabang yang dipilih
            lokasiSelect.find('option').each(function() {
                const option = $(this);
                if (option.data('cabang') == selectedCabang || selectedCabang === "") {
                    option.show();
                } else {
                    option.hide();
                }
            });

            // Reset pilihan lokasi penugasan jika tidak ada yang sesuai
            if (lokasiSelect.find('option:visible').length === 1) { // 1 karena option default
                lokasiSelect.val("");
            }
        }

        // Event listener untuk perubahan cabang
        $('#edit_kode_cabang').on('change', function() {
            const selectedCabang = $(this).val();
            filterLokasiPenugasan(selectedCabang);
        });

        // Pratinjau gambar saat mengupload file baru
        $('#edit_foto').change(function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                // Gantikan gambar lama dengan gambar baru yang diunggah
                $('#edit_imgPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
</script>


@endpush


@endsection
