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

    #dari, #sampai > span:hover{
        cursor: pointer;
        }
</style>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Cashbon {{ $cashbon->kode_cashbon }} {{ $cashbon->created_at->format('d-m-Y H:i') }}</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col">
        <div class="card">
            {{-- <div class="card-header">

            </div> --}}
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $cashbon->karyawan->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td>{{ $cashbon->karyawan->nik }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>{{ $cashbon->karyawan->jabatan->nama_jabatan }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi Penugasan</th>
                            <td>{{ $cashbon->karyawan->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                        </tr>
                        <tr>
                            <th>Kantor Cabang</th>
                            <td>{{ $cashbon->karyawan->Cabang->nama_cabang }}</td>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <td>{{ $cashbon->id }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($cashbon->status == 'diterima')
                                    <span class="badge badge-success">Diterima</span>
                                @elseif ($cashbon->status == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>Rp {{ number_format($cashbon->jumlah, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $cashbon->keterangan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>{{ $cashbon->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Tambahkan informasi lain yang relevan sesuai kebutuhan -->
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.cashbon') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>



@endsection
@push('myscript')

@endpush
