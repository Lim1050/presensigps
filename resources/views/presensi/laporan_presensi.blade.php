@extends('layouts.admin.admin_master')
@section('content')
@if (Auth::user()->can('laporan.laporan-presensi'))
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    @if (Auth::user()->role == 'admin-cabang' && $cabang)
    <h1 class="h3 mb-0 text-gray-800">Laporan Presensi Cabang {{ $cabang->nama_cabang }}</h1>
    @else
    <h1 class="h3 mb-0 text-gray-800">Laporan Presensi</h1>
    @endif
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.laporan.print') }}" id="form_laporan" method="POST">
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
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="kode_cabang" id="kode_cabang" class="form-control">
                                    @if (Auth::user()->role == 'admin-cabang' && $cabang)
                                        <option value="{{ $cabang->kode_cabang }}">{{ $cabang->nama_cabang }}</option>
                                    @else
                                        <option value="">Pilih Cabang</option>
                                        @foreach ($cabangs as $item)
                                            <option value="{{ $item->kode_cabang }}">{{ $item->nama_cabang }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="nik" id="nik" class="form-control">
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($karyawan as $item)
                                        <option value="{{ $item->nik }}" data-cabang="{{ $item->kode_cabang }}">{{ $item->nama_lengkap }}</option>
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
        document.getElementById('kode_cabang').addEventListener('change', function() {
            var selectedCabang = this.value;
            var karyawanSelect = document.getElementById('nik');

            // Reset karyawan dropdown
            karyawanSelect.innerHTML = '<option value="">Pilih Karyawan</option>'; // Reset options

            // Loop through karyawan options and display only those that match the selected cabang
            @foreach ($karyawan as $item)
                if (selectedCabang === "{{ $item->kode_cabang }}") {
                    var option = document.createElement('option');
                    option.value = "{{ $item->nik }}";
                    option.text = "{{ $item->nama_lengkap }}";
                    karyawanSelect.appendChild(option);
                }
            @endforeach
        });
        $(function(){
            $("#form_laporan").submit(function(e){
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
                } else if (nik==""){
                    Swal.fire({
                    title: 'Oops!',
                    text: 'Pilih Nama Karyawan!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                    }).then((result)=>{
                        $("#nik").focus();
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
@endif
