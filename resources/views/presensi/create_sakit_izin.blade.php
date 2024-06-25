@extends('layouts.master')
@section('header')
{{-- css datepicker --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }
    .datepicker-date-display {
        background-color: #ca364b !important;
    }
    .datepicker-cancel, .datepicker-clear, .datepicker-today, .datepicker-done {
    color: #ca364b !important;
    }
    .datepicker-table td.is-selected {
    background-color: #ca364b !important;
    color: #fff !important;
    }
    .datepicker-table td.is-today {
    color: #ca364b;
    }
</style>
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Pengajuan Sakit / Izin </div>
    <div class="right"></div>
</div>
{{-- App Header --}}
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form action="{{ route('presensi.store.sakit-izin') }}" method="POST" enctype="multipart/form-data" style="min-height: 1000px" id="formIzin">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" name="tanggal_izin" id="tanggal_izin" placeholder="Tanggal">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Sakit/Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-danger btn-block">
                    {{-- <ion-icon name="arrow-up-outline"></ion-icon> --}}
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            $("#formIzin").submit(function(){
                var tanggal_izin = $("#tanggal_izin").val();
                var status = $("#status").val();
                var keterangan = $("#keterangan").val();
                if(tanggal_izin==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Tanggal Harus Diisi!',
                    icon: 'warning',
                    })
                    return false;
                } else if (status==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Status Harus Diisi!',
                    icon: 'warning',
                    })
                    return false;
                } else if (keterangan==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Keterangan Harus Diisi!',
                    icon: 'warning',
                    })
                    return false;
                }
            });
        });
    </script>
@endpush
