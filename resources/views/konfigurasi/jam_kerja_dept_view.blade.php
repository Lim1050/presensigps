@extends('layouts.admin.admin_master')
@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dataset Jam Kerja</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="{{ route('admin.konfigurasi.jam-kerja-dept')}}" class="btn btn-danger mb-2"><i class="bi bi-backspace"></i> Kembali</a>

            <div class="row">
                <div class="col-12">
                    {{-- table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                            <tr>
                                <th>Departemen</th>
                                <td>{{ $jam_kerja_dept->nama_departemen }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td>{{ $jam_kerja_dept->nama_cabang }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jam_kerja_dept_detail as $item)
                                <tr>
                                    <td>
                                        {{ $item->hari }}
                                        <input type="hidden" name="hari[]" value="{{ $item->hari }}">
                                    </td>
                                    <td>
                                        {{ $item->nama_jam_kerja }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th colspan="6">Master Jam Kerja</th>
                                </tr>
                                <tr>
                                    <th>Kode Jam Kerja</th>
                                    <th>Nama Jam Kerja</th>
                                    <th>Awal Jam Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Akhir Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jam_kerja as $item)
                                <tr>
                                    <td class="text-center">{{ $item->kode_jam_kerja }}</td>
                                    <td class="text-center">{{ $item->nama_jam_kerja }}</td>
                                    <td class="text-center">{{ $item->awal_jam_masuk }}</td>
                                    <td class="text-center">{{ $item->jam_masuk }}</td>
                                    <td class="text-center">{{ $item->akhir_jam_masuk }}</td>
                                    <td class="text-center">{{ $item->jam_pulang }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection
