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
    <h1 class="h3 mb-0 text-gray-800">Detail Lembur</h1>
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
                                <td>{{ $lembur->karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $lembur->karyawan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $lembur->karyawan->jabatan->nama_jabatan }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Penugasan</th>
                                <td>{{ $lembur->karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                            </tr>
                            <tr>
                                <th>Kantor Cabang</th>
                                <td>{{ $lembur->karyawan->Cabang->nama_cabang }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Presensi</th>
                                <td>{{$lembur->tanggal_presensi }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Mulai</th>
                                <td>{{$lembur->waktu_mulai }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Selesai</th>
                                <td>{{$lembur->waktu_selesai }}</td>
                            </tr>
                            <tr>
                                <th>Durasi Lembur</th>
                                <td>{{$lembur->durasi_menit }} menit</td>
                            </tr>
                            <tr>
                                <th>Lintas Hari</th>
                                <td>
                                    @if ($lembur->lintas_hari == 1)
                                        <span class="badge badge-success">Ya</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Lembur Saat Tidak Ada Jadwal</th>
                                <td>
                                    @if ($lembur->lembur_libur == 1)
                                        <span class="badge badge-success">Ya</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($lembur->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($lembur->status == 'disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif ($lembur->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Diketahui</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{$lembur->catatan_lembur }}</td>
                            </tr>
                            @if($lembur->alasan_penolakan != null)
                            <tr>
                                <th>Alasan Penolakan</th>
                                <td>{{$lembur->alasan_penolakan }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <a href="{{ route('admin.lembur') }}" class="btn btn-danger">Kembali</a>
                </div>
            </div>
    </div>
</div>

@push('myscript')

@endpush

@endsection
