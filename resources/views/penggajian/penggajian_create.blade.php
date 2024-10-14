@extends('layouts.admin.admin_master')
@section('content')
    <div class="container">
        <h1>Tambah Penggajian</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
{{-- {{ route('admin.penggajian.store') }} --}}
        <form action="{{ route('admin.penggajian.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nik">NIK Karyawan</label>
                <select name="nik" id="nik" class="form-control">
                    @foreach($karyawan as $k)
                        <option value="{{ $k->nik }}">{{ $k->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_gaji">Tanggal Gaji</label>
                <input type="date" name="tanggal_gaji" id="tanggal_gaji" class="form-control" value="{{ old('tanggal_gaji') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            {{-- {{ route('penggajian.index') }} --}}
            <a href="{{ route('admin.penggajian') }}" class="btn btn-secondary mt-3">Batal</a>
        </form>
    </div>
@endsection
