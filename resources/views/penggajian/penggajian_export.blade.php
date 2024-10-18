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
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
    <h1>Slip Gaji {{ $penggajian->karyawan->nama_lengkap }}</h1>
    <h2>Bulan: {{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('F Y') }}</h2>

    <table>
        <tr>
            <th>NIK</th>
            <td>{{ $penggajian->karyawan->nik }}</td>
        </tr>
        <tr>
            <th>Nama Karyawan</th>
            <td>{{ $penggajian->karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <th>Bulan</th>
            <td>{{$penggajian->bulan }}</td>
        </tr>
        <tr>
            <th>Jumlah Hari Dalam Bulan</th>
            <td>{{$penggajian->jumlah_hari_dalam_bulan }}</td>
        </tr>
        <tr>
            <th>Jumlah Masuk</th>
            <td>{{$penggajian->jumlah_hari_masuk }}</td>
        </tr>
        <tr>
            <th>Jumlah Tidak Masuk</th>
            <td>{{$penggajian->jumlah_hari_tidak_masuk }}</td>
        </tr>
        <tr>
            <th>Gaji Tetap</th>
            <td>Rp {{ number_format($penggajian->gaji_tetap, 2) }}</td>
        </tr>
        <tr>
            <th>Tunjangan Jabatan</th>
            <td>Rp {{ number_format($penggajian->tunjangan_jabatan, 2) }}</td>
        </tr>
        <tr>
            <th>Uang Makan</th>
            <td>Rp {{ number_format($penggajian->uang_makan, 2) }}</td>
        </tr>
        <tr>
            <th>Uang Transportasi</th>
            <td>Rp {{ number_format($penggajian->transportasi, 2) }}</td>
        </tr>
        <tr>
            <th>Sub Total Gaji</th>
            <td>Rp {{ number_format($penggajian->gaji, 2) }}</td>
        </tr>
        <tr>
            <th>Potongan = ((Uang Makan + Transportasi) / Jumlah Hari Dalam Bulan) x Jumlah Tidak Masuk</th>
            <td>
                Rp {{ number_format($penggajian->potongan, 2) }}
            </td>
        </tr>
        <tr>
            <th>Total Gaji = Gaji - Potongan</th>
            <td>Rp {{ number_format($penggajian->total_gaji, 2) }}</td>
        </tr>
        <tr>
            <th>Tanggal Gaji</th>
            <td>{{ \Carbon\Carbon::parse($penggajian->tanggal_gaji)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>
    @if($penggajian->diubah_oleh)
    <div class="info-perubahan">
        <h3>Informasi Perubahan:</h3>
        <p>
            Dirubah pada: {{ \Carbon\Carbon::parse($penggajian->tanggal_perubahan)->translatedFormat('d F Y H:i:s') }}<br>
            Oleh: {{ $penggajian->diubah_oleh }}<br>
            Catatan: {{ $penggajian->catatan_perubahan }}
        </p>
    </div>
    @endif
</body>
</html>
