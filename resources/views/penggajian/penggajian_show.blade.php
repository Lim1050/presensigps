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
    <h1 class="h3 mb-0 text-gray-800">Gaji Karyawan {{ $penggajian->karyawan->nama_lengkap }} Bulan {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('F Y') }}</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <tbody>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $penggajian->karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $penggajian->karyawan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Bulan</th>
                                <td>{{$penggajian->bulan }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Hari Dalam Bulan</th>
                                <td>{{$penggajian->jumlah_hari_dalam_bulan }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Masuk</th>
                                <td>{{$penggajian->jumlah_hari_masuk }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tidak Masuk</th>
                                <td>{{$penggajian->jumlah_hari_tidak_masuk }}</td>
                            </tr>
                            <tr>
                                <th>Gaji Tetap</th>
                                <td>Rp {{ number_format($penggajian->gaji_tetap, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Tunjangan Jabatan</th>
                                <td>Rp {{ number_format($penggajian->tunjangan_jabatan, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Uang Makan</th>
                                <td>Rp {{ number_format($penggajian->uang_makan, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Uang Transportasi</th>
                                <td>Rp {{ number_format($penggajian->transportasi, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Sub Total Gaji</th>
                                <td>Rp {{ number_format($penggajian->gaji, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Potongan = ((Uang Makan + Transportasi) / Jumlah Hari Dalam Bulan) x Jumlah Tidak Masuk</th>
                                <td>
                                    Rp {{ number_format($penggajian->potongan, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Total Gaji = Gaji - Potongan</th>
                                <td>Rp {{ number_format($penggajian->total_gaji, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Gaji</th>
                                <td>{{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($penggajian->diubah_oleh)
                    <div class="alert alert-info mt-3">
                        <strong>Informasi Perubahan:</strong><br>
                        Dirubah pada {{ \Carbon\Carbon::parse($penggajian->updated_at)->translatedFormat('d F Y H:i:s') }}
                        oleh {{ $penggajian->diubah_oleh }}<br>
                        Catatan: {{ $penggajian->catatan_perubahan }}
                    </div>
                    @endif
                    {{-- {{ $jabatan->links('vendor.pagination.bootstrap-5') }} --}}
                    <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
                    <a href="{{ route('admin.penggajian.export', $penggajian->id) }}" class="btn btn-primary">Export PDF</a>
                </div>
            </div>
    </div>
</div>

@push('myscript')

@endpush

@endsection
