<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $thr->kode_thr }} {{ $thr->karyawan->nama_lengkap }}</title>
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
    <h1>{{ $thr->kode_thr }} {{ $thr->karyawan->nama_lengkap }}</h1>
    <h2>{{$thr->nama_thr }} {{$thr->tahun }}</h2>

    <table>
        <tr>
            <th>Kode THR</th>
            <td>{{ $thr->kode_thr }}</td>
        </tr>
        <tr>
            <th>NIK</th>
            <td>{{ $thr->karyawan->nik }}</td>
        </tr>
        <tr>
            <th>Nama Karyawan</th>
            <td>{{ $thr->karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <th>Jabatan</th>
            <td>{{ $thr->jabatan->nama_jabatan }}</td>
        </tr>
        <tr>
            <th>Lokasi Penugasan</th>
            <td>{{ $thr->lokasiPenugasan->nama_lokasi_penugasan }}</td>
        </tr>
        <tr>
            <th>Kantor Cabang</th>
            <td>{{ $thr->kantorCabang->nama_cabang }}</td>
        </tr>
        <tr>
            <th>Nama THR</th>
            <td>{{$thr->nama_thr }}</td>
        </tr>
        <tr>
            <th>Tahun</th>
            <td>{{$thr->tahun }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>Rp {{ number_format($thr->jumlah_thr, 2) }}</td>
        </tr>
        <tr>
            <th>Tanggal Penyerahan</th>
            <td>{{ \Carbon\Carbon::parse($thr->tanggal_penyerahan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if ($thr->status == 'Pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($thr->status == 'Disetujui')
                    <span class="badge badge-success">Disetujui</span>
                @elseif ($thr->status == 'Ditolak')
                    <span class="badge badge-danger">Ditolak</span>
                @else
                    <span class="badge badge-secondary">Tidak Diketahui</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Catatan</th>
            <td>{{$thr->notes }}</td>
        </tr>
    </tbody>
</table>

@if($thr->diubah_oleh)
<div class="alert alert-info mt-3">
    <strong>Informasi Perubahan:</strong><br>
    Dirubah pada {{ \Carbon\Carbon::parse($thr->updated_at)->translatedFormat('d F Y H:i:s') }}
    oleh {{ $thr->diubah_oleh }}<br>
    Catatan: {{ $thr->catatan_perubahan }}
</div>
@endif
</body>
</html>
