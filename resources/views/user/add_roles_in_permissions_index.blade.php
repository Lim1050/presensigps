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
    <h1 class="h3 mb-0 text-gray-800">Konfigurasi Role in Permission</h1>
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
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalInputRole"><i class="bi bi-plus-lg"></i> Tambah Role in Permission</a>
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
                                <th>Role</th>
                                <th>Permission</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key=> $item)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @foreach ($item->permissions as $permission)
                                            <span class="badge bg-danger text-white">{{ $permission->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group ">
                                            <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#modalEditRole"
                                                {{-- data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}" --}}
                                                ><i class="bi bi-pencil-square"></i> Edit</a>
                                                {{-- {{ route('admin.konfigurasi.role.delete', $item->id) }} --}}
                                            <a href="#" class="btn btn-danger delete-confirm"><i class="bi bi-trash3"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                <h5 class="modal-title" id="modalInputRoleLabel">Tambah Role in Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- {{ route('admin.konfigurasi.Role.store') }} --}}
                <form action="{{ route('admin.konfigurasi.add-role-in-permission-store') }}" method="POST" id="formRole" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="roles_name">Nama Role</label>
                        <select name="role_id" id="roles_name" class="form-control">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="CheckMain">
                        <label for="CheckMain">Permission All</label>
                    </div>

                    <hr>

                    @foreach ($permission_groups as $group)
                    <div class="row mt-3">
                        <div class="col-4">
                            <div class="form-group">
                                <input type="checkbox" id="checkbox">
                                <label for="checkbox">{{ $group->group_name }}</label>
                            </div>
                        </div>
                        <div class="col-8">
                            @php
                                $permissions = App\Models\User::GetPermissionByGroupName($group->group_name);
                            @endphp
                            @foreach ($permissions as $permission)
                            <div class="form-group">
                                <input type="checkbox" id="checkDefault{{ $permission->id }}" name="permission[]" value="{{ $permission->id }}">
                                <label for="checkDefault{{ $permission->id }}">{{ str_replace(' ', '.', ucwords(str_replace('.', ' ', $permission->name))) }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
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

@push('myscript')
<script>
    $('#CheckMain').click(function(){
        if ($(this).is(':checked')) {
            $('input[type=checkbox]').prop('checked',true)
        }else{
            $('input[type=checkbox]').prop('checked',false)

        }
    });
</script>


@endpush


@endsection
