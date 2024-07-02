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
        </tr>
        @foreach ($presensi as $item)
            <tr>
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
                    Belum Absen Pulang
                    @endif
                </td>
                <td>
                     @if ($item->jam_masuk >= '09:00')
                        Terlambat
                    @else
                        Tepat Waktu
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    </section>

</body>

</html>
