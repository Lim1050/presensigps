@extends('layouts.master')

@section('header')
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
<div class="container" style="margin-top: 4rem; margin-bottom: 100px;">
    <div class="row">
        <div class="col-12">
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @elseif (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @error('foto')
                <div class="alert alert-warning">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Foto Profil -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h4 class="m-0 font-weight-bold text-secondary">Foto Profil</h4>
        </div>
        <div class="card-body text-center">
            <img id="previewImage" class="img-account-profile rounded-circle mb-2" src="{{ asset('storage/uploads/karyawan/'.$karyawan->foto) }}" alt="" style="width: 150px; height: 150px; object-fit: cover;">
            <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 5 MB</div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="inputGroupFile" class="btn btn-outline-secondary btn-sm d-block mx-auto" style="width: 200px;">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        Pilih Foto Baru
                    </label>
                    <input type="file" id="inputGroupFile" name="foto" accept=".png, .jpg, .jpeg" style="display: none;">
                </div>
                <div id="fileInfo" class="small text-muted mb-3" style="display: none;">
                    <p class="mb-1"><strong>Nama file:</strong> <span id="fileName"></span></p>
                    <p class="mb-0"><strong>Ukuran:</strong> <span id="fileSize"></span></p>
                </div>
                <button type="submit" class="btn btn-danger btn-block" id="uploadButton" disabled>
                    <ion-icon name="refresh-outline"></ion-icon> Perbarui Foto
                </button>
            </form>
        </div>
    </div>

    <!-- Informasi Profil -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h4 class="m-0 font-weight-bold text-secondary">Informasi Profil</h4>
        </div>
        <div class="card-body">
            {{-- {{ route('profile.update') }} --}}
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" value="{{ $karyawan->nik }}" name="nik" placeholder="NIK" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" value="{{ $karyawan->nama_lengkap }}" name="nama_lengkap" placeholder="Nama Lengkap">
                </div>
                <div class="mb-3">
                    <label for="no_wa" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="no_wa" value="{{ $karyawan->no_wa }}" name="no_wa" placeholder="No. WA">
                </div>
                <button type="submit" class="btn btn-danger btn-block">
                    <ion-icon name="refresh-outline"></ion-icon> Perbarui Profil
                </button>
            </form>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h4 class="m-0 font-weight-bold text-secondary">Ubah Password</h4>
        </div>
        <div class="card-body">
            {{-- {{ route('profile.update-password') }} --}}
            <form action="#" method="POST">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="old_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password Baru">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password Baru">
                </div>
                <button type="submit" class="btn btn-danger btn-block">
                    <ion-icon name="refresh-outline"></ion-icon> Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    const inputImage = document.getElementById('inputGroupFile');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadButton = document.getElementById('uploadButton');

    inputImage.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Preview gambar
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Tampilkan info file
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            fileInfo.style.display = 'block';

            // Aktifkan tombol upload
            uploadButton.disabled = false;
        } else {
            fileInfo.style.display = 'none';
            uploadButton.disabled = true;
        }
    });

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
</script>
@endpush
