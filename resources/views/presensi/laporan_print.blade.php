<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Print Laporan Presensi</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/person-check-fill.svg') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/person-check-fill.svg') }}">

    <style>
        @page {
            size: A4 portrait; /* Mengatur ukuran kertas A4 dan orientasi portrait */
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi th {
            border: 1px solid black;
            padding: 8px;
            background: lightgrey;
        }

        .tabelpresensi td {
            border: 1px solid black;
            padding: 5px;
            font-size: 12px;
            /* text-align: center; Menyelaraskan teks ke tengah */
        }

        .tabelpresensi tr:nth-child(even) {
            background-color: #f2f2f2; /* Warna latar belakang untuk baris genap */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .sheet {
                margin: 0;
                padding: 10mm; /* padding di sekitar konten */
            }
        }
    </style>
</head>

<body>
    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ $src }}" width="80px" height="80px" alt="">
                </td>
                <td>
                    <span id="title">
                        LAPORAN PRESENSI PT. GUARD WARRIOR SECURITY<br>
                        PERIODE {{ strtoupper($months[$bulan]) }} {{ $tahun }}<br>
                    </span>
                </td>
            </tr>
        </table>

        <table class="tabeldatakaryawan">
            <tr>
                <td rowspan="6">
                    <img src="{{ $srcProfil }}" class="img-thumbnail" style="width: 100px; height: auto; object-fit: cover;" alt="Foto Karyawan">
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td >:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->nama_jabatan }}</td>
            </tr>
            <tr>
                <td>Cabang</td>
                <td>:</td>
                <td>{{ $karyawan->nama_cabang }}</td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td>{{ $karyawan->no_wa }}</td>
            </tr>
        </table>

        <table class="tabelpresensi">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto Masuk</th>
                <th>Jam Pulang</th>
                <th>Foto Pulang</th>
                <th>Status</th>
                <th>Jumlah Jam</th>
                <th>Lembur</th>
            </tr>
            @foreach ($presensi as $item)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</td>
                    <td>{{ $item->jam_masuk ?? '-' }}</td>
                    <td>
                        {{-- Mengonversi path menjadi base64 jika belum dilakukan di controller --}}
                        @php
                            $fotoMasukPath = storage_path('app/' . $item->foto_masuk); // Ganti dengan public_path jika diperlukan
                            $srcMasuk = file_exists($fotoMasukPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($fotoMasukPath)) : null;
                        @endphp
                        @if ($srcMasuk)
                            <img src="{{ $srcMasuk }}" alt="" style="width: 50px; height: 75px; object-fit: cover;">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->jam_keluar ?? 'Belum Absen Pulang' }}</td>
                    <td>
                        @php
                            $fotoKeluarPath = storage_path('app/' . $item->foto_keluar); // Ganti dengan public_path jika diperlukan
                            $srcKeluar = file_exists($fotoKeluarPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($fotoKeluarPath)) : null;
                        @endphp
                        @if ($srcKeluar)
                            <img src="{{ $srcKeluar }}" alt="" style="width: 50px; height: 75px; object-fit: cover;">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->status }}</td>
                    <td>
                        @if ($item->jam_keluar)
                            @php
                                $tgl_masuk = $item->tanggal_presensi;
                                $tgl_pulang = $item->lintas_hari == 1 ? date('Y-m-d', strtotime('+1 days', strtotime($tgl_masuk))) : $tgl_masuk;
                                $jam_masuk = $tgl_masuk . ' ' . $item->jam_masuk;
                                $jam_pulang = $tgl_pulang . ' ' . $item->jam_keluar;
                                $jumlah_jam_kerja = hitungjamkerja($jam_masuk, $jam_pulang);
                            @endphp
                            {{ $jumlah_jam_kerja }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if ($item->mulai_lembur && $item->selesai_lembur)
                            @php
                                $mulai = \Carbon\Carbon::parse($item->mulai_lembur);
                                $selesai = \Carbon\Carbon::parse($item->selesai_lembur);
                                $durasi = $selesai->diff($mulai);
                                $jam_lembur = $durasi->h;
                                $menit_lembur = $durasi->i;
                            @endphp
                            {{ $jam_lembur }} jam {{ $menit_lembur }} menit
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="9">
                    Total Hari Kerja: {{ $total_hari }} hari<br>
                    Total Jam Lembur: {{ $total_jam_lembur }} jam {{ $total_menit_lembur }} menit
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 50px">
            <tr>
                <td colspan="2" style="text-align: right">{{ $karyawan->nama_cabang }}, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right; vertical-align:bottom" height="100px">
                    <i><b>PT. GUARD WARRIOR SECURITY</b></i>
                </td>
            </tr>
        </table>
    </section>
</body>
</html>
