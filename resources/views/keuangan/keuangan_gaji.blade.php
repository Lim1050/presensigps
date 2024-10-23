@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Gaji</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="container" style="margin-top: 70px; margin-bottom: 70px">
    <div class="row">
        @if ($gaji->isEmpty())
            <div class="text-center">
                <ion-icon name="alert-circle" style="font-size: 50px; color: gray;"></ion-icon>
                <h5>Tidak Ada Data</h5>
                <p>Belum ada data gaji yang tersedia.</p>
            </div>
        @else
            <div class="row">
                @foreach ($gaji as $item)
                    <div class="col-md-4 mb-1">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ \Carbon\Carbon::parse($item->tanggal_gaji)->translatedFormat('F Y') }}</h5>
                                <p class="card-text">Gaji: Rp {{ number_format($item->total_gaji, 2) }}</p>
                                <a href="#" class="btn btn-secondary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('myscript')

@endpush
