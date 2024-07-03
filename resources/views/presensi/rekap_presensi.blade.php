@extends('layouts.admin.admin_master')
@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Rekap Presensi</h1>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rekap.print') }}" target="_blank" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">Bulan</option>
                                     @for ($i = 1; $i<=12; $i++)
                                        <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>{{ $months[$i] }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">Tahun</option>
                                    @php
                                        $tahun_mulai = 2000;
                                        $tahun_sekarang = date("Y");
                                    @endphp
                                    @for ($tahun=$tahun_mulai; $tahun <= $tahun_sekarang; $tahun++)
                                        <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="nik" id="nik" class="form-control">
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($karyawan as $item)
                                        <option value="{{ $item->nik }}">{{ $item->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <button type="submit" name="cetak" class="btn btn-primary w-100">
                                    <i class="bi bi-printer"></i> Cetak
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <button type="submit" name="export_excel" class="btn btn-success w-100">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
@endsection
