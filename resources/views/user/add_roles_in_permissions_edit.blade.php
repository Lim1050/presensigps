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
    <h1 class="h3 mb-0 text-gray-800">Edit Konfigurasi Role in Permission</h1>
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
                <a href="{{ route('admin.konfigurasi.add-role-in-permission') }}" class="btn btn-danger"><i class="bi bi-backspace"></i> Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.konfigurasi.add-role-in-permission-update', $roles->id) }}" method="POST" id="formRole" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="roles_name">Nama Role</label>
                        <h4>{{ ucwords($roles->name) }}</h4>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="CheckMain">
                        <label for="CheckMain">Permission All</label>
                    </div>

                    <hr>

                    @foreach ($permission_groups as $group)
                    <div class="row mt-3">
                        <div class="col-4">

                            @php
                                $permissions = App\Models\User::GetPermissionByGroupName($group->group_name);
                            @endphp

                            <div class="form-group">
                                <input type="checkbox" id="checkbox" {{ App\Models\User::roleHasPermissions($roles, $permissions) ? 'checked' : '' }}>
                                <label for="checkbox">{{ $group->group_name }}</label>
                            </div>
                        </div>
                        <div class="col-8">

                            @foreach ($permissions as $permission)
                            <div class="form-group">
                                <input type="checkbox" id="checkDefault{{ $permission->id }}" name="permission[]" value="{{ $permission->name }}" {{ $roles->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <label for="checkDefault{{ $permission->id }}">{{ str_replace(' ', '.', ucwords(str_replace('.', ' ', $permission->name))) }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send"></i> Perbarui</button>
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
