<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $thr->kode_thr }} {{ $thr->karyawan->nama_lengkap }}</title>
    <style>
        body
        {
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

        .info-perubahan {
            margin-top: 20px;
            padding: 10px;
            background-color: #e6f3ff;
            border: 1px solid #b8daff;
            border-radius: 4px;
        }
        .alert-warning {
                background-color: #fff3cd; /* Warna latar belakang untuk alert warning */
                border-color: #ffeeba; /* Warna border untuk alert warning */
                color: #856404; /* Warna teks untuk alert warning */
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
<div class="alert alert-warning mt-3">
    <strong>Informasi Perubahan:</strong><br>
    Dirubah pada {{ \Carbon\Carbon::parse($thr->updated_at)->translatedFormat('d F Y H:i:s') }}
    oleh {{ $thr->diubah_oleh }}<br>
    Catatan: {{ $thr->catatan_perubahan }}
</div>
@endif
</body>
</html>
