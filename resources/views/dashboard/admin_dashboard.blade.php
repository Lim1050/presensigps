@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('dashboard.dashboard'))
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row mb-4">
    <div class="col mb-2">
        @if (Auth::user()->role == 'admin-cabang')
            <h3 class="h4 mb-0 text-gray-800">Rekap Presensi Hari Ini {{ date("d-m-Y") }} Cabang {{ $cabang->nama_cabang }}</h3>
        @else
            <h3 class="h4 mb-0 text-gray-800">Rekap Presensi Hari Ini {{ date("d-m-Y") }}</h3>
        @endif
    </div>
</div>
<div class="row">
    <!-- Jumlah Karyawan -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Jumlah Karyawan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_karyawan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-vcard fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Hadir -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rekap_presensi->jml_hadir != null ? $rekap_presensi->jml_hadir : 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-fingerprint fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan Sakit -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Karyawan Sakit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rekap_presensi->jml_sakit != null ? $rekap_presensi->jml_sakit : 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-capsule fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan Izin -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Karyawan Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rekap_presensi->jml_izin != null ? $rekap_presensi->jml_izin : 0}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-file-text fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan Cuti -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Karyawan Cuti</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rekap_presensi->jml_cuti != null ? $rekap_presensi->jml_cuti : 0}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar3 fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan Terlambat -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Karyawan Terlambat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rekap_presensi->jml_terlambat != null ? $rekap_presensi->jml_terlambat : 0}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@push('myscript')

@endpush
@endsection
