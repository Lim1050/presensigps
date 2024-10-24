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

                    <style>
                        .historycontent{
                            display: flex;
                        }
                        .datapresensi{
                            margin-left: 10px;
                        }
                        .status {
                            position: absolute;
                            right: 20px;
                            bottom: 20px;
                        }
                        .fixed-top {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            z-index: 1030; /* z-index tinggi untuk memastikan di atas elemen lainnya */
                            background-color: white; /* Pastikan background-color diatur agar tidak transparan */
                            padding: 10px; /* Tambahkan padding untuk memberikan sedikit ruang */
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Tambahkan shadow untuk efek visual */
                        }
                    </style>
                    <div class="col">
                        @foreach ($cashbon as $item)
                        <a href="{{ route('keuangan.cashbon.show', $item->id) }}">
                        {{-- onclick="window.location='{{ route('keuangan.cashbon.show', $item->id) }}'" --}}
                        <div class="card mb-1 card_izin" >
                            <div class="card-body">
                                <div class="historycontent">
                                    <div class="datapresensi">
                                        <h3 style="line-height: 3px">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</h3>
                                        <small>{{ $item->keterangan }}</small>

                                        <h4>Rp {{ number_format($item->jumlah, 2) }}</h4>
                                    </div>
                                    <div class="status">
                                        @if ($item->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($item->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @elseif ($item->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                        @endforeach
                    </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('myscript')

@endpush
