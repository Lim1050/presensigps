@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori Presensi</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i = 1; $i<=12; $i++)
                            <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $months[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @php
                            $tahun_mulai = 2000;
                            $tahun_sekarang = date("Y");
                        @endphp
                        @for ($tahun=$tahun_mulai; $tahun <= $tahun_sekarang; $tahun++)
                            <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-danger btn-block" id="getData"><ion-icon name="search-outline"></ion-icon>Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col" id="showHistory">

    </div>
</div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#getData").click(function(e){
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();
                // alert(bulan + " " + tahun);
                $.ajax({
                    type:'POST',
                    url:'/gethistory',
                    data:{
                        _token:"{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    success: function(respond){
                        $('#showHistory').html(respond);
                    }
                });
            });
        });
    </script>
@endpush
