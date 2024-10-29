@extends('layouts.admin.admin_master')
@section('content')
    <div class="container">
        <h1>Edit THR</h1>

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <form action="{{ route('admin.thr.update', $thr->kode_thr) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="nik">Karyawan</label>
                            <input type="text" id="nik" class="form-control" value="{{ $thr->karyawan->nik }} - {{ $thr->karyawan->nama_lengkap }}" disabled>
                            <input type="hidden" name="nik" value="{{ $thr->nik }}">
                        </div>
                    </div>

                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="kode_jabatan">Jabatan</label>
                            <input type="text" id="kode_jabatan" class="form-control" value="{{ $thr->jabatan->nama_jabatan }}" disabled>
                            <input type="hidden" name="kode_jabatan" value="{{ $thr->kode_jabatan }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="kode_lokasi_penugasan">Lokasi Penugasan</label>
                            <input type="text" id="kode_lokasi_penugasan" class="form-control" value="{{ $thr->lokasiPenugasan->nama_lokasi_penugasan }}" disabled>
                            <input type="hidden" name="kode_lokasi_penugasan" value="{{ $thr->kode_lokasi_penugasan }}">
                        </div>
                    </div>

                    <div class="col-md-6 sm-3">
                        <div class="form-group">
                            <label for="kode_cabang">Kantor Cabang</label>
                            <input type="text" id="kode_cabang" class="form-control" value="{{ $thr->kantorCabang->nama_cabang }}" disabled>
                            <input type="hidden" name="kode_cabang" value="{{ $thr->kode_cabang }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_thr">Nama THR</label>
                            <input type="text" name="nama_thr" id="nama_thr" class="form-control @error('nama_thr') is-invalid @enderror" value="{{ old('nama_thr', $thr->nama_thr) }}" required>
                            @error('nama_thr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $thr->tahun) }}" required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jumlah_thr">Jumlah THR</label>
                            <input type="number" name="jumlah_thr" id="jumlah_thr" class="form-control @error('jumlah_thr') is-invalid @enderror" value="{{ old('jumlah_thr', $thr->jumlah_thr) }}" step="0.01" required>
                            @error('jumlah_thr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_penyerahan">Tanggal Penyerahan</label>
                            <input type="date" name="tanggal_penyerahan" id="tanggal_penyerahan" class="form-control @error('tanggal_penyerahan') is-invalid @enderror" value="{{ old('tanggal_penyerahan', $thr->tanggal_penyerahan ? date('Y-m-d', strtotime($thr->tanggal_penyerahan)) : '') }}" required>
                            @error('tanggal_penyerahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Pending" {{ old('status', $thr->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Disetujui" {{ old('status', $thr->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Ditolak" {{ old('status', $thr->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Catatan</label>
                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $thr->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="catatan_perubahan">Catatan Perubahan</label>
                    <textarea name="catatan_perubahan" id="catatan_perubahan" class="form-control @error('catatan_perubahan') is-invalid @enderror">{{ old('catatan_perubahan') }}</textarea>
                    @error('catatan_perubahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class ="btn btn-danger">Simpan</button>
                    <a href="{{ route('admin.thr') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@endsection
