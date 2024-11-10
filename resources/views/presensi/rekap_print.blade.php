<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Print Laporan Presensi</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/person-check-fill.svg') }}" sizes="32x32" style="color: white">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/person-check-fill.svg') }}" style="color: white">

    <!-- Normalize or reset CSS with your favorite library -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css"> --}}

    <!-- Load paper.css for happy printing -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css"> --}}

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: Legal lanscape
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
        .tabelpresensi th, .tabelpresensi td {
            border: 1px solid black;
            padding: 5px;
            /* text-align: center; Menyelaraskan teks ke tengah */
        }

        .tabelpresensi th {
            background: lightgrey;
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

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body>
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ $src }}" width="80px" height="80px" alt="">
            </td>
            <td>
                <span id="title">
                    REKAP PRESENSI PT. GUARD WARRIOR SECURITY<br>
                    PERIODE {{ strtoupper($months[$bulan]) }} {{ $tahun }}<br>
                </span>
                {{-- <span>Jl. baru no 3, kelurahan Agak Baru, Kota Baru Banget, Provinsi Sangat Baru, 12345</span> --}}
            </td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="{{ $jml_hari }}">Bulan {{ $months[$bulan] }} {{ $tahun }}</th>
            <th rowspan="2">H</th>
            <th rowspan="2">I</th>
            <th rowspan="2">S</th>
            <th rowspan="2">C</th>
            <th rowspan="2">A</th>
        </tr>
        <tr>
            @foreach ( $range_tanggal as $tanggal)
            @if ($tanggal != NULL)
                <th>{{ date("d", strtotime($tanggal)) }}</th>
            @endif
            @endforeach
        </tr>
        @foreach ($rekap as $r)
        <tr>
            <td>{{ $r->nik }}</td>
            <td>{{ $r->nama_lengkap }}</td>

            <?php
                $jml_hadir = 0;
                $jml_izin = 0;
                $jml_sakit = 0;
                $jml_cuti = 0;
                $jml_alpa = 0;
                $color = "";
                for ($i=1; $i <= $jml_hari; $i++) {
                    $tgl = "tgl_".$i;
                    $data_presensi = explode("|", $r->$tgl);

                    if ($r->$tgl != NULL) {
                        $status = $data_presensi[2];
                    } else {
                        $status = "";
                    }

                    if($status == 'hadir'){
                        $jml_hadir +=1;
                        $color = "green";
                    } elseif($status == 'izin'){
                        $jml_izin +=1;
                        $color = "blue";
                    } elseif($status == 'sakit'){
                        $jml_sakit +=1;
                        $color = "red";
                    } elseif($status == 'cuti'){
                        $jml_cuti +=1;
                        $color = "grey";
                    } elseif($status == ''){
                        $jml_alpa +=1;
                        $color = "white";
                    }

            ?>
            <td style="text-align: center; background-color: {{ $color }}; color:white">
                {{ $status }}
            </td>
            <?php
                }
            ?>
            <td style="text-align: center">{{ !empty($jml_hadir) ? $jml_hadir : "" }}</td>
            <td style="text-align: center">{{ !empty($jml_izin) ? $jml_izin : "" }}</td>
            <td style="text-align: center">{{ !empty($jml_sakit) ? $jml_sakit : "" }}</td>
            <td style="text-align: center">{{ !empty($jml_cuti) ? $jml_cuti : "" }}</td>
            <td style="text-align: center">{{ !empty($jml_alpa) ? $jml_alpa : "" }}</td>

        </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 50px">
        <tr>
            {{-- <td></td> --}}
            <td style="text-align: right">Jakarta, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            {{-- <td style="text-align: center; vertical-align:bottom" height="100px" >
                <u>Nama HRD</u><br>
                <i><b>Head HRD</b></i>
            </td> --}}
            <td style="text-align: right; vertical-align:bottom" height="100px" >
                {{-- <u>Nama Direktur</u><br> --}}
                <i><b>PT. GUARD WARRIOR SECURITY</b></i>
            </td>
        </tr>

    </table>

    </section>

</body>

</html>

