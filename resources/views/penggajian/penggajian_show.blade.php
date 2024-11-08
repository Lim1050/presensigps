@extends('layouts.admin.admin_master')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Gaji {{ $penggajian->kode_penggajian }}</h1>
</div>
<div class="card shadow mb-4">

    <div class="card-body">
        <!-- Data Karyawan -->
        <div class="row">
            <div class="col">
                {{-- <div class="card-body"> --}}
                    <h4>Data Karyawan</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="200">NIK</td>
                            <td width="10">:</td>
                            <td>{{ $penggajian->karyawan->nik }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $penggajian->karyawan->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $penggajian->karyawan->jabatan->nama_jabatan }}</td>
                        </tr>
                        <tr>
                            <td>Kantor Cabang</td>
                            <td>:</td>
                            <td>{{ $penggajian->cabang->nama_cabang }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi Penugasan</td>
                            <td>:</td>
                            <td>{{ $penggajian->lokasiPenugasan->nama_lokasi_penugasan }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Hari Kerja</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_hari_kerja }} hari</td> <!-- Pastikan $hariKerjaLokasi didefinisikan di controller -->
                        </tr>
                    </table>
                {{-- </div> --}}
            </div>
        </div>

        <!-- Data Kehadiran -->
        <div class="row">
            <div class="col">
                {{-- <div class="card-body"> --}}
                    <h4>Data Kehadiran</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="200">Jumlah Hari Masuk</td>
                            <td width="10">:</td>
                            <td>{{ $penggajian->jumlah_hari_masuk }} hari</td> <!-- Pastikan $totalKehadiran didefinisikan di controller -->
                        </tr>
                        <tr>
                            <td>Jumlah Ketidakhadiran</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_hari_tidak_masuk }} hari</td> <!-- Pastikan $totalKetidakhadiran didefinisikan di controller -->
                        </tr>
                        <tr>
                            <td>Total Jam Lembur</td>
                            <td>:</td>
                            <td>{{ number_format($penggajian->total_jam_lembur) }} jam</td> <!-- Pastikan $lembur didefinisikan di controller -->
                        </tr>
                    </table>
                {{-- </div> --}}
            </div>
        </div>

        <!-- Komponen Penghasilan -->
        <div class="row">
            <div class="col">
                {{-- <div class="card-body"> --}}
                    <h4>Komponen Penghasilan</h4>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Keterangan</th>
                                <th class="text-right">Jumlah Awal</th>
                                <th class="text-right">Potongan</th>
                                <th class="text-right">Jumlah Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($komponenGaji as $kode => $data)
                                @if($kode !== 'L')
                                    <tr>
                                        <td>{{ $data['jenis_gaji'] }}</td>
                                        <td class="text-right">Rp {{ number_format($komponenGajiKotor[$kode]['jumlah_gaji'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">
                                            @if($kode === 'GT' && isset($komponenPotongan['Potongan Ketidakhadiran']))
                                                Rp {{ number_format($komponenPotongan['Potongan Ketidakhadiran'], 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">Rp {{ number_format($data['jumlah_gaji'], 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach

                            @if(isset($komponenGaji['L']) && $penggajian->total_jam_lembur > 0)
                                <tr>
                                    <td>{{ $komponenGaji['L']['jenis_gaji'] }} × {{ $penggajian->total_jam_lembur }} jam</td>
                                    <td class="text-right">Rp {{ number_format($komponenGajiKotor['L']['jumlah_gaji'] ?? 0, 0, ',', '.') }} (Per jam)</td>
                                    <td class="text-right">-</td>
                                    <td class="text-right">Rp {{ number_format($komponenGaji['L']['jumlah_gaji'], 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            <tr class="font-weight-bold">
                                <td>Total Penghasilan</td>
                                <td class="text-right">Rp {{ number_format($penggajian->total_gaji_kotor, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($komponenPotongan['Potongan Ketidakhadiran'] ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format(array_sum(array_column($komponenGaji, 'jumlah_gaji')), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </div> --}}
            </div>
        </div>


        <!-- Komponen Potongan -->
        <div class="row">
            <div class="col">
                {{-- <div class="card-body"> --}}
                    <h4>Rincian Potongan</h4>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Keterangan</th>
                                <th class="text-right">Rincian</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($komponenPotongan as $nama => $nominal)
                                <tr>
                                    <td>{{ $nama }}</td>
                                    <td class="text-right">
                                        @if($nama === 'Potongan Ketidakhadiran')
                                            Rp {{ number_format($komponenGajiKotor['GT']['jumlah_gaji'] / $penggajian->jumlah_hari_kerja, 0, ',', '.') }} × {{ $penggajian->jumlah_hari_tidak_masuk }} hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bold">
                                <td colspan="2">Total Potongan</td>
                                <td class="text-right">Rp {{ number_format(array_sum($komponenPotongan), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                {{-- </div> --}}
            </div>
        </div>

        <!-- Gaji Bersih -->
        <div class="row">
            <div class="col">
                {{-- <div class="card-body"> --}}
                    <h4>Gaji Bersih</h4>
                    <table class="table table-bordered">
                        <tr class="font-weight-bold">
                            <td>Total Gaji Bersih</td>
                            <td class="text-right">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                {{-- </div> --}}
            </div>
        </div>
        <div class="row">
            <div class="col text-right">
                {{-- <div class="card-body"> --}}
                    <p>Tanggal Gaji : {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('d F Y') }}</p>
                    {{-- <table class="table table-bordered">
                        <tr class="font-weight-bold">
                            <td>Total Gaji Bersih</td>
                            <td class="text-right">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</td>
                        </tr>
                    </table> --}}
                {{-- </div> --}}
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
                <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Cetak Slip Gaji</a>
            </div>
        </div>
    </div>
</div>

{{-- <style>
    .preview-gaji {
        padding: 20px;
    }
    .preview-gaji h3 {
        margin-bottom: 20px;
    }
    .preview-gaji h4 {
        margin-bottom: 15px;
        color: #333;
    }
    .table {
        margin-bottom: 0;
    }
    .thead-light th {
        background-color: #f8f9fa;
    }
    .text-right {
        text-align: right;
    }
    .font-weight-bold {
        font-weight: bold;
    }
    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style> --}}
@endsection
