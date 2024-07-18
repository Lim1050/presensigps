@extends('layouts.master')
@section('header')
<!-- App Header -->
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->

@endsection

@section('content')
<div class="row" style="margin-top: 60px">
    <div class="col">
        <div class="alert alert-warning">
            <p>
                Maaf, Anda Tidak Memiliki Jadwal Pada Hari Ini, Silahkan Hubungi HRD!
            </p>
        </div>
    </div>
</div>

@endsection

