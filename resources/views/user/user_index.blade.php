@extends('layouts.admin.admin_master')
@section('content')

<style>
    .icon-placeholder {
        position: relative;
        /* display: inline-block; */
    }

    .icon-placeholder input {
        padding-left: 30px; /* Adjust padding to make room for the icon */
    }

    .icon-placeholder .bi {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        /* color: #ccc; Icon color */
    }

    .preview-container {
            margin-top: 20px;
    }
    .preview-container img {
        width: 100px;
        height: 150px;
        object-fit: cover;
        display: none; /* Initially hide the image */
    }
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data User</h1>
</div>

<!-- DataTables Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputUser"><i class="bi bi-plus-lg"></i> Tambah Data User</a>
            </div>
        </div>

        {{-- form cari data User --}}
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.user') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Cari Nama User" value="{{ Request('nama') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="role_name" id="role_name" class="form-control">
                                    <option value="">Pilih Role</option>
                                    @foreach ($user as $item)
                                    <option {{ Request('role_name') == $item->role_name ? 'selected' : '' }} value="{{ $item->role_name }}">{{ $item->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Foto</th>
                                <th>Role</th>
                                <th>Departemen</th>
                                <th>Kantor</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->username }}</td>
                                    <td class="text-center">{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->email }}</td>
                                    <td class="text-center">{{ $item->foto }}</td>
                                    <td class="text-center">{{ $item->role_name }}</td>
                                    <td class="text-center">{{ $item->nama_departemen }}</td>
                                    <td class="text-center">{{ $item->nama_cabang }}</td>
                                    <td class="text-center">{{ $item->no_hp }}</td>
                                    <td class="text-center">
                                        <div class="btn-group ">
                                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditUser"
                                                data-username="{{ $item->username }}"
                                                data-name="{{ $item->name }}"
                                                data-email="{{ $item->email }}"
                                                data-foto="{{ $item->foto }}"
                                                data-role_name="{{ $item->role_name }}"
                                                data-nama_departemen="{{ $item->kode_departemen }}"
                                                data-nama_cabang="{{ $item->kode_cabang }}"
                                                data-no_hp="{{ $item->no_hp }}">
                                                <i class="bi bi-pencil-square"></i> Edit</a>
                                            <a href="#" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $karyawan->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input User -->
<div class="modal fade" id="modalInputUser" tabindex="-1" aria-labelledby="modalInputUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputUserLabel">Input Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.konfigurasi.user.store') }}" method="POST" id="formUser" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" class="form-control" id="username" name="username" placeholder=" username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" id="name" name="name" placeholder=" Nama Lengkap">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control" id="email" name="email" placeholder=" Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder=" Nomor HP">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-key-fill"></i>
                            <input type="password" class="form-control" id="password" name="password" placeholder=" Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <select name="role" id="role" class="form-control">
                            <option value="">Pilih Role</option>
                            @foreach ($role as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_departemen" id="kode_departemen" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemen as $item)
                            <option value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_cabang" id="kode_cabang" class="form-control">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                            <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto">
                            <label class="custom-file-label" for="foto"  aria-describedby="inputGroupFileAddon02"><i class="bi bi-image"></i> Pilih Foto User</label>
                        </div>
                    </div>
                    <div class="preview-container">
                        <img id="imgPreview" src="#" alt="Your Image">
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal edit user --}}
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserLabel">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.konfigurasi.user.update', ['username' => 0]) }}" method="POST" id="formEditUser" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-badge"></i>
                            <input type="text" class="form-control" id="edit_username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-person-fill"></i>
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Nama Lengkap">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" class="form-control" id="edit_email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-telephone-fill"></i>
                            <input type="text" class="form-control" id="edit_no_hp" name="no_hp" placeholder="Nomor HP">
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="role" id="edit_role" class="form-control">
                            <option value="">Pilih Role</option>
                            @foreach ($role as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_departemen" id="edit_kode_departemen" class="form-control">
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemen as $item)
                                <option value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="kode_cabang" id="edit_kode_cabang" class="form-control">
                            <option value="">Pilih Cabang</option>
                            @foreach ($cabang as $item)
                                <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="edit_foto" name="foto" accept="image/*">
                            <label class="custom-file-label" for="foto"><i class="bi bi-image"></i> Pilih Foto User</label>
                        </div>
                    </div>
                    <div class="preview-container">
                        <img id="edit_imgPreview" src="#" alt="Your Image" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-bar-up"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>

    // $(function(){
    //     $("#nik").mask("000000000");
    //     $("#no_wa").mask("0000000000000");
    // });

    // preview image
    $(document).ready(function () {
            $('#foto').on('change', function () {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imgPreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imgPreview').hide();
                }
            });
        });

    $(".delete-confirm").click(function (e){
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
        title: "Apakah Anda Yakin?",
        text: "Data Ini Akan Dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!"
        }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
            Swal.fire({
            title: "Terhapus!",
            text: "Data Sudah dihapus.",
            icon: "success"
            });
        }
        });
    });

    // Validasi form
        $("#formUser").submit(function(){
            var username = $("#username").val();
            var name = $("#name").val();
            var email = $("#email").val();
            var no_hp = $("#no_hp").val();
            var password = $("#password").val();
            var role = $("#role").val();
            var kode_departemen = $("#kode_departemen").val();
            var kode_cabang = $("#kode_cabang").val();
            var foto = $("#foto").val();

            if(username==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Username Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#username").focus();
                });
                return false;
            } else if (name==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nama Lengkap Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#name").focus();
                });
                return false;
            } else if (email==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Email Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#email").focus();
                });
                return false;
            } else if (no_hp==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Nomor HP Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#no_hp").focus();
                });
                return false;
            } else if (password==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Password Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#password").focus();
                });
                return false;
            } else if (role==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Role Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#role").focus();
                });
                return false;
            } else if (kode_departemen==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Departemen Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_departemen").focus();
                });
                return false;
            } else if (kode_cabang==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Cabang Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#kode_cabang").focus();
                });
                return false;
            } else if (foto==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Foto Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#foto").focus();
                });
                return false;
            }
        });

    // Send Data to modal edit user
    $('#modalEditUser').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var username = button.data('username');
    var name = button.data('name');
    var email = button.data('email');
    var foto = button.data('foto');
    var role_name = button.data('role_name');
    var nama_departemen = button.data('nama_departemen');
    var nama_cabang = button.data('nama_cabang');
    var no_hp = button.data('no_hp');

    var modal = $(this);
    modal.find('.modal-body #edit_username').val(username);
    modal.find('.modal-body #edit_name').val(name);
    modal.find('.modal-body #edit_email').val(email);
    modal.find('.modal-body #edit_role').val(role_name);
    modal.find('.modal-body #edit_kode_departemen').val(nama_departemen);
    modal.find('.modal-body #edit_kode_cabang').val(nama_cabang);
    modal.find('.modal-body #edit_no_hp').val(no_hp);

    // Pratinjau gambar jika ada
    if (foto) {
        modal.find('.modal-body #edit_imgPreview').attr('src', '/public/uploads/user/' + foto);
    } else {
        modal.find('.modal-body #edit_imgPreview').attr('src', '#');
    }

    // Update form action URL with the username
    var formAction = "{{ route('admin.konfigurasi.user.update', ['username' => ':username']) }}";
    formAction = formAction.replace(':username', username);
    $('#formEditUser').attr('action', formAction);
});

// Pratinjau gambar saat mengupload file baru
$('#edit_foto').change(function(){
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#edit_imgPreview').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);
});
</script>


@endpush


@endsection