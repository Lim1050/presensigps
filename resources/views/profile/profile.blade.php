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
            <div id="cropperContainer" style="display: none; max-width: 300px; margin: 0 auto;">
                <img id="cropperImage" src="" alt="Image to crop" style="max-width: 100%;">
            </div>
            <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 5 MB</div>
            <form action="{{ route('profile.update.foto') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="inputGroupFile" class="btn btn-outline-secondary btn-sm d-block mx-auto" style="width: 200px;">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        Pilih Foto Baru
                    </label>
                    <input type="file" id="inputGroupFile" name="foto" accept=".png, .jpg, .jpeg" style="display: none;">
                    <input type="hidden" id="croppedImageData" name="croppedImage">
                </div>
                <div id="cropControls" style="display: none;">
                    {{-- <button type="button" class="btn btn-sm btn-primary" id="zoomInBtn">Zoom In</button> --}}
                    {{-- <button type="button" class="btn btn-sm btn-primary" id="zoomOutBtn">Zoom Out</button> --}}
                    {{-- <button type="button" class="btn btn-sm btn-primary" id="rotateLeftBtn">Rotate Left</button> --}}
                    {{-- <button type="button" class="btn btn-sm btn-primary" id="rotateRightBtn">Rotate Right</button> --}}
                    <button type="button" class="btn btn-sm btn-success" id="cropBtn">Crop</button>
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
            {{-- {{ route('profile.update.detail') }} --}}
            <form action="{{ route('profile.update.detail') }}" method="POST">
                @csrf
                @method('PUT')
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
            {{-- {{ route('profile.update.password') }} --}}
            <form action="{{ route('profile.update.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="old_password" class="form-label">Password Lama</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="old_password">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password Baru">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password Baru">
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                    </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const inputFile = document.getElementById('inputGroupFile');
        const previewImage = document.getElementById('previewImage');
        const cropperContainer = document.getElementById('cropperContainer');
        const cropperImage = document.getElementById('cropperImage');
        const cropControls = document.getElementById('cropControls');
        const uploadButton = document.getElementById('uploadButton');
        const profileForm = document.getElementById('profileForm');

        let cropper;

        inputFile.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                const imageData = event.target.result;
                if (previewImage) previewImage.style.display = 'none';
                if (cropperContainer) cropperContainer.style.display = 'block';
                if (cropperImage) cropperImage.src = imageData;
                if (cropControls) cropControls.style.display = 'block';

                if (!cropperImage) {
                    console.error('Cropper image element not found');
                    return;
                }

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    scalable: true,
                    zoomable: true,
                    crop: function (event) {
                        updateCroppedImage();
                    },
                });

                function updateCroppedImage() {
                    const canvas = cropper.getCroppedCanvas({
                        width: 150,
                        height: 150,
                    });
                    const croppedImageData = canvas.toDataURL('image/jpeg');
                    const croppedImageInput = document.getElementById('croppedImageData');
                    if (croppedImageInput) croppedImageInput.value = croppedImageData;
                    if (uploadButton) uploadButton.disabled = false;
                }

                ['zoomInBtn', 'zoomOutBtn', 'rotateLeftBtn', 'rotateRightBtn', 'cropBtn'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('click', function () {
                            switch(id) {
                                // case 'zoomInBtn': cropper.zoom(0.1); break;
                                // case 'zoomOutBtn': cropper.zoom(-0.1); break;
                                // case 'rotateLeftBtn': cropper.rotate(-10); break;
                                // case 'rotateRightBtn': cropper.rotate(10); break;
                                case 'cropBtn':
                                    updateCroppedImage();
                                    break;
                            }
                        });
                    }
                });

                if (profileForm) {
                    profileForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        updateCroppedImage();
                        this.submit();
                    });
                }
            };
            reader.readAsDataURL(file);
        });
    });
</script>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelectorAll('.toggle-password');

        togglePassword.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('ion-icon');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('name', 'eye-off-outline');
                } else {
                    input.type = 'password';
                    icon.setAttribute('name', 'eye-outline');
                }
            });
        });
    });
</script>
<script>
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (newPassword !== confirmPassword) {
            event.preventDefault(); // Mencegah pengiriman formulir
            alert('Password baru dan konfirmasi password tidak cocok. Silakan periksa kembali.');
        }
    });
</script>
@endpush
