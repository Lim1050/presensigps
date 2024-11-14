@extends('layouts.admin.admin_master')
@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Jam Kerja</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="{{ route('admin.konfigurasi.jam-kerja-dept')}}" class="btn btn-danger mb-2"><i class="bi bi-backspace"></i> Kembali</a>
        <form action="{{ route('admin.konfigurasi.jam-kerja-dept.update', $jam_kerja_dept->kode_jk_dept) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control" disabled>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $item)
                                        <option {{ $jam_kerja_dept->kode_cabang == $item->kode_cabang ? 'selected' : '' }} value="{{ $item->kode_cabang }}">{{ strtoupper($item->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control" disabled>
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                        <option {{ $jam_kerja_dept->kode_departemen == $item->kode_departemen ? 'selected' : '' }} value="{{ $item->kode_departemen }}">{{ strtoupper($item->nama_departemen) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                                        <select name="kode_jam_kerja[]" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $jk)
                                            <option {{ $item->kode_jam_kerja == $jk->kode_jam_kerja ? 'selected' : ''}} value="{{ $jk->kode_jam_kerja }}">{{ $jk->kode_jam_kerja }} - {{ $jk->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button class="btn btn-danger w-100" type="submit"><i class="bi bi-box-arrow-up"></i> Update</button>
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
        </form>
    </div>
</div>

@endsection
@push('myscript')
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-popup', // Kelas kustom untuk popup
                    title: 'custom-title', // Kelas kustom untuk judul
                    content: 'custom-content', // Kelas kustom untuk konten
                    confirmButton: 'custom-confirm-button' // Kelas kustom untuk tombol konfirmasi
                }
            });
        });
    </script>
@endif
@endpush
