@extends('layouts.admin.admin_master')
@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        {{-- form cari data karyawan --}}
        <div class="row">
            <div class="col-12">
                <form action="/karyawan" method="GET">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                    <option {{ Request('kode_departemen') == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ $item->nama_departemen }}</option>
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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Foto</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawan as $item)
                            @php
                                $path = Storage::url("uploads/karyawan/".$item->foto)
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $karyawan->firstItem() -1}}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>
                                    <div class="text-center">
                                        @if (empty($item->foto))
                                            <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
                                        @else
                                            <img src="{{ url($path) }}" class="img-thumbnail" style="width: 70px; height: 70px;" alt="...">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->nama_departemen }}</td>
                                <td>{{ $item->no_wa }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
