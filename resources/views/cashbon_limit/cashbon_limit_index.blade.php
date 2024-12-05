@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('konfigurasi.limit-cashbon'))
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

    #dari, #sampai > span:hover{
        cursor: pointer;
        }
</style>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    @if (Auth::user()->role == 'admin-cabang')
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Cashbon Limit Cabang {{ $nama_cabang->nama_cabang}}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Cashbon Limit</h1>
    @endif
    {{-- <h1 class="h3 mb-0 text-gray-800">Konfigurasi Cashbon Limit</h1> --}}
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
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
                @if (Auth::user()->role == 'super-admin')
                <h2>Global Limit</h2>
                <form action="{{ route('admin.konfigurasi.cashbon.global.limit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="global_limit ">Global Limit</label>
                        <input type="number" class="form-control" id="global_limit" name="global_limit" value="{{ $globalLimit }}">
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="update_all" id="update_all" value="1">
                        <label class="form-check-label" for="update_all">
                            Update all personal limits to match the new global limit
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger">Update Global Limit</button>
                </form>
                @endif

                {{-- form cari data cashbon limit --}}
                <div class="row mt-3">
                    <div class="col-12">
                        <form action="{{ route('admin.konfigurasi.cashbon.limit') }}" method="GET">
                            <div class="row mt-2">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" name="nama_karyawan" id="nama_karyawan_cari" class="form-control" placeholder="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <select name="kode_jabatan" id="kode_jabatan_cari" class="form-control">
                                            <option value="">Pilih Jabatan</option>
                                            @foreach ($jabatan as $item)
                                            <option {{ Request('kode_jabatan') == $item->kode_jabatan ? 'selected' : '' }} value="{{ $item->kode_jabatan  }}">{{ $item->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <select name="kode_cabang" id="kode_cabang_cari" class="form-control">
                                            <option value="">Pilih Kantor Cabang</option>
                                            @foreach ($cabang as $item)
                                            <option {{ Request('kode_cabang') == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <select name="kode_lokasi_penugasan" id="kode_lokasi_penugasan_cari" class="form-control">
                                            <option value="">Pilih Lokasi Penugasan</option>
                                            @foreach ($lokasi_penugasan as $item)
                                            <option {{ Request('kode_lokasi_penugasan') == $item->kode_lokasi_penugasan ? 'selected' : '' }} value="{{ $item->kode_lokasi_penugasan }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lokasi_penugasan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <h2 class="mt-2">Limit Karyawan Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h2>
                <div class="table-responsive">
                <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Lengkap</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Lokasi Penugasan</th>
                            <th class="text-center">Kantor Cabang</th>
                            <th class="text-center">Limit</th>
                            <th class="text-center">Terpakai</th>
                            <th class="text-center">Sisa</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($karyawan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->nama_lengkap }}</td>
                            <td class="text-center">{{ $item->jabatan->nama_jabatan }}</td>
                            <td class="text-center">{{ $item->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                            <td class="text-center">{{ $item->cabang->nama_cabang }}</td>
                            <td class="text-right">Rp {{ number_format($item->cashbonKaryawanLimit->limit ?? $globalLimit) }}</td>
                            {{-- Gunakan cashbon_bulan_ini yang sudah difilter --}}
                            <td class="text-right">Rp {{ number_format($item->cashbon_bulan_ini) }}</td>
                            <td class="text-right">{{ $item->getFormattedAvailableCashbonLimit($globalLimit) }}</td>
                            <td class="text-right">
                                {{-- {{ route('cashbon.setitemLimit', $item) }} --}}
                                <form action="{{ route('admin.konfigurasi.cashbon.karyawan.limit', $item->nik) }}" method="POST">
                                    @csrf
                                    {{-- {{ $item->cashbonLimit->limit ?? '' }} --}}
                                    <input type="number" name="limit" value="{{ $item->cashbonKaryawanLimit->limit ?? $globalLimit }}">
                                    <button type="submit" class="btn btn-danger my-2">Update</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endif
@endsection
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
    let table = new DataTable('#dataTable');
</script>
@endpush
