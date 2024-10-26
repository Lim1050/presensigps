@extends('layouts.master')

@section('header')
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('keuangan.cashbon') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Cashbon</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="container" style="margin-top: 70px; margin-bottom: 70px">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-secondary">{{ session('info') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('keuangan.cashbon.update', $cashbon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
            <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
                value="{{ old('tanggal_pengajuan', date('Y-m-d', strtotime($cashbon->tanggal_pengajuan))) }}"
                required>
            @error('tanggal_pengajuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah"
                class="form-control @error('jumlah') is-invalid @enderror"
                value="{{ old('jumlah', $cashbon->jumlah) }}"
                required>
            @error('jumlah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan"
                    class="form-control @error('keterangan') is-invalid @enderror"
                    required>{{ old('keterangan', $cashbon->keterangan) }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-danger">Update</button>
            <a href="{{ route('keuangan.cashbon') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    // Jika diperlukan JavaScript tambahan
</script>
@endpush
