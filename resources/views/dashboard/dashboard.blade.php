@extends('layouts.master')
@section('content')
<div class="section" id="user-section">
    <div id="user-detail">
        {{-- @php
            $message = Session::get('message');
        @endphp

        @if (Session::get('message'))
            <div class="alert alert-outline-success">
                {{ $message }}
            </div>
        @endif --}}

        <div class="avatar">
            @if ((Auth::guard('karyawan')->user()->foto))
                @php
                    $path = Storage::url("uploads/karyawan/".Auth::guard('karyawan')->user()->foto)
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded" style="height: 60px">
            @else
                <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
        </div>
    </div>
</div>

<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Cuti</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Histori</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="" class="orange" style="font-size: 40px;">
                            <ion-icon name="location"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        Lokasi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">

            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                            @if ($presensi_hari_ini != null)
                                @php
                                    $path = Storage::url($presensi_hari_ini->foto_masuk);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48 ">
                            @else
                                <ion-icon name="camera"></ion-icon>
                            @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $presensi_hari_ini != null ? $presensi_hari_ini->jam_masuk : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensi_hari_ini != null)
                                @php
                                    $path = Storage::url($presensi_hari_ini->foto_keluar);
                                @endphp
                                <img src="{{ url($path) }}" alt="" class="imaged w48 ">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $presensi_hari_ini != null && $presensi_hari_ini->jam_keluar != null? $presensi_hari_ini->jam_keluar : 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="rekappresence">
        <div id="chartdiv"></div>
        <!-- <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence primary">
                                <ion-icon name="log-in"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Hadir</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence green">
                                <ion-icon name="document-text"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Izin</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence warning">
                                <ion-icon name="sad"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Sakit</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence danger">
                                <ion-icon name="alarm"></ion-icon>
                            </div>
                            <div class="presencedetail">
                                <h4 class="rekappresencetitle">Terlambat</h4>
                                <span class="rekappresencedetail">0 Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div> --}}

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $monthName }} {{ $tahun_ini }}</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_hadir }}</span>
                        <ion-icon name="accessibility-outline" style="font-size: 1.6rem" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-primary">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">0</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.6rem" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-success">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">0</span>
                        <ion-icon name="information-outline" style="font-size: 1.6rem" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-warning">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_terlambat }}</span>
                        <ion-icon name="close-circle-outline" style="font-size: 1.6rem" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-danger">Telat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                        Bulan Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($history_bulan_ini as $bulan_ini)
                    @php
                        $path = Storage::url($bulan_ini->foto_masuk);
                    @endphp
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="checkmark-circle-outline" role="img" class="md hydrated"
                                    aria-label="checkmark"></ion-icon>
                            </div>
                            <div class="in">
                                <div>{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</div>
                                <div>
                                    <span class="badge badge-success">{{ $bulan_ini->jam_masuk }}</span>
                                    <span class="badge badge-danger">{{ $presensi_hari_ini != null && $bulan_ini->jam_keluar != null ? $bulan_ini->jam_keluar : 'Belum Absen'}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach

                </ul>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboards as $leaderboard)
                    <li>
                        <div class="item">
                            <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                            <div class="in">
                                <div>
                                    {{ $leaderboard->nama_lengkap }}
                                    <br>
                                    <small class="text-muted">{{ $leaderboard->jabatan }}</small>
                                </div>
                                @if ($leaderboard->jam_masuk < "09:00")
                                    <span class="badge bg-success">{{ $leaderboard->jam_masuk}}
                                @else
                                    <span class="badge bg-danger">{{ $leaderboard->jam_masuk}}  <small>Telat</small></span>
                                @endif

                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
