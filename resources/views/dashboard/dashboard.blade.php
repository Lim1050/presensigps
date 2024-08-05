@extends('layouts.master')
@section('content')
<style>
    .logout{
        position:absolute;
        color: white;
        font-size: 15px;
        right: 5px;
    }
</style>
<div class="section gradasired" id="user-section">
    <a href="{{ route('logout') }}" class="btn btn-primary logout"><ion-icon name="log-out-outline"></ion-icon>Keluar</a><br>
    <div id="user-detail">
        <div class="avatar">
            @if ((Auth::guard('karyawan')->user()->foto))
                @php
                    $path = Storage::url("uploads/karyawan/".Auth::guard('karyawan')->user()->foto)
                @endphp
                <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded" style="height: 60px">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded">
            @endif
        </div>
        <div id="user-info">
            <h2 id="user-name">
                @php
                    $fullName = Auth::guard('karyawan')->user()->nama_lengkap;
                    $namaSingkat = '';
                    $parts = explode(' ', $fullName);

                    // Ambil kata pertama dan tambahkan langsung ke namaSingkat
                    $namaSingkat .= $parts[0] . ' ';

                    // Loop dimulai dari index 1 agar kata pertama tidak disingkat
                    for ($i = 1; $i < count($parts); $i++) {
                        // Ambil inisial dari setiap kata kecuali kata pertama
                        $namaSingkat .= strtoupper(substr($parts[$i], 0, 1));
                    }

                    // Tampilkan hasilnya
                    echo $namaSingkat;
                @endphp
            </h2>
            <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
            (<span id="user-role">{{ Auth::guard('karyawan')->user()->kode_cabang }}</span>)
        </div>
    </div>
</div>

<div class="section mt-1" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('profile') }}" class="green" style="font-size: 40px;">
                            <ion-icon name="person-sharp"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Profil</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('presensi.history') }}" class="danger" style="font-size: 40px;">
                            <ion-icon name="calendar-number"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Riwayat</span>
                    </div>
                </div>
                <div class="item-menu text-center">
                    <div class="menu-icon">
                        <a href="{{ route('izin') }}" class="warning" style="font-size: 40px;">
                            <ion-icon name="document-text"></ion-icon>
                        </a>
                    </div>
                    <div class="menu-name">
                        <span class="text-center">Izin</span>
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
                                @if ($presensi_hari_ini != null && $presensi_hari_ini->status == 'hadir')
                                    @php
                                        $path = Storage::url($presensi_hari_ini->foto_masuk);
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'izin')
                                    <ion-icon name="reader-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'sakit')
                                    <ion-icon name="medkit-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'cuti')
                                    <ion-icon name="calendar-outline"></ion-icon>
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>

                            @if ($presensi_hari_ini == null || $presensi_hari_ini->status == 'hadir')
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensi_hari_ini != null ? $presensi_hari_ini->jam_masuk : 'Belum Absen' }}</span>
                                </div>
                            @else
                                <div class="presencedetail">
                                    <h4 class="presencetitle">{{ strtoupper($presensi_hari_ini->status) }}</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($presensi_hari_ini != null && $presensi_hari_ini->foto_keluar != null)
                                    @php
                                        $path = Storage::url($presensi_hari_ini->foto_keluar);
                                    @endphp
                                    <img src="{{ url($path) }}" alt="" class="imaged w48">
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'izin')
                                    <ion-icon name="reader-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'sakit')
                                    <ion-icon name="medkit-outline"></ion-icon>
                                @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status == 'cuti')
                                    <ion-icon name="calendar-outline"></ion-icon>
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>

                            @if ($presensi_hari_ini != null && $presensi_hari_ini->foto_keluar != null)
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensi_hari_ini->jam_keluar != null ? $presensi_hari_ini->jam_keluar : 'Belum Absen' }}</span>
                                </div>
                            @elseif ($presensi_hari_ini != null && $presensi_hari_ini->status != 'hadir')
                                <div class="presencedetail">
                                    <h4 class="presencetitle">{{ strtoupper($presensi_hari_ini->status)}}</h4>
                                </div>
                            @else
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>Belum Absen</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rekappresensi">
        <h3>Rekap Presensi Bulan {{ $monthName }} {{ $tahun_ini }}</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_hadir }}</span>
                        <ion-icon name="checkmark-circle-outline" style="font-size: 1.6rem" class="text-success mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-success">Hadir</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_sakit_izin->jumlah_sakit }}</span>
                        <ion-icon name="medkit-outline" style="font-size: 1.6rem" class="text-danger mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-danger">Sakit</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_sakit_izin->jumlah_izin }}</span>
                        <ion-icon name="reader-outline" style="font-size: 1.6rem" class="text-primary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-primary">Izin</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_sakit_izin->jumlah_cuti }}</span>
                        <ion-icon name="calendar-outline" style="font-size: 1.6rem" class="text-secondary mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-secondary">Cuti</span>
                    </div>
                </div>
            </div>
            {{-- <div class="col-3">
                <div class="card">
                    <div class="card-body text-center" style="padding: 12px 12px; line-height:0.8rem !important">
                        <span class="badge bg-danger" style="position: absolute; top:3px; right: 5px; font-size:0.7rem; z-index:999">{{ $rekap_presensi->jml_terlambat }}</span>
                        <ion-icon name="alert-circle-outline" style="font-size: 1.6rem" class="text-warning mb-1"></ion-icon>
                        <br>
                        <span style="font-size: 0.8rem; font-weight:500" class="text-warning">Telat</span>
                    </div>
                </div>
            </div> --}}
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
                        Daftar Kehadiran
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                {{-- <ul class="listview image-listview">
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
                                    <span class="badge {{ $bulan_ini->jam_masuk < "09:00" ? "badge-success" : "badge-warning"}}">{{ $bulan_ini->jam_masuk < "09:00" ? $bulan_ini->jam_masuk : "Telat " . $bulan_ini->jam_masuk}}</span>
                                    <span class="badge badge-danger">{{ $bulan_ini->jam_keluar != null ? $bulan_ini->jam_keluar : 'Belum Absen'}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul> --}}
                <style>
                    .historycontent{
                        display: flex;
                    }
                    .datapresensi{
                        margin-left: 10px;
                    }
                </style>
                @foreach ($history_bulan_ini as $bulan_ini)
                @if ($bulan_ini->status == "hadir")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    @if ($bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk)
                                        <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-success" aria-label="checkmark"></ion-icon>
                                    @else
                                        <ion-icon style="font-size: 48px" name="alert-circle-outline" role="img" class="md hydrated text-warning" aria-label="checkmark"></ion-icon>
                                    @endif
                                    {{-- <ion-icon style="font-size: 48px" name="checkmark-circle-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon> --}}
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ $bulan_ini->nama_jam_kerja }} <small>({{ date("H:i",strtotime($bulan_ini->jam_kerja_masuk)) }} - {{ date("H:i",strtotime($bulan_ini->jam_pulang)) }})</small></h3>

                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <span class="{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? date("H:i",strtotime($bulan_ini->jam_masuk)) : date("H:i",strtotime($bulan_ini->jam_masuk))}}</span> -
                                    <span class="text-danger">{{ $bulan_ini->jam_keluar != null ? date("H:i",strtotime($bulan_ini->jam_keluar)) : 'Belum Absen'}}</span>
                                    <div id="keterangan" class="mt-0">
                                        @php
                                            // waktu ketika absen
                                            $jam_masuk = date("H:i",strtotime($bulan_ini->jam_masuk));
                                            // waktu jadwal masuk
                                            $jam_kerja_masuk = date("H:i",strtotime($bulan_ini->jam_kerja_masuk));

                                            $jadwal_jam_masuk = $bulan_ini->tanggal_presensi." ".$jam_kerja_masuk;
                                            $jam_presensi = $bulan_ini->tanggal_presensi." ".$jam_masuk;
                                            $hitungjamterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
                                            // Konversi hasil $hitungjamterlambat menjadi format deskriptif
                                            list($hours, $minutes) = explode(':', $hitungjamterlambat);
                                            $deskripsiTerlambat = '';
                                            if ($hours > 0) {
                                                $deskripsiTerlambat .= (int)$hours . ' jam';
                                            }
                                            if ($minutes > 0) {
                                                if ($hours > 0) {
                                                    $deskripsiTerlambat .= ' ';
                                                }
                                                $deskripsiTerlambat .= (int)$minutes . ' menit';
                                            }
                                        @endphp
                                        <span class="{{ $bulan_ini->jam_masuk < $bulan_ini->jam_kerja_masuk ? "text-success" : "text-warning"}}">{{ $bulan_ini->jam_masuk > $bulan_ini->jam_kerja_masuk ? "Terlambat $deskripsiTerlambat" : "Tepat Waktu"}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="izin")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="reader-outline" role="img" class="md hydrated text-primary" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status) }}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="sakit")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="medkit-outline" role="img" class="md hydrated text-danger" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status) }}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($bulan_ini->status=="cuti")
                    <div class="card mb-1">
                        <div class="card-body">
                            <div class="historycontent">
                                <div class="iconpresensi">
                                    <ion-icon style="font-size: 48px" name="calendar-outline" role="img" class="md hydrated text-secondary" aria-label="checkmark"></ion-icon>
                                </div>
                                <div class="datapresensi">
                                    <h3 style="line-height: 3px">{{ strtoupper($bulan_ini->status )}}
                                    <h4 style="margin: 0px !important;">{{ date("d-m-Y", strtotime($bulan_ini->tanggal_presensi)) }}</h4>
                                    <p>{{ $bulan_ini->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($leaderboards as $leaderboard)
                    <li>
                        <div class="item">
                            @php
                                $path = Storage::url("uploads/karyawan/".$leaderboard->foto)
                            @endphp
                            <img src="{{ url($path) }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    {{ $leaderboard->nama_lengkap }}
                                    <br>
                                    <small class="text-muted">{{ $leaderboard->jabatan }}</small>
                                </div>
                                @if ($leaderboard->status == 'hadir')
                                <span class="badge {{ $leaderboard->jam_masuk < "09:00" ? "badge-success" : "badge-warning"}}">{{ $leaderboard->jam_masuk < "09:00" ? $leaderboard->jam_masuk : "Telat " . $leaderboard->jam_masuk}}</span>
                                @else
                                <span class="badge badge-secondary">{{ $leaderboard->status}}</span>
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
