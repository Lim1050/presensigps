@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit / Cuti</div>
    <div class="right"></div>
</div>
{{-- App Header --}}
@endsection
@section('content')
<div class="row">
    <div class="col" style="margin-top: 70px;">
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
<div class="tab-content" style="margin-bottom:100px;">
    <div class="row">
        <div class="col">
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
                }
            </style>
            @foreach ($dataIzin as $izin)
            <div class="card mb-1" style="display: flex">
                <div class="card-body">
                    <div class="historycontent">
                        <div class="iconpresensi">
                            @if ($izin->status == "izin")
                            <ion-icon style="font-size: 40px; color:rgb(21,95,207) !important;" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                            @elseif ($izin->status == "sakit")
                            <ion-icon style="font-size: 40px; color:rgb(191,7,65) !important;" name="medkit-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                            @elseif ($izin->status == "cuti")
                            <ion-icon style="font-size: 40px; color:rgb(1, 139, 31) !important;" name="calendar-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                            @endif

                        </div>
                        <div class="datapresensi">
                            <h3 style="line-height: 3px">{{ date("d-m-Y",strtotime($izin->tanggal_izin_dari)) }} ({{ $izin->status == "izin" ? "Izin" : ($izin->status == "sakit" ? "Sakit" : "Cuti")}})</h3>
                            <small>{{ date("d-m-Y",strtotime($izin->tanggal_izin_dari)) }} - {{ date("d-m-Y",strtotime($izin->tanggal_izin_sampai)) }} ({{ $izin->jumlah_hari }}) </small>

                            <h4 >{{ $izin->keterangan }}</h4>

                                @if ($izin->status == "cuti")
                                <p class="text-success"><ion-icon name="calendar-outline"></ion-icon> {{ $izin->nama_cuti }}</p>
                                @endif

                                @if (!empty($izin->surat_sakit))
                                    <a href="#" class="text-danger"><ion-icon name="document-attach-outline"></ion-icon> Lihat Surat Sakit</a>
                                @endif

                        </div>
                        <div class="status">
                            <span class="{{ $izin->status_approved == "1" ? "text-success" : ($izin->status_approved == "2" ? "text-danger" : "text-warning")}}">{{ $izin->status_approved == "1" ? "Aproved" : ($izin->status_approved == "2" ? "Rejected" : "Waiting") }}</span>
                            {{-- <p>{{ hitunghari($izin->tanggal_izin_dari, $izin->tanggal_izin_sampai) }} Hari</p> --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <ul class="listview image-listview">
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <b>{{ date("d-m-Y", strtotime($izin->tanggal_izin_dari)) }} ({{ $izin->status == "sakit" ? "Sakit" : "Izin" }})</b>  <br>
                                <small class="text-muted">{{ $izin->keterangan}}</small>
                            </div>
                            <span class="badge {{ $izin->status_approved == "1" ? "badge-success" : ($izin->status_approved == "2" ? "badge-danger" : "badge-warning")}}">{{ $izin->status_approved == "1" ? "Aproved" : ($izin->status_approved == "2" ? "Rejected" : "Waiting") }}</span>
                        </div>
                    </div>
                </li>
            </ul> --}}
            @endforeach
        </div>
    </div>
</div>

<div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
    <a href="#" class="fab bg-danger" data-toggle="dropdown">
        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">

        <a href="{{ route('izin.absen') }}" class="dropdown-item bg-danger">
            <ion-icon name="reader-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Izin Absen</p>
        </a>

        <a href="{{ route('izin.sakit') }}" class="dropdown-item bg-danger">
            <ion-icon name="medkit-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
            <p>Izin Sakit</p>
        </a>

        <a href="{{ route('izin.cuti') }}" class="dropdown-item bg-danger">
            <ion-icon name="calendar-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
            <p>Izin Cuti</p>
        </a>
    </div>
</div>

{{-- <div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="{{ route('presensi.create.sakit-izin') }}" class="fab bg-danger"><ion-icon name="add-outline"></ion-icon></a>
</div> --}}
@endsection
