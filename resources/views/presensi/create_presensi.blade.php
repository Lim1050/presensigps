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

{{-- style for absen --}}
<style>
    .webcam-capture,
    .webcam-capture video{
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;

    }
    #map {
        height: 200px;
    }

    .jam-digital-malasngoding {
        background-color: #27272783;
        position: absolute;
        top: 63px;
        left: 10px;
        z-index: 9999;
        width: auto;
        height: auto;
        border-radius: 10px;
        padding: 5px;
    }

    .jam-digital-malasngoding p {
        color: #fff;
        font-size: 12px;
        text-align: left;
        margin-top: -5px;
        margin-bottom: -5px;
    }
</style>

{{-- leaflet css (for map) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
{{-- leaflet js (for map) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@endsection

@section('content')
<div class="tab-content" style="margin-bottom:100px;">
    <div class="row" style="margin-top: 60px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="jam-digital-malasngoding">
        <p>{{ $hari_ini }}</p>
        <p id="jam"></p>
        <p>{{ $jam_kerja_karyawan->nama_jam_kerja }}</p>

        @if($lembur_hari_ini)
            @php
                $jam_masuk = date("H:i", strtotime($jam_kerja_karyawan->jam_masuk));
                $jam_pulang = date("H:i", strtotime($jam_kerja_karyawan->jam_pulang));
                $jam_mulai_lembur = date("H:i", strtotime($lembur_hari_ini->waktu_mulai));
                $jam_selesai_lembur = date("H:i", strtotime($lembur_hari_ini->waktu_selesai));
            @endphp

            <p>Awal Jam Masuk : {{ date("H:i",strtotime($jam_kerja_karyawan->awal_jam_masuk)) }}</p>
            <p>Jam Masuk : {{ $jam_masuk }}</p>
            <p>Akhir Jam Masuk : {{ date("H:i",strtotime($jam_kerja_karyawan->akhir_jam_masuk)) }}</p>
            <p>Jam Pulang : {{ $jam_pulang }}</p>
            <p class="text-success">Lembur Disetujui</p>
            <p>Jam Mulai Lembur: {{ $jam_mulai_lembur }}</p>
            <p>Jam Selesai Lembur: {{ $jam_selesai_lembur }}</p>
        @else
            <p>Awal Jam Masuk : {{ date("H:i",strtotime($jam_kerja_karyawan->awal_jam_masuk)) }}</p>
            <p>Jam Masuk : {{ date("H:i",strtotime($jam_kerja_karyawan->jam_masuk)) }}</p>
            <p>Akhir Jam Masuk : {{ date("H:i",strtotime($jam_kerja_karyawan->akhir_jam_masuk)) }}</p>
            <p>Jam Pulang : {{ date("H:i",strtotime($jam_kerja_karyawan->jam_pulang)) }}</p>
        @endif
    </div>
    <div class="row">
        <div class="col">
            @if ($cek_izin && $cek_masuk && !$foto_keluar && in_array($cek_izin->status, ['izin', 'sakit', 'cuti']))
                <button id="sudahabsen" class="btn btn-secondary btn-block" disabled>
                    <ion-icon name="camera-outline"></ion-icon>{{ $cek_izin->status }}
                </button>
            @elseif (!$cek_masuk)
                @php
                    $jam_sekarang = date('H:i:s');
                    $awal_jam_masuk = $jam_kerja_karyawan->awal_jam_masuk;
                    $akhir_jam_masuk = $jam_kerja_karyawan->akhir_jam_masuk;

                    // Sesuaikan jam masuk jika ada lembur
                    if ($lembur_hari_ini) {
                        $waktu_mulai_lembur = \Carbon\Carbon::parse($lembur_hari_ini->waktu_mulai);
                        $waktu_selesai_lembur = \Carbon\Carbon::parse($lembur_hari_ini->waktu_selesai);
                        $jam_masuk_normal = \Carbon\Carbon::parse($jam_kerja_karyawan->jam_masuk);

                        // Jika lembur sebelum jam kerja normal
                        if ($waktu_selesai_lembur <= $jam_masuk_normal) {
                            $awal_jam_masuk = $lembur_hari_ini->waktu_mulai;
                            $akhir_jam_masuk = $lembur_hari_ini->waktu_selesai;
                        }
                    }

                    $boleh_masuk = strtotime($jam_sekarang) >= strtotime($awal_jam_masuk) &&
                                strtotime($jam_sekarang) <= strtotime($akhir_jam_masuk);

                    // Hitung sisa waktu
                    if (strtotime($jam_sekarang) < strtotime($awal_jam_masuk)) {
                        // Jika belum waktunya masuk
                        $sisa_waktu = strtotime($awal_jam_masuk) - strtotime($jam_sekarang);
                        $sisa_menit = ceil($sisa_waktu / 60);
                        $pesan = "Belum Waktunya Masuk<br><small>($sisa_menit menit lagi)</small>";
                    } elseif (strtotime($jam_sekarang) > strtotime($akhir_jam_masuk)) {
                        // Jika sudah lewat waktu masuk
                        $pesan = "Waktu Absen Masuk Telah Berakhir";
                    } else {
                        $pesan = "Absen Masuk";
                    }
                @endphp
                <button id="takeabsen" class="btn btn-primary btn-block" {{ !$boleh_masuk ? 'disabled' : '' }}>
                    <ion-icon name="camera-outline"></ion-icon>
                    {!! $pesan !!}
                </button>
            @elseif (($cek_masuk && !$foto_keluar) || ($cek_lintas_hari == 1 && !$cek_presensi_sebelumnya->foto_keluar))
                @php
                    $jam_sekarang = date('H:i:s');
                    $jam_pulang = $jam_kerja_karyawan->jam_pulang;

                    if ($lembur_hari_ini) {
                        $waktu_mulai_lembur = \Carbon\Carbon::parse($lembur_hari_ini->waktu_mulai);
                        $waktu_selesai_lembur = \Carbon\Carbon::parse($lembur_hari_ini->waktu_selesai);
                        $jam_masuk_normal = \Carbon\Carbon::parse($jam_kerja_karyawan->jam_masuk);
                        $jam_pulang_normal = \Carbon\Carbon::parse($jam_kerja_karyawan->jam_pulang);

                        // Jika lembur sebelum jam kerja normal
                        if ($waktu_selesai_lembur <= $jam_masuk_normal) {
                            $jam_pulang = $jam_kerja_karyawan->jam_pulang;
                        }
                        // Jika lembur setelah jam pulang normal
                        elseif ($waktu_mulai_lembur >= $jam_pulang_normal) {
                            $jam_pulang = $lembur_hari_ini->waktu_selesai;
                        }
                    }

                    // Toleransi pulang lebih awal (30 menit)
                    $toleransi_pulang = 30;
                    $jam_pulang_minimal = date('H:i:s', strtotime("-{$toleransi_pulang} minutes", strtotime($jam_pulang)));

                    $boleh_pulang = strtotime($jam_sekarang) >= strtotime($jam_pulang_minimal);

                    // Hitung sisa waktu
                    $sisa_waktu = strtotime($jam_pulang_minimal) - strtotime($jam_sekarang);
                    $sisa_menit = ceil($sisa_waktu / 60);
                @endphp
                <button id="takeabsen" class="btn btn-danger btn-block" {{ !$boleh_pulang ? 'disabled' : '' }}>
                    <ion-icon name="camera-outline"></ion-icon>
                    @if($boleh_pulang)
                        Absen Pulang
                    @else
                        Belum Waktunya Pulang
                        <br>
                        <small>({{ $sisa_menit }} menit lagi)</small>
                    @endif
                </button>
            @elseif ($cek_keluar && $cek_masuk)
                <button id="sudahabsen" class="btn btn-secondary btn-block" disabled>
                    <ion-icon name="camera-outline"></ion-icon>Sudah Absen
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <button class="btn btn-secondary btn-block"><ion-icon name="camera-outline"></ion-icon>Absen Lembur</button>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <audio id="notif_in">
        <source src="{{ asset('assets/sound/notif_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_out">
        <source src="{{ asset('assets/sound/notif_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_rad">
        <source src="{{ asset('assets/sound/notif_rad.mp3') }}" type="audio/mpeg">
    </audio>
</div>

@endsection

@push('myscript')
<script type="text/javascript">
    window.onload = function() {
        jam();
    }

    function jam() {
        var e = document.getElementById('jam')
            , d = new Date()
            , h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());

        e.innerHTML = h + ':' + m + ':' + s;

        setTimeout('jam()', 1000);
    }

    function set(e) {
        e = e < 10 ? '0' + e : e;
        return e;
    }
</script>

{{-- <script>
    // JavaScript to disable the button
    var button = document.getElementById('sudahabsen');
    button.disabled = true;
</script> --}}

<script>
    // inisiasi audio
    var notif_in = document.getElementById('notif_in');
    var notif_out = document.getElementById('notif_out');
    var notif_rad = document.getElementById('notif_rad');
    // setting webcam
    Webcam.set({
        height: 480,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });

    Webcam.attach('.webcam-capture');

    // mendapatkan lokasi
    var lokasi = document.getElementById('lokasi');
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }

    // menampilkan lokasi
    function successCallback(position){
        lokasi.value = position.coords.latitude+","+position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 17);

        var lokasi_penugasan = "{{ $lokasi_penugasan->lokasi_penugasan }}";
        var lokasi_penugasan = lokasi_penugasan.split(",");
        var lat_lokasi_penugasan = lokasi_penugasan[0];
        var long_lokasi_penugasan = lokasi_penugasan[1];

        // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19,
        //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        // }).addTo(map);
        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

        // nanti disesuaikan dengan lokasi lokasi_penugasan
        var radius = "{{ $lokasi_penugasan->radius }}";
        var circle = L.circle([lat_lokasi_penugasan, long_lokasi_penugasan], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);
    }

    function errorCallback(){

    }

    // get data gambar dan lokasi
    $("#takeabsen").click(function(e){
        Webcam.snap(function(uri){
            image = uri;
        });
        var lokasi = $("#lokasi").val();

        // save data with ajax
        $.ajax({
            type:'POST',
            url:'/presensi/store',
            data:{
                _token: "{{ csrf_token() }}",
                image:image,
                lokasi:lokasi
            },
            cache:false,
            success:function(respond) {
                var status = respond.split("|");
                if(status[0] == "success"){
                    if (status[2] == "in") {
                        notif_in.play();
                    } else {
                        notif_out.play();
                    }
                    Swal.fire({
                    title: 'Success!',
                    text: status[1],
                    icon: 'success',
                    })
                    setTimeout("location.href='/dashboard'", 3000)
                }else{
                    if (status[2] == "rad") {
                        notif_rad.play();
                    }
                    Swal.fire({
                    title: 'Failed!',
                    text: status[1],
                    icon: 'error',
                    confirmButtonText: 'Ok'
                    })
                }
            }
        });

    });
</script>
@endpush
