@extends('layouts.master')
@section('header')
{{-- App Header --}}
<div class="appHeader gradasired text-light">
    <div class="left">
        <a href="{{ route('dashboard') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Profil</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="tab-content" style="margin-bottom:100px;">
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col" style="margin-top: 4rem">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">{{ $messagesuccess }}</div>
            @elseif (Session::get('error'))
                <div class="alert alert-error">{{ $messageerror }}</div>
            @endif

            @error('foto')
                <div class="alert alert-warning">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label for="nik">NIK</label>
                <input type="text" class="form-control" value="{{ $karyawan->nik }}" name="nik" placeholder="NIK" autocomplete="off" disabled>
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap }}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
            </div>
        </div>
        {{-- <div class="form-group boxed">
            <div class="input-wrapper">
                <label for="jabatan">Jabatan</label>
<<<<<<< HEAD
                <input type="text" class="form-control" value="{{ $karyawan->kode_jabatan }}" name="jabatan" placeholder="Jabatan" autocomplete="off" disabled>
=======
                <input type="text" class="form-control" value="{{ $karyawan->kode_jabatan }}" name="jabatan" placeholder="Jabatan" autocomplete="off">
>>>>>>> 1a0d450a0dd891b35b54efcccdcb71ce88d1e4b6
            </div>
        </div> --}}
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label for="no_wa">Nomor HP</label>
                <input type="text" class="form-control" value="{{ $karyawan->no_wa }}" name="no_wa" placeholder="No. WA" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
            </div>
        </div>
        <label for="foto">Foto</label>
        <div class="custom-file-upload" id="fileUpload1">
            <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput">
                <span>
                    <strong>
                        <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                        <i>Sentuh Untuk Unggah Foto Profil</i>
                    </strong>
                </span>
            </label>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-danger btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Perbarui
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection
