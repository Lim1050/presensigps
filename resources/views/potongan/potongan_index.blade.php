@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('master.potongan'))
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
    @if (Auth::user()->role == 'admin-cabang')
    <h1 class="h3 mb-0 text-gray-800">Data Potongan Cabang {{ $nama_cabang->nama_cabang}}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Data Potongan</h1>
    @endif
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputPotongan"><i class="bi bi-plus-lg"></i> Tambah Data Potongan</a>
            </div>
        </div>

        {{-- form cari data Potongan --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.potongan') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="nama_potongan" id="nama_potongan_cari" class="form-control" placeholder="Cari Nama Potongan" value="{{ Request('nama_potongan') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_jabatan" id="kode_jabatan_cari" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($jabatan as $item)
                                    <option {{ Request('kode_jabatan') == $item->kode_jabatan ? 'selected' : '' }} value="{{ $item->kode_jabatan  }}">{{ $item->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_jenis_potongan" id="kode_jenis_potongan_cari" class="form-control">
                                    <option value="">Pilih Jenis Potongan</option>
                                    @foreach ($jenis_potongan as $item)
                                    <option {{ Request('kode_jenis_potongan') == $item->kode_jenis_potongan ? 'selected' : '' }} value="{{ $item->kode_jenis_potongan  }}">{{ $item->jenis_potongan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang_cari" class="form-control">
                                    <option value="">Pilih Kantor Cabang</option>
                                    @foreach ($cabang as $item)
                                    <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan_cari" class="form-control">
                                    <option value="">Pilih Lokasi Penugasan</option>
                                    @foreach ($lokasi_penugasan as $item)
                                    <option {{ Request('kode_lokasi_penugasan') == $item->kode_lokasi_penugasan ? 'selected' : '' }} value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
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
                                <th class="text-center">Kode Potongan</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Lokasi Penugasan</th>
                                <th class="text-center">Cabang</th>
                                <th class="text-center">Jenis Potongan</th>
                                <th class="text-center">Nama Potongan</th>
                                <th class="text-right">Jumlah Potongan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($potongan as $item)

                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td class="text-center">{{ $item->kode_potongan }}</td>
                                <td class="text-center">{{ $item->jabatan->nama_jabatan }}</td>
                                <td class="text-center">{{ $item->lokasiPenugasan->nama_lokasi_penugasan ?? ''}}</td>
                                <td class="text-center">{{ $item->kantorCabang->nama_cabang ?? ''}}</td>
                                <td class="text-center">{{ $item->jenisPotongan->jenis_potongan }}</td>
                                <td class="text-center">{{ $item->nama_potongan }}</td>
                                <td class="text-right">Rp {{ number_format($item->jumlah_potongan, 2) }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        {{-- <a href="{{ route('admin.jabatan.edit', $item->kode_jabatan) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a> --}}
                                        <a href="#" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#modalEditPotongan"
                                                data-kode_potongan="{{ $item->kode_potongan }}"
                                                data-kode_jabatan="{{ $item->kode_jabatan }}"
                                                data-kode_lokasi_penugasan="{{ $item->kode_lokasi_penugasan }}"
                                                data-kode_cabang="{{ $item->kode_cabang }}"
                                                data-kode_jenis_potongan="{{ $item->kode_jenis_potongan }}"
                                                data-nama_potongan="{{ $item->nama_potongan }}"
                                                data-jumlah_potongan="{{ $item->jumlah_potongan }}"
                                                >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <a href="{{ route('admin.potongan.delete', $item->kode_potongan) }}"
                                            class="btn btn-danger delete-confirm"
                                            title="Delete"
                                            data-nama="{{ $item->nama_potongan }}"
                                            data-jabatan="{{ $item->jabatan->nama_jabatan }}"
                                            data-lokasi_penugasan="{{ $item->lokasiPenugasan->nama_lokasi_penugasan }}"
                                            data-cabang="{{ $item->kantorCabang->nama_cabang }}"
                                            ><i class="bi bi-trash3"></i></a>
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

<!-- Modal Input Potongan -->
<div class="modal fade" id="modalInputPotongan" tabindex="-1" aria-labelledby="modalInputPotonganLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputPotonganLabel">Input Data Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.potongan.store') }}" method="POST" id="formPotongan" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="kode_potongan" name="kode_potongan" placeholder=" Kode Potongan">
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
                        <select name="kode_cabang" id="kode_cabang" class="form-control">
                            <option value="">Pilih Kantor Cabang</option>
                            @foreach ($cabang as $item)
                            <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan" class="form-control">
                            <option value="">Pilih Lokasi Penugasan</option>
                            @foreach ($lokasi_penugasan as $item)
                            <option value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="kode_jenis_potongan" id="kode_jenis_potongan" class="form-control">
                            <option value="">Pilih Jenis Potongan</option>
                            @foreach ($jenis_potongan as $item)
                            <option value="{{ $item->kode_jenis_potongan  }}">{{ $item->jenis_potongan }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <select name="jenis_potongan" id="jenis_potongan" class="form-control">
                            <option value="">Pilih Jenis Gaji</option>
                            <option value="Gaji tetap">Gaji tetap</option>
                            <option value="Tunjangan jabatan">Tunjangan jabatan</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash"></i>
                            <input type="text" class="form-control" id="nama_potongan" name="nama_potongan" placeholder=" Nama Potongan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-cash-stack"></i>
                            <input type="number" class="form-control" id="jumlah_potongan" name="jumlah_potongan" placeholder=" Jumlah Potongan">
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

<!-- Modal Edit Potongan -->
<div class="modal fade" id="modalEditPotongan" tabindex="-1" aria-labelledby="modalEditPotonganLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPotonganLabel">Edit Data Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.potongan.update', ['kode_potongan' => 0]) }}" method="POST" id="formPotongan" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-upc-scan"></i>
                            <input type="text" class="form-control" id="edit_kode_potongan" name="kode_potongan" placeholder=" Kode Potongan" readonly>
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
                        <select name="kode_cabang" id="edit_kode_cabang" class="form-control">
                            <option value="">Pilih Kantor Cabang</option>
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
                    <div class="form-group">
                        <select name="kode_jenis_potongan" id="edit_kode_jenis_potongan" class="form-control">
                            <option value="">Pilih Jenis Potongan</option>
                            @foreach ($jenis_potongan as $item)
                            <option value="{{ $item->kode_jenis_potongan  }}">{{ $item->jenis_potongan }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <select name="jenis_potongan" id="edit_jenis_potongan" class="form-control">
                            <option value="">Pilih Jenis Potongan</option>
                            <option value="Potongan tetap">Potongan tetap</option>
                            <option value="Tunjangan jabatan">Tunjangan jabatan</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="text" class="form-control" id="edit_nama_potongan" name="nama_potongan" placeholder=" Nama Potongan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-vcard"></i>
                            <input type="number" class="form-control" id="edit_jumlah_potongan" name="jumlah_potongan" placeholder=" Jumlah Potongan">
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
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('kode_cabang_cari');
        const lokasiSelect = document.getElementById('kode_lokasi_penugasan_cari');

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
$(document).ready(function() {
    // Ketika cabang dipilih
    $('#kode_cabang').change(function() {
        var selectedCabang = $(this).val();
        if(selectedCabang) {
            $('#kode_lokasi_penugasan option').each(function() {
                if($(this).data('cabang') == selectedCabang || $(this).val() == '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('#kode_lokasi_penugasan option').show();
        }
        $('#kode_lokasi_penugasan').val('');
    });

    // Ketika lokasi penugasan dipilih
    $('#kode_lokasi_penugasan').change(function() {
        var selectedCabang = $(this).find(':selected').data('cabang');
        if(selectedCabang) {
            $('#kode_cabang').val(selectedCabang);
        }
    });

    // Inisialisasi: sembunyikan lokasi penugasan yang tidak sesuai dengan cabang yang dipilih
    $('#kode_cabang').trigger('change');
});
</script>

<script>
$(document).ready(function() {
    // Fungsi untuk memperbarui opsi lokasi penugasan berdasarkan cabang yang dipilih
    function updateLokasiPenugasan() {
        var selectedCabang = $('#edit_kode_cabang').val();
        if(selectedCabang) {
            $('#edit_kode_lokasi_penugasan option').each(function() {
                if($(this).data('cabang') == selectedCabang || $(this).val() == '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('#edit_kode_lokasi_penugasan option').show();
        }
    }

    // Ketika cabang dipilih
    $('#edit_kode_cabang').change(function() {
        updateLokasiPenugasan();
        $('#edit_kode_lokasi_penugasan').val('');
    });

    // Ketika lokasi penugasan dipilih
    $('#edit_kode_lokasi_penugasan').change(function() {
        var selectedCabang = $(this).find(':selected').data('cabang');
        if(selectedCabang) {
            $('#edit_kode_cabang').val(selectedCabang);
        }
    });

    // Inisialisasi: perbarui lokasi penugasan berdasarkan cabang yang mungkin sudah terpilih
    updateLokasiPenugasan();

    // Fungsi untuk mengatur nilai awal saat modal edit dibuka
    window.setEditFormValues = function(kodeCabang, kodeLokasiPenugasan) {
        $('#edit_kode_cabang').val(kodeCabang);
        updateLokasiPenugasan();
        $('#edit_kode_lokasi_penugasan').val(kodeLokasiPenugasan);
    }
});
</script>

<script>
    let table = new DataTable('#dataTable');

    $(".delete-confirm").click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        var nama = $(this).data('nama');
        var jabatan = $(this).data('jabatan');
        var lokasi_penugasan = $(this).data('lokasi_penugasan');
        var cabang = $(this).data('cabang');

        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data Potongan " + nama + ", dengan jabatan " + jabatan + ", lokasi penugasan " + lokasi_penugasan + ", kantor cabang " + cabang + " akan dihapus permanen!",
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
        $("#formPotongan").submit(function(){
            var kode_potongan = $("#kode_potongan").val();
            var kode_jabatan = $("#kode_jabatan").val();
            var kode_jenis_potongan = $("#kode_jenis_potongan").val();
            var nama_potongan = $("#nama_potongan").val();
            var jumlah_potongan = $("#jumlah_potongan").val();

            if(kode_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_potongan").focus();
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
            }else if (kode_lokasi_penugasan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Lokasi Penugasan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_lokasi_penugasan").focus();
                });
                return false;
            }else if (kode_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kantor Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cabang").focus();
                });
                return false;
            } else if (kode_jenis_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jenis Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_jenis_potongan").focus();
                });
                return false;
            }else if (nama_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_potongan").focus();
                });
                return false;
            } else if (jumlah_potongan==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Jumlah Potongan Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jumlah_potongan").focus();
                });
                return false;
            }
        });

        // Mengisi Data pada Modal Edit Potongan
        $('#modalEditPotongan').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var kode_potongan = button.data('kode_potongan');
            var kode_jabatan = button.data('kode_jabatan');
            var kode_lokasi_penugasan = button.data('kode_lokasi_penugasan');
            var kode_cabang = button.data('kode_cabang');
            var kode_jenis_potongan = button.data('kode_jenis_potongan');
            var nama_potongan = button.data('nama_potongan');
            var jumlah_potongan = button.data('jumlah_potongan');

            var modal = $(this);
            modal.find('.modal-body #edit_kode_potongan').val(kode_potongan);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
            modal.find('.modal-body #edit_kode_lokasi_penugasan').val(kode_lokasi_penugasan);
            modal.find('.modal-body #edit_kode_cabang').val(kode_cabang);
            modal.find('.modal-body #edit_kode_jenis_potongan').val(kode_jenis_potongan);
            modal.find('.modal-body #edit_nama_potongan').val(nama_potongan);
            modal.find('.modal-body #edit_jumlah_potongan').val(jumlah_potongan);

            // Update URL form action dengan username yang sesuai
            var formAction = "{{ route('admin.potongan.update', ['kode_potongan' => ':kode_potongan']) }}";
            formAction = formAction.replace(':kode_potongan', kode_potongan);
            $('#formEditPotongan').attr('action', formAction);
        });
</script>


@endpush

@endif
@endsection
