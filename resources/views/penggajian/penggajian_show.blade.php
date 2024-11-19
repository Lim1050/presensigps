@extends('layouts.admin.admin_master')
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Gaji {{ $penggajian->kode_penggajian }}
                                    @if ($penggajian->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif ($penggajian->status == 'disetujui')
                                        <span class="badge badge-primary">Disetujui</span>
                                    @elseif ($penggajian->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif ($penggajian->status == 'dibayar')
                                        <span class="badge badge-success">Dibayar</span>
                                    @endif
                                </h1>
</div>
<div class="card shadow mb-4">

    <div class="card-body">
        <!-- Data Karyawan -->
        <div class="row">
            <div class="col">
                <h4>Data Karyawan</h4>
                <div class="table-responsive">
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
                </div>
            </div>
        </div>

        <!-- Data Kehadiran -->
        <div class="row">
            <div class="col">
                <h4>Data Kehadiran</h4>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200">Jumlah Hadir</td>
                            <td width="10">:</td>
                            <td>{{ $penggajian->kehadiran_murni }} hari</td>
                        </tr>
                        <tr>
                            <td>Jumlah Sakit</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_sakit }} hari</td>
                        </tr>
                        <tr>
                            <td>Jumlah Izin</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_izin }} hari</td>
                        </tr>
                        <tr>
                            <td>Jumlah Cuti</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_cuti }} hari</td>
                        </tr>
                        <tr>
                            <td>Total Kehadiran</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_hari_masuk }} hari</td>
                        </tr>
                        <tr>
                            <td>Jumlah Tidak Hadir Dengan Keterangan</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_isc }} hari</td>
                        </tr>
                        <tr>
                            <td>Jumlah Tidak Hadir Tanpa Keterangan</td>
                            <td>:</td>
                            <td>{{ $penggajian->jumlah_hari_tidak_masuk }} hari</td>
                        </tr>
                        <tr>
                            <td>Total Jam Lembur</td>
                            <td>:</td>
                            <td>{{ number_format($penggajian->total_jam_lembur) }} jam</td> <!-- Pastikan $lembur didefinisikan di controller -->
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Komponen Penghasilan -->
        <div class="row">
            <div class="col">
                <h4>Komponen Penghasilan</h4>
                <div class="table-responsive">
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
                </div>
            </div>
        </div>


        <!-- Komponen Potongan -->
        <div class="row">
            <div class="col">
                <h4>Rincian Potongan</h4>
                <div class="table-responsive">
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
                </div>
            </div>
        </div>

        <!-- Gaji Bersih -->
        <div class="row">
            <div class="col">
                <h4>Gaji Bersih</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr class="font-weight-bold">
                            <td>Total Gaji Bersih</td>
                            <td class="text-right">Rp {{ number_format($penggajian->gaji_bersih, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col alert alert-success">
                <h5>Informasi Gaji </h5>
                    <p>
                        Status : @if ($penggajian->status == 'draft')
                                    <span class="badge badge-secondary">Draft</span>
                                @elseif ($penggajian->status == 'disetujui')
                                    <span class="badge badge-primary">Disetujui</span>
                                @elseif ($penggajian->status == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @elseif ($penggajian->status == 'dibayar')
                                    <span class="badge badge-success">Dibayar</span>
                                @endif <br>
                        Tanggal Gaji : {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('d F Y') }} <br>
                        Catatan : {{ $penggajian->catatan }} <br>
                        Diproses Oleh : {{ $penggajian->diproses_oleh }}
                    </p>
            </div>
        </div>

        @if($penggajian->diubah_oleh)
        <div class="row">
            <div class="col alert alert-warning">
                <h5>Informasi Perubahan :</h5>
                <p>
                    Dirubah pada : {{ \Carbon\Carbon::parse($penggajian->waktu_perubahan)->translatedFormat('d F Y H:i:s') }}<br>
                    Diubah Oleh : {{ $penggajian->diubah_oleh }}<br>
                    Catatan Perubahan : {{ $penggajian->alasan_perubahan }}
                </p>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col">
                <a href="{{ route('admin.penggajian') }}" class="btn btn-danger">Kembali</a>
                <a href="{{ route('admin.penggajian.export', $penggajian->kode_penggajian) }}" class="btn btn-danger">Cetak Slip Gaji</a>
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
