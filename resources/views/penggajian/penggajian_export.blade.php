<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Slip Gaji {{ $penggajian->karyawan->nama_lengkap }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                margin: 20px; /* Margin untuk body */
            }

            #title {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 18px;
                font-weight: bold;
            }

            h1 {
                font-size: 1.75rem; /* Ukuran font untuk h1 */
                font-weight: 500; /* Berat font */
                /* margin-bottom: 0; Menghilangkan margin bawah */
                /* color: #343a40; Warna abu-abu gelap */
            }

            h4 {
                font-size: 1.5rem; /* Ukuran font untuk h4 */
                margin-top: 20px; /* Margin atas */
                margin-bottom: 10px; /* Margin bawah */
            }

            p {
                font-size: 1rem; /* Ukuran font untuk p */
                margin: 0; /* Menghilangkan margin */
            }

            table {
                width: 100%;
                border-collapse: collapse; /* Menghilangkan jarak antar border */
                margin-bottom: 20px; /* Margin bawah untuk jarak antar tabel */
            }

            th, td {
                border: 1px solid #ddd; /* Border untuk sel */
                padding: 8px; /* Padding untuk sel */
                text-align: left; /* Rata kiri untuk teks */
            }

            th {
                background-color: #f2f2f2; /* Warna latar belakang untuk header tabel */
                font-weight: bold; /* Berat font untuk header */
            }

            .table-bordered {
                border: 1px solid #ddd; /* Border untuk tabel */
            }

            .table-borderless td, .table-borderless th {
                border: none; /* Menghilangkan border untuk tabel borderless */
            }

            .text-right {
                text-align: right; /* Rata kanan untuk teks */
            }

            .font-weight-bold {
                font-weight: bold; /* Berat font bold */
            }

            .alert {
                padding: 15px; /* Padding untuk alert */
                border-radius: 4px; /* Sudut untuk alert */
                margin-top: 20px; /* Margin atas untuk jarak */
            }

            .alert-success {
                background-color: #d4edda; /* Warna latar belakang untuk alert success */
                border-color: #c3e6cb; /* Warna border untuk alert success */
                color: #155724; /* Warna teks untuk alert success */
            }

            .alert-warning {
                background-color: #fff3cd; /* Warna latar belakang untuk alert warning */
                border-color: #ffeeba; /* Warna border untuk alert warning */
                color: #856404; /* Warna teks untuk alert warning */
            }

            .badge {
                display: inline-block;
                color: white; /* Warna teks badge */
                padding: .25em .4em;
                padding-top: 0.25em;
                padding-right: 0.4em;
                padding-bottom: 0.25em;
                padding-left: 0.4em;
                font-size: 75%;
                font-weight: 700;
                line-height: 1;
                text-align: center;
                white-space: nowrap;
                white-space-collapse: collapse;
                text-wrap-mode: nowrap;
                vertical-align: baseline;
                border-radius: .25rem;
                border-top-left-radius: 0.25rem;
                border-top-right-radius: 0.25rem;
                border-bottom-right-radius: 0.25rem;
                border-bottom-left-radius: 0.25rem;
                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                transition-behavior: normal, normal, normal, normal;
                transition-duration: 0.15s, 0.15s, 0.15s, 0.15s;
                transition-timing-function: ease-in-out, ease-in-out, ease-in-out, ease-in-out;
                transition-delay: 0s, 0s, 0s, 0s;
            }

            .badge-secondary {
                background-color: #6c757d; /* Warna latar belakang badge secondary */
            }

            .badge-primary {
                background-color: #007bff; /* Warna latar belakang badge primary */
            }

            .badge-danger {
                background-color: #dc3545; /* Warna latar belakang badge danger */
            }

            .badge-success {
                background-color: #28a745; /* Warna latar belakang badge success */
            }

            .info-perubahan {
                margin-top: 20px;
                padding: 10px;
                background-color: #e6f3ff;
                border: 1px solid #b8daff;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <table class="table table-borderless" style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ $src }}" width="80px" height="80px" alt="">
                </td>
                <td>
                    <h1>
                        PT. GUARD WARRIOR SECURITY
                    </h1>
                    {{-- <span>Jl. baru no 3, kelurahan Agak Baru, Kota Baru Banget, Provinsi Sangat Baru, 12345</span> --}}
                </td>
            </tr>
        </table>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <p>Slip Gaji {{ $penggajian->kode_penggajian }}
                @if ($penggajian->status == 'draft')
                    <span class="badge badge-secondary">Draft</span>
                @elseif ($penggajian->status == 'disetujui')
                    <span class="badge badge-primary">Disetujui</span>
                @elseif ($penggajian->status == 'ditolak')
                    <span class="badge badge-danger">Ditolak</span>
                @elseif ($penggajian->status == 'dibayar')
                    <span class="badge badge-success">Dibayar</span>
                @endif
            </p>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <p>Bulan: {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('F Y') }}</p>

                <!-- Data Karyawan -->
                <div class="row">
                    <div class="col">
                        <h4>Data Karyawan</h4>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200">NIK</td>
                                    <td  width="10">:</td>
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

                <!-- Rincian Potongan -->
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
                        <h4>Informasi Gaji</h4>
                        <p>
                            Status: @if ($penggajian->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif ($penggajian->status == 'disetujui')
                                        <span class="badge badge-primary">Disetujui</span>
                                    @elseif ($penggajian->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif ($penggajian->status == 'dibayar')
                                        <span class="badge badge-success">Dibayar</span>
                                    @endif <br>
                            Tanggal Gaji : {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('d F Y') }} <br>
                            Diproses Oleh: {{ $penggajian->diproses_oleh }}<br>
                            Catatan: {{ $penggajian->catatan }}
                        </p>
                    </div>
                </div>

                @if($penggajian->diubah_oleh)
                <div class="row">
                    <div class="col alert alert-warning">
                        <h4>Informasi Perubahan:</h4>
                        <p>
                            Dirubah pada: {{ \Carbon\Carbon::parse($penggajian->waktu_perubahan)->translatedFormat('d F Y H:i:s') }}<br>
                            Diubah Oleh: {{ $penggajian->diubah_oleh }}<br>
                            Catatan Perubahan: {{ $penggajian->alasan_perubahan }}
                        </p>
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="row">
            <div class="col">
                    <table class="table table-borderless" width="100%" style="margin-top: 50px">
                    <tr>
                        <td colspan="2" style="text-align: right">{{ $penggajian->cabang->nama_cabang }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; vertical-align:bottom" height="100px" >
                            {{-- <u>Nama Direktur</u><br> --}}
                            <i><b>PT. GUARD WARRIOR SECURITY</b></i>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>


