@extends('layouts.admin.admin_master')
@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Jam Kerja</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <a href="{{ route('admin.konfigurasi.jam-kerja-dept')}}" class="btn btn-danger mb-2"><i class="bi bi-backspace"></i> Kembali</a>
        <form action="{{ route('admin.konfigurasi.jam-kerja-dept.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($cabang as $item)
                                        <option value="{{ $item->kode_cabang }}">{{ strtoupper($item->nama_cabang) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="kode_departemen" id="kode_departemen" class="form-control" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $item)
                                        <option value="{{ $item->kode_departemen }}">{{ strtoupper($item->nama_departemen) }}</option>
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
                        {{-- <input type="hidden" name="nik" value="{{ $karyawan->nik }}"> --}}
                        <table class="table table-hover table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Senin
                                        <input type="hidden" name="hari[]" value="Senin">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Selasa
                                        <input type="hidden" name="hari[]" value="Selasa">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Rabu
                                        <input type="hidden" name="hari[]" value="Rabu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Kamis
                                        <input type="hidden" name="hari[]" value="Kamis">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Jumat
                                        <input type="hidden" name="hari[]" value="Jumat">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sabtu
                                        <input type="hidden" name="hari[]" value="Sabtu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Minggu
                                        <input type="hidden" name="hari[]" value="Minggu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-control">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jam_kerja as $item)
                                            <option value="{{ $item->kode_jam_kerja }}">{{ $item->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-danger w-100" type="submit"><i class="bi bi-box-arrow-up"></i> Simpan</button>
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
