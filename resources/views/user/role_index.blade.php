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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Role</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-4">
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputRole"><i class="bi bi-plus-lg"></i> Tambah Data Role</a>
                {{-- <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalImportRole"><i class="bi bi-arrow-bar-up"></i></i> Import Role</a> --}}
                {{-- <a href="{{ route('admin.konfigurasi.role.export') }}" class="btn btn-success">Export Role <i class="bi bi-arrow-bar-right"></i> </a> --}}
            </div>
        </div>

        {{-- form cari data jam kerja --}}
        {{-- <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.jam.kerja') }}" method="GET">
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_jam_kerja_cari" id="nama_jam_kerja_cari" class="form-control" placeholder="Cari Nama Jam Kerja" value="{{ Request('nama_jam_kerja') }}">
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
        </div> --}}
        <div class="row">
            <div class="col-12">
                {{-- table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">
                                    <div class="btn-group ">
                                        <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditRole"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            ><i class="bi bi-pencil-square"></i> Edit</a>
                                            {{-- {{ route('admin.konfigurasi.role.delete', $item->id) }} --}}
                                        <a href="{{ route('admin.konfigurasi.role.delete', $item->id) }}" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $departemen->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Role -->
<div class="modal fade" id="modalInputRole" tabindex="-1" aria-labelledby="modalInputRoleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputRoleLabel">Input Data Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- {{ route('admin.konfigurasi.Role.store') }} --}}
                <form action="{{ route('admin.konfigurasi.role.store') }}" method="POST" id="formRole" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-universal-access"></i>
                            <input type="text" class="form-control" id="name" name="name" placeholder=" Role">
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <select name="group_name" id="group_name" class="form-control">
                            <option value="">Pilih Group Permission</option>
                            <option value="Dashboard">Dashboard</option>
                            <option value="Laporan">Laporan</option>
                            <option value="Master">Master</option>
                            <option value="Konfigurasi">Konfigurasi</option>
                        </select>
                    </div> --}}
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

<!-- Modal Import Permission -->
<div class="modal fade" id="modalImportPermission" tabindex="-1" aria-labelledby="modalImportPermissionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportPermissionLabel">Import Data Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- {{ route('admin.konfigurasi.role.store') }} --}}
                <form action="#" method="POST" id="formPermission" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="import_file" name="import_file" accept=".xlsx, .xls">
                            <label class="custom-file-label" for="import_file"><i class="bi bi-file-earmark-excel"></i> Xlsx File Import</label>
                        </div>
                    </div>
                    <div class="preview-container">
                        <p id="fileNamePreview" style="font-weight: bold;"></p>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-bar-up"></i> Import</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEditRole" tabindex="-1" role="dialog" aria-labelledby="modalEditRoleLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditRoleLabel">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- {{ route('admin.konfigurasi.role.update', ['id' => 0]) }} --}}
            <form id="editForm" method="POST" action="{{ route('admin.konfigurasi.role.update', ['id' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <input type="hidden" class="form-control" id="edit_id" name="id" readonly>

                    <div class="form-group">
                        <div class="icon-placeholder">
                            <i class="bi bi-universal-access"></i>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <select name="group_name" id="edit_group_name" class="form-control">
                            <option value="">Pilih Group Permission</option>
                            <option value="Dashboard" {{ old('group_name', '') == 'Dashboard' ? 'selected' : '' }}>Dashboard</option>
                            <option value="Laporan" {{ old('group_name', '') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="Master" {{ old('group_name', '') == 'Master' ? 'selected' : '' }}>Master</option>
                            <option value="Konfigurasi" {{ old('group_name', '') == 'Konfigurasi' ? 'selected' : '' }}>Konfigurasi</option>
                        </select>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('myscript')
<script>

    document.getElementById('import_file').addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file && (file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || file.type === "application/vnd.ms-excel")) {
            // Menampilkan nama file di paragraf pratinjau
            document.getElementById('fileNamePreview').textContent = "File name: " + file.name;
        } else {
            document.getElementById('fileNamePreview').textContent = "Please upload a valid Excel file.";
        }
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
        $("#formRole").submit(function(){
            var name = $("#name").val();
            // var guard_name = $("#guard_name").val();

            if(name==""){
                Swal.fire({
                title: 'Oops!',
                text: 'Kode Jam Kerja Harus Diisi!',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#name").focus();
                });
                return false;
            }
        });

    // Send Data to modal edit Role
        $('#modalEditRole').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var name = button.data('name');

        var modal = $(this);
        modal.find('.modal-body #edit_id').val(id);
        modal.find('.modal-body #edit_name').val(name);

        // Update form action URL with the id
        var formAction = "{{ route('admin.konfigurasi.role.update', ['id' => ':id']) }}";
        formAction = formAction.replace(':id', id);
        $('#editForm').attr('action', formAction);
    });
</script>


@endpush


@endsection
