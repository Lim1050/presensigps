@extends('layouts.admin.admin_master')

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Admin</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4 mb-xl-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Profil</h6>
                </div>
                <div class="card-body text-center">
                    <img id="previewImage" class="img-account-profile rounded-circle mb-3"
                        src="{{ asset('storage/uploads/user/'.$user->foto) }}" alt="Profile Picture"
                        style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e0e0e0;">

                    <p class="small text-muted mb-4">JPG atau PNG tidak lebih dari 5 MB</p>

                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="inputGroupFile" class="btn btn-outline-secondary btn-sm d-block mx-auto" style="width: 200px;">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> Pilih File
                            </label>
                            <input type="file" class="form-control d-none" id="inputGroupFile" name="foto" accept="image/*">
                        </div>

                        <div id="fileInfo" class="small text-muted mb-3" style="display: none;">
                            <p class="mb-1"><strong>Nama file:</strong> <span id="fileName"></span></p>
                            <p class="mb-0"><strong>Ukuran:</strong> <span id="fileSize"></span></p>
                        </div>

                        <button class="btn btn-primary btn-sm px-4" type="submit" id="uploadButton" disabled>
                            <i class="fas fa-upload mr-2"></i> Unggah Foto Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Detail Akun</div>
                <div class="card-body">
                    <form>
                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputUsername">Username</label>
                            <input class="form-control" id="inputUsername" type="text" placeholder="Masukkan username" value="{{ $user->username }}">
                        </div>
                        <!-- Form Row-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputFirstName">Nama Lengkap</label>
                            <input class="form-control" id="inputFirstName" type="text" placeholder="Masukkan nama depan" value="{{ $user->name }}">
                        </div>
                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Alamat Email</label>
                            <input class="form-control" id="inputEmailAddress" type="email" placeholder="Masukkan alamat email" value="{{ $user->email }}">
                        </div>
                        <!-- Form Group (phone number)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputPhone">Nomor Telepon</label>
                            <input class="form-control" id="inputPhone" type="tel" placeholder="Masukkan nomor telepon" value="{{ $user->no_hp }}">
                        </div>
                        <!-- Form Group (role)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="role">Role</label>
                            <input class="form-control" id="role" type="tel" placeholder="Role" value="{{ $user->role }}">
                        </div>
                        <!-- Form Group (kode_departemen)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="kode_departemen">kode_departemen</label>
                            <input class="form-control" id="kode_departemen" type="tel" placeholder="kode_departemen" value="{{ $user->kode_departemen }}">
                        </div>
                        <!-- Form Group (kode_cabang)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="kode_cabang">kode_cabang</label>
                            <input class="form-control" id="kode_cabang" type="tel" placeholder="kode_cabang" value="{{ $user->kode_cabang }}">
                        </div>
                        <!-- Save changes button-->
                        <button class="btn btn-primary" type="button">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
            <!-- Password change card -->
            <div class="card mb-4">
                <div class="card-header">Ubah Password</div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="small mb-1" for="current_password">Password Saat Ini</label>
                            <input class="form-control" id="current_password" name="current_password" type="password" placeholder="Masukkan password saat ini">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="new_password">Password Baru</label>
                            <input class="form-control" id="new_password" name="password" type="password" placeholder="Masukkan password baru">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="password_confirmation">Konfirmasi Password Baru</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Konfirmasi password baru">
                        </div>
                        <button class="btn btn-primary" type="submit">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('myscript')
    <script>
        document.getElementById('inputGroupFile').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var fileName = file.name;
                var fileSize = (file.size / 1024).toFixed(2) + " KB";
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileSize').textContent = fileSize;
                document.getElementById('fileInfo').style.display = 'block';
                document.getElementById('uploadButton').disabled = false;

                // Preview image
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                resetForm();
            }
        });

        function resetForm() {
            document.getElementById('inputGroupFile').value = '';
            document.getElementById('fileInfo').style.display = 'none';
            document.getElementById('uploadButton').disabled = true;
            document.getElementById('previewImage').src = "{{ asset('storage/uploads/user/'.$user->foto) }}";
        }
    </script>
@endpush
@endsection
