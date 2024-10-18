@extends('layouts.admin.admin_master')
@section('content')

<style>
    .icon-placeholder {
        position: relative;
    }

    .icon-placeholder input {
        padding-left: 30px;
    }

    .icon-placeholder .bi {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
    }

    .preview-container {
        margin-top: 20px;
    }
    .preview-container img {
        width: 100px;
        height: 150px;
        object-fit: cover;
        display: none;
    }
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Gaji Karyawan {{ $penggajian->karyawan->nama_lengkap }}</h1>
</div>

<!-- Edit Form -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.penggajian.update', $penggajian->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="text" class="form-control" id="bulan" name="bulan" value="{{ $penggajian->bulan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_hari_dalam_bulan">Jumlah Hari dalam Bulan</label>
                        <input type="number" class="form-control" id="jumlah_hari_dalam_bulan" name="jumlah_hari_dalam_bulan" value="{{ $penggajian->jumlah_hari_dalam_bulan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_hari_masuk">Jumlah Hari Masuk</label>
                        <input type="number" class="form-control" id="jumlah_hari_masuk" name="jumlah_hari_masuk" value="{{ $penggajian->jumlah_hari_masuk }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_hari_tidak_masuk">Jumlah Hari Tidak Masuk</label>
                        <input type="number" class="form-control" id="jumlah_hari_tidak_masuk" name="jumlah_hari_tidak_masuk" value="{{ $penggajian->jumlah_hari_tidak_masuk }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gaji_tetap">Gaji Tetap</label>
                        <input type="number" step="0.01" class="form-control" id="gaji_tetap" name="gaji_tetap" value="{{ $penggajian->gaji_tetap }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_jabatan">Tunjangan Jabatan</label>
                        <input type="number" step="0.01" class="form-control" id="tunjangan_jabatan" name="tunjangan_jabatan" value="{{ $penggajian->tunjangan_jabatan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for=" uang_makan">Uang Makan</label>
                        <input type="number" step="0.01" class="form-control" id="uang_makan" name="uang_makan" value="{{ $penggajian->uang_makan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="transportasi">Uang Transportasi</label>
                        <input type="number" step="0.01" class="form-control" id="transportasi" name="transportasi" value="{{ $penggajian->transportasi }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="catatan_perubahan">Catatan Perubahan</label>
                        <textarea class="form-control" id="catatan_perubahan" name="catatan_perubahan" rows="3" required></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
        </form>
    </div>
</div>

@push('myscript')

@endpush

@endsection
