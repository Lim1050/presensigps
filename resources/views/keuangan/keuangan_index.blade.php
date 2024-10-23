@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Keuangan</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<style>
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
<div class="container" style="margin-top: 70px">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-3 card-hover">
                <a href="{{ route('keuangan.gaji') }}" class="text-decoration-none text-dark">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <ion-icon name="cash-outline" style="font-size: 24px; margin-right: 10px;"></ion-icon>
                            <span class="h5">Gaji</span>
                        </div>
                        <ion-icon name="chevron-forward-outline" style="font-size: 24px;"></ion-icon>
                    </div>
                </a>
            </div>
            <div class="card card-hover">
                <a href="{{ route('keuangan.cashbon') }}" class="text-decoration-none text-dark">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <ion-icon name="cash" style="font-size: 24px; margin-right: 10px;"></ion-icon>
                            <span class="h5">Cashbon</span>
                        </div>
                        <ion-icon name="chevron-forward-outline" style="font-size: 24px;"></ion-icon>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')

@endpush
