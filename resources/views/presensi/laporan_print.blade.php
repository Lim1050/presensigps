<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Print Laporan Presensi</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/person-check-fill.svg') }}" sizes="32x32" style="color: white">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/person-check-fill.svg') }}" style="color: white">

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
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
        .tabelpresensi > tr, th {
        border: 1px solid black;
        padding: 8px;
        background: lightgrey;
        }
        .tabelpresensi td {
        border: 1px solid black;
        padding: 5px;
        font-size: 12px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    @php
        // Function Untuk Menghitung Selisih Jam
        function selisih($jam_masuk, $jam_keluar)
        {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . " jam " . round($sisamenit2) . " menit";
        }
    @endphp
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/person-check-fill-black.svg') }}" width="70" height="70" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper($months[$bulan]) }} {{ $tahun }}<br>
                    PT XYZ<br>
                </span>
                <span>Jl. baru no 3, kelurahan Agak Baru, Kota Baru Banget, Provinsi Sangat Baru, 12345</span>
            </td>
        </tr>
    </table>

    <table class="tabeldatakaryawan">
        <tr>
            <td rowspan="6">
                @php
                    $path = Storage::url("uploads/karyawan/".$karyawan->foto)
                @endphp
                <img src="{{ url($path) }}" class="img-thumbnail" style="width: 100px; height: 150px; object-fit: cover;" alt="...">
            </td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
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
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->nama_departemen }}</td>
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
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
            {{-- <th>Gaji</th> --}}
        </tr>
        @foreach ($presensi as $item)
            <tr style="text-align: center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y", strtotime($item->tanggal_presensi)) }}</td>
                <td>{{ $item->jam_masuk }}</td>
                <td>
                    @php
                        $pathin = Storage::url($item->foto_masuk);
                    @endphp
                    <img src="{{ url($pathin) }}" alt="" style="width: 50px; height: 75px; object-fit: cover;">
                </td>
                <td>{{ $item->jam_keluar != null ? $item->jam_keluar : 'Belum Absen Pulang' }}</td>
                <td>
                    @php
                        $pathout = Storage::url($item->foto_keluar );
                    @endphp
                    @if ($item->foto_keluar != null)
                    <img src="{{ url($pathout) }}" alt="" style="width: 50px; height: 75px; object-fit: cover;">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="75" fill="currentColor" class="bi bi-person-x" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708"/>
                    </svg>
                    @endif
                </td>
                <td>
                    @php
                        $terlambat = selisih($item->jam_masuk_kerja, $item->jam_masuk);
                    @endphp
                    @if ($item->jam_masuk >= $item->jam_masuk_kerja)
                        Terlambat {{ $terlambat }}
                    @else
                        Tepat Waktu
                    @endif
                </td>
                <td>
                    @if ($item->jam_keluar != null)
                        @php
                            $jumlah_jam_kerja = selisih($item->jam_masuk, $item->jam_keluar);
                        @endphp
                    @else
                        @php
                            $jumlah_jam_kerja = 0;
                        @endphp
                    @endif
                    {{ $jumlah_jam_kerja }}
                </td>
                {{-- <td>
                    @php

                        $hasil = hitung_gaji($item->jam_masuk, $item->jam_keluar, 100000);
                        // dd($hasil['gaji'])
                    @endphp
                    {{ $hasil['gaji'] }}
                </td> --}}
            </tr>
        @endforeach
            <tr>
                <td colspan="8">
                    Total Masuk : {{ $total_hari }} hari, <br>
                    Gaji per Hari : Rp {{ $gaji_harian }}, <br>
                    Total Gaji : Rp {{ $total_gaji }}
                </td>
            </tr>
    </table>

    <table width="100%" style="margin-top: 50px">
        <tr>
            <td colspan="2" style="text-align: right">Kota Baru Banget, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align:bottom" height="100px" >
                <u>Ramadhan S Purnama</u><br>
                <i><b>Head HRD</b></i>
            </td>
            <td style="text-align: center; vertical-align:bottom" height="100px" >
                <u>Salim</u><br>
                <i><b>Direktur</b></i>
            </td>
        </tr>
    </table>

    </section>

</body>

</html>
