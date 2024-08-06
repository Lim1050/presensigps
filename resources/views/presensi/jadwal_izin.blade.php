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
    <div class="col text-center mb-1">
        @if ($presensi_hari_ini->status == 'izin')
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($presensi_hari_ini->status == 'sakit')
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($presensi_hari_ini->status == 'cuti')
                    <div class="card bg-secondary">
                        <div class="card-body">
                            <div>
                                <div>
                                    <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated" aria-label="checkmark"></ion-icon>
                                </div>
                                <div>
                                    <h3 class="presencetitle mt-1" style="line-height: 3px">{{ date("d-m-Y", strtotime($presensi_hari_ini->tanggal_presensi)) }}</h3>
                                    <h4  class="presencetitle mt-1" style="margin: 0px !important;">Hari ini Anda sedang {{ strtoupper($presensi_hari_ini->status )}}</h4>
                                    <p>{{ $presensi_hari_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
    </div>
</div>

@endsection

