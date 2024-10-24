@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan.cashbon') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Pengajuan Cashbon</div>
    <div class="right"></div>
</div>
@endsection

@section('content')

<div class="container" style="margin-top: 70px; margin-bottom: 70px">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form id="cashbonForm" action="{{ route('keuangan.cashbon.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggal_pengajuan" class="form-label">Tanggal Pengajuan</label>
            <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan">
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" step="0.01">
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan"></textarea>
        </div>

        <button type="submit" class="btn btn-danger">Ajukan Cashbon</button>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $("#cashbonForm").submit(function(){
        var tanggal_pengajuan = $("#tanggal_pengajuan").val();
        var jumlah = $("#jumlah").val();
        var keterangan = $("#keterangan").val();
        if(tanggal_pengajuan==""){
            Swal.fire({
            title: 'Oops!',
            text: 'Tanggal Pengajuan Harus Diisi!',
            icon: 'warning',
            });
            return false;
        } else if (jumlah=="") {
            Swal.fire({
            title: 'Oops!',
            text: 'Jumlah Harus Diisi!',
            icon: 'warning',
            });
            return false;
        } else if (keterangan=="") {
            Swal.fire({
            title: 'Oops!',
            text: 'Keterangan Harus Diisi!',
            icon: 'warning',
            });
            return false;
        }
    });
</script>
@endpush
