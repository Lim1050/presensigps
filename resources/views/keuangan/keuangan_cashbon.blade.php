@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Cashbon</div>
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
{{-- {{ route('karyawan.cashbons.create') }} --}}
    <a href="{{ route('keuangan.cashbon.create') }}" class="btn btn-danger">Ajukan Cashbon</a>

    <div class="container mt-3">
        <div class="row">
            @if ($cashbon->isEmpty())
                <div class="text-center">
                    <ion-icon name="alert-circle" style="font-size: 50px; color: gray;"></ion-icon>
                    <h5>Tidak Ada Data</h5>
                    <p>Belum ada pengajuan cashbon yang diajukan.</p>
                </div>
            @else
                <div class="row">
                    @foreach ($cashbon as $item)
                        <div class="col-md-4 mb-1">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Tanggal Pengajuan: {{ $item->tanggal_pengajuan }}</h6>
                                    <p class="card-text">Jumlah: Rp {{ number_format($item->jumlah, 2) }}</p>
                                    <p class="card-text">
                                        Status:
                                        @if ($item->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($item->status == 'approved')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif ($item->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
                                        @endif
                                    </p>
                                    <a href="" class="btn btn-secondary btn-sm">
                                        <ion-icon name="eye"></ion-icon> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('myscript')

@endpush
