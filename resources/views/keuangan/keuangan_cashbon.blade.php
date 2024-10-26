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
    .card-hover {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-hover:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .card-hover:active {
        transform: scale(0.98);
        box-shadow: none;
    }
</style>
<div class="container" style="margin-top: 70px; margin-bottom: 70px">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('keuangan.cashbon.create') }}" class="btn btn-danger mb-1">Ajukan Cashbon</a>

    <form action="{{ route('keuangan.cashbon') }}" method="GET">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control selectmaterialize">
                        <option value="">Pilih Bulan</option>
                        <option value="01" {{ request('bulan') == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ request('bulan') == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ request('bulan') == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ request('bulan') == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ request('bulan') == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ request('bulan') == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ request('bulan') == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control selectmaterialize">
                        <option value="">Tahun</option>
                        @php
                            $currentYear = date('Y');
                            $startYear = $currentYear - 5;
                        @endphp
                        @for ($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <a href="{{ route('keuangan.cashbon') }}" class="btn btn-secondary w-100">
                        <ion-icon name="refresh-outline"></ion-icon> Reset
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <button class="btn btn-danger btn-block"><ion-icon name="search-outline"></ion-icon>Cari</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        @if ($cashbon->isEmpty())
        <div class="col-12">
            <div class="text-center">
                <ion-icon name="alert-circle" style="font-size: 50px; color: gray;"></ion-icon>
                <h5>Tidak Ada Data</h5>
                <p>Belum ada pengajuan cashbon yang diajukan.</p>
            </div>
        </div>
        @else
        <div class="col">
            @foreach ($cashbon as $item)
            <a href="{{ route('keuangan.cashbon.show', $item->id) }}" class="text-decoration-none text-dark">
            {{-- onclick="window.location='{{ route('keuangan.cashbon.show', $item->id) }}'" --}}
                <div class="card mb-1 card_izin card-hover" >
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
@endsection

@push('myscript')

@endpush
