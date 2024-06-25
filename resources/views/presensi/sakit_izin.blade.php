@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
</div>
{{-- App Header --}}
@endsection
@section('content')
<div class="row">
    <div class="col" style="margin-top: 70px">
        @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">{{ $messagesuccess }}</div>
        @elseif (Session::get('error'))
            <div class="alert alert-error">{{ $messageerror }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col">
        @foreach ($dataIzin as $izin)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <b>{{ date("d-m-Y", strtotime($izin->tanggal_izin)) }} ({{ $izin->status == "sakit" ? "Sakit" : "Izin" }})</b>  <br>
                            <small class="text-muted">{{ $izin->keterangan}}</small>
                        </div>
                        <span class="badge {{ $izin->status_approved == "1" ? "badge-success" : ($izin->status_approved == "2" ? "badge-danger" : "badge-warning")}}">{{ $izin->status_approved == "1" ? "Aproved" : ($izin->status_approved == "2" ? "Rejected" : "Waiting") }}</span>
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="{{ route('presensi.create.sakit-izin') }}" class="fab bg-danger"><ion-icon name="add-outline"></ion-icon></a>
</div>
@endsection
