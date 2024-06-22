@extends('layouts.master')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
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
</style>

{{-- leaflet css (for map) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
{{-- leaflet js (for map) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">

        {{-- menampilkan lokasi --}}
        <input type="hidden" id="lokasi">

        {{-- menampilkan webcam --}}
        <div class="webcam-capture"></div>
    </div>
</div>
<div class="row">
    <div class="col">
        @if ($cek_masuk > 0 && empty($foto_keluar))
            <button id="takeabsen" class="btn btn-danger btn-block"><ion-icon name="camera-outline"></ion-icon>Absen Pulang</button>
        @elseif ($cek_masuk == 0 || $cek_masuk > 0 && !empty($foto_keluar))
            <button id="takeabsen" class="btn btn-primary btn-block"><ion-icon name="camera-outline"></ion-icon>Absen Masuk</button>
        @endif
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

@endsection

@push('myscipt')
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
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([position.coords.latitude, position.coords.longitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 60
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
