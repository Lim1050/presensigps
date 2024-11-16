@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('laporan.rekap-presensi'))
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Rekap Presensi</h1>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.rekap.print') }}" id="form_rekap" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-control" required>
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
                                <select name="tahun" id="tahun" class="form-control" required>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control">
                                    <option value="">Semua Kantor Cabang</option>
                                    @foreach ($kantor_cabang as $item)
                                        <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <button type="submit" name="cetak" class="btn btn-primary w-100">
                                    <i class="bi bi-printer"></i> Cetak
                                </button>
                            </div>
                        </div>
                        {{-- <div class="col-6">
                            <div class="form-group">
                                <button type="submit" name="export_excel" class="btn btn-success w-100">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
                                </button>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
    <script>
        $(function(){
            $("#form_rekap").submit(function(e){
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                var nik = $("#nik").val();
                if(bulan==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Pilih Bulan!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                    }).then((result)=>{
                        $("#bulan").focus();
                    });
                    return false;
                } else if (tahun==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Pilih Tahun!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                    }).then((result)=>{
                        $("#tahun").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
@endif
