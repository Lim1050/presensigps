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
        .tabelpresensi tr th {
        border: 1px solid black;
        padding: 8px;
        background: lightgrey;
        font-size: 9px;
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
<body class="A4 landscape">
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
                    REKAP PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper($months[$bulan]) }} {{ $tahun }}<br>
                    PT XYZ<br>
                </span>
                <span>Jl. baru no 3, kelurahan Agak Baru, Kota Baru Banget, Provinsi Sangat Baru, 12345</span>
            </td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">Total Hadir</th>
            <th rowspan="2">Total Terlambat</th>
        </tr>
        <tr>
            @for ($i=1; $i <= 31; $i++)
            <th>{{ $i }}</th>
            @endfor
        </tr>
        @foreach ($rekap as $r)
        <tr>
            <td>{{ $r->nik }}</td>
            <td>{{ $r->nama_lengkap }}</td>
            <?php
                $total_hadir = 0;
                $total_terlambat = 0;
                for($i=1; $i<=31; $i++){
                    $tanggal = "tanggal_" . $i;

                    if (empty($r->$tanggal)) {
                        $hadir = ['',''];
                        $total_hadir +=0;
                    } else {
                        $hadir = explode("-", $r->$tanggal);
                        $total_hadir += 1;
                        if ($hadir[0] > "09:00:00" ) {
                            $total_terlambat += 1;
                        }
                    }
            ?>
            <td style="font-size: 9px">
                <span style="color: {{ $hadir[0] > "09:00:00" ? "red" : ""}}"  >{{ $hadir[0] }}</span></br>
                <span style="color: {{ $hadir[0] < "17:00:00" ? "red" : ""}}"  >{{ $hadir[1] }}</span>
            </td>

            <?php
                }
            ?>
            <td>{{ $total_hadir }}</td>
            <td>{{ $total_terlambat }}</td>

        </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 50px">
        <tr>
            <td></td>
            <td style="text-align: center">Kota Baru Banget, {{ date('d-m-Y') }}</td>
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

