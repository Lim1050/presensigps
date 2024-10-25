@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan.cashbon') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Cashbon {{ $cashbon->kode_cashbon }}</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="container" style="margin-top: 70px; margin-bottom: 70px">
    {{-- <h1>Daftar Pengajuan Cashbon</h1> --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

</div>
@if ($cashbon)
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
                    <a href="{{ route('keuangan.cashbon') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@else
    <p>Cashbon tidak ditemukan.</p>
@endif
@endsection

@push('myscript')

@endpush
