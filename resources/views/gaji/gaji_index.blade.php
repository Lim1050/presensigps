@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('master.gaji'))

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
    <h1 class="h3 mb-0 text-gray-800">Data Gaji Cabang {{ $nama_cabang->nama_cabang}}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Data Gaji</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputGaji"><i class="bi bi-plus-lg"></i> Tambah Data Gaji</a>
            </div>
        </div>

        {{-- form cari data Gaji --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.gaji') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" name="nama_gaji" id="nama_gaji_cari" class="form-control" placeholder="Cari Nama Gaji" value="{{ Request('nama_gaji') }}">
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
                                <select name="kode_jenis_gaji" id="kode_jenis_gaji_cari" class="form-control">
                                    <option value="">Pilih Jenis Gaji</option>
                                    @foreach ($jenis_gaji as $item)
                                    <option {{ Request('kode_jenis_gaji') == $item->kode_jenis_gaji ? 'selected' : '' }} value="{{ $item->kode_jenis_gaji  }}">{{ $item->jenis_gaji }}</option>
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
                                <th class="text-center">Kode Gaji</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Lokasi Penugasan</th>
                                <th class="text-center">Cabang</th>
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
                                <td class="text-center">{{ $item->lokasiPenugasan->nama_lokasi_penugasan ?? ''}}</td>
                                <td class="text-center">{{ $item->kantorCabang->nama_cabang ?? ''}}</td>
                                <td class="text-center">{{ $item->jenisGaji->jenis_gaji }}</td>
                                <td class="text-center">{{ $item->nama_gaji }}</td>
                                <td class="text-right">Rp {{ $item->jumlah_gaji }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        {{-- <a href="{{ route('admin.jabatan.edit', $item->kode_jabatan) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Edit</a> --}}
                                        <a href="#" class="btn btn-warning" title="Edit" data-toggle="modal" data-target="#modalEditGaji"
                                                data-kode_gaji="{{ $item->kode_gaji }}"
                                                data-kode_jabatan="{{ $item->kode_jabatan }}"
                                                data-kode_lokasi_penugasan="{{ $item->kode_lokasi_penugasan }}"
                                                data-kode_cabang="{{ $item->kode_cabang }}"
                                                data-kode_jenis_gaji="{{ $item->kode_jenis_gaji }}"
                                                data-nama_gaji="{{ $item->nama_gaji }}"
                                                data-jumlah_gaji="{{ $item->jumlah_gaji }}"
                                                >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <a href="{{ route('admin.gaji.delete', $item->kode_gaji) }}"
                                            class="btn btn-danger delete-confirm"
                                            title="Delete"
                                            data-nama="{{ $item->nama_gaji }}"
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
        text: "Data Gaji " + nama + ", dengan jabatan " + jabatan + ", lokasi penugasan " + lokasi_penugasan + ", kantor cabang " + cabang + " akan dihapus permanen!",
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
            var kode_lokasi_penugasan = button.data('kode_lokasi_penugasan');
            var kode_cabang = button.data('kode_cabang');
            var kode_jenis_gaji = button.data('kode_jenis_gaji');
            var nama_gaji = button.data('nama_gaji');
            var jumlah_gaji = button.data('jumlah_gaji');

            var modal = $(this);
            modal.find('.modal-body #edit_kode_gaji').val(kode_gaji);
            modal.find('.modal-body #edit_kode_jabatan').val(kode_jabatan);
            modal.find('.modal-body #edit_kode_lokasi_penugasan').val(kode_lokasi_penugasan);
            modal.find('.modal-body #edit_kode_cabang').val(kode_cabang);
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
@endif

@endsection
