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
    <h1 class="h3 mb-0 text-gray-800">{{ $thr->kode_thr }} {{ $thr->karyawan->nama_lengkap }} {{$thr->nama_thr }} {{$thr->tahun }}</h1>
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
                                <th>Kode THR</th>
                                <td>{{ $thr->kode_thr }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $thr->karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $thr->karyawan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $thr->jabatan->nama_jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Penugasan</th>
                                <td>{{ $thr->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                            </tr>
                            <tr>
                                <th>Kantor Cabang</th>
                                <td>{{ $thr->kantorCabang->nama_cabang }}</td>
                            </tr>
                            <tr>
                                <th>Nama THR</th>
                                <td>{{$thr->nama_thr }}</td>
                            </tr>
                            <tr>
                                <th>Tahun</th>
                                <td>{{$thr->tahun }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>Rp {{ number_format($thr->jumlah_thr, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Penyerahan</th>
                                <td>{{ \Carbon\Carbon::parse($thr->tanggal_penyerahan)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($thr->status == 'Pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($thr->status == 'Disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($thr->status == 'Ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Diketahui</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{$thr->notes }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($thr->diubah_oleh)
                    <div class="alert alert-info mt-3">
                        <strong>Informasi Perubahan:</strong><br>
                        Dirubah pada {{ \Carbon\Carbon::parse($thr->updated_at)->translatedFormat('d F Y H:i:s') }}
                        oleh {{ $thr->diubah_oleh }}<br>
                        Catatan: {{ $thr->catatan_perubahan }}
                    </div>
                    @endif
                    {{-- {{ $jabatan->links('vendor.pagination.bootstrap-5') }} --}}
                    <a href="{{ route('admin.thr') }}" class="btn btn-danger">Kembali</a>
                    <a href="{{ route('admin.thr.export', $thr->kode_thr) }}" class="btn btn-primary">Export PDF</a>
                </div>
            </div>
    </div>
</div>

@push('myscript')

@endpush

@endsection
