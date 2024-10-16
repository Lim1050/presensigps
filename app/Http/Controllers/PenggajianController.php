<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\PengajuanIzin;
use App\Models\Penggajian;
use App\Models\presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function PenggajianIndex()
    {
        $penggajian = Penggajian::with('karyawan')->get();
        return view('penggajian.penggajian_index',compact('penggajian'));
    }

    public function PenggajianCreate()
    {
        $karyawan = Karyawan::all();
        return view('penggajian.penggajian_create', compact('karyawan'));
    }

    public function PenggajianStore(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal_gaji' => 'required|date',
        ]);

        $karyawan = Karyawan::where('nik', $request->nik)->first();
        // $jabatan = $karyawan->kode_jabatan;
        // Mengambil data gaji berdasarkan jabatan
        $gajiTetap = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                            ->where('jenis_gaji', 'Gaji Tetap')
                            ->first();
        // dd($gajiTetap->jenis_gaji, $gajiTetap->jumlah_gaji);

        $gajiTunjangan = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                            ->where('jenis_gaji', 'Tunjangan Jabatan')
                            ->first();
        // dd($gajiTetap->jenis_gaji, $gajiTetap->jumlah_gaji, $gajiTunjangan->jenis_gaji, $gajiTunjangan->jumlah_gaji);

        $gajiKaryawan = $gajiTetap->jumlah_gaji + $gajiTunjangan->jumlah_gaji;

        // $gajiKaryawan = Gaji::where('kode_jabatan', operator: $karyawan->kode_jabatan)->sum('jumlah_gaji');
        // dd($gajiKaryawan);

        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Mengambil bulan dari tanggal yang di-parse
        $bulan = Carbon::parse($request->tanggal_gaji)->month;

        // Mendapatkan nama bulan dalam bahasa Indonesia
        $namaBulan = $bulanIndonesia[$bulan];
        // dd($namaBulan);

        // Menghitung jumlah hari dalam bulan tersebut
        $totalHariDalamBulan = Carbon::parse($request->tanggal_gaji)->daysInMonth;

        // Menghitung jumlah kehadiran
        $totalKehadiran = Presensi::where('nik', $request->nik)
                                    ->whereBetween('tanggal_presensi', [
                                        Carbon::parse($request->tanggal_gaji)->startOfMonth(),
                                        Carbon::parse($request->tanggal_gaji)->endOfMonth()
                                    ])
                                    ->count();

        // Menghitung jumlah izin, sakit, dan cuti yang sudah disetujui
        $totalIzin = PengajuanIzin::where('nik', $request->nik)
                                    ->where('status_approved', 1)
                                    ->whereIn('status', ['izin', 'sakit', 'cuti'])
                                    ->whereBetween('tanggal_izin_dari', [
                                        Carbon::parse($request->tanggal_gaji)->startOfMonth(),
                                        Carbon::parse($request->tanggal_gaji)->endOfMonth()
                                    ])
                                    ->count();

        // Menghitung total ketidakhadiran
        $totalKetidakhadiran = $totalHariDalamBulan - $totalKehadiran;

        // Potongan dihitung sebagai gaji per hari dikali dengan jumlah ketidakhadiran
        $potongan = ($gajiKaryawan / $totalHariDalamBulan) * $totalKetidakhadiran;

        // Total gaji setelah potongan
        $totalGaji = $gajiKaryawan - $potongan;

        Penggajian::create([
            'nik' => $request->nik,
            'bulan' => $namaBulan,
            'jumlah_hari_dalam_bulan' => $totalHariDalamBulan,
            'jumlah_hari_masuk' => $totalKehadiran,
            'jumlah_hari_tidak_masuk' => $totalKetidakhadiran,
            'gaji' => $gajiKaryawan,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
            'tanggal_gaji' => $request->tanggal_gaji,
        ]);

        return redirect()->route('admin.penggajian')->with('success', 'Data penggajian berhasil ditambahkan.');
    }

    public function PenggajianShow($id)
    {
        $penggajian = Penggajian::with('karyawan')->findOrFail($id);
        return view('penggajian.penggajian_show', compact('penggajian'));
    }

    public function edit($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $karyawan = Karyawan::all();
        return view('penggajian.edit', compact('penggajian', 'karyawan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik' => 'required',
            'tanggal_gaji' => 'required|date',
        ]);

        $penggajian = Penggajian::findOrFail($id);
        $karyawan = Karyawan::where('nik', $request->nik)->first();
        $gaji = $karyawan->jabatan->gaji->jumlah_gaji;

        // Hitung potongan
        $potongan = $this->hitungPotongan($request->nik, $request->tanggal_gaji);

        // Hitung total gaji
        $totalGaji = $gaji - $potongan;

        $penggajian->update([
            'nik' => $request->nik,
            'gaji' => $gaji,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
            'tanggal_gaji' => $request->tanggal_gaji,
        ]);

        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->delete();

        return redirect()->route('penggajian.index')->with('success', 'Data penggajian berhasil dihapus.');
    }

    private function hitungPotongan($nik, $tanggal_gaji)
    {
        $tanggalMulai = date('Y-m-01', strtotime($tanggal_gaji));
        $tanggalSelesai = date('Y-m-t', strtotime($tanggal_gaji));

        // Hitung jumlah ketidakhadiran
        $jumlahKetidakhadiran = presensi::where('nik', $nik)
            ->whereBetween('tanggal_presensi', [$tanggalMulai, $tanggalSelesai])
            ->where('status', '!=', 'hadir')
            ->count();

        // Tentukan nilai potongan per hari ketidakhadiran (contoh: 100000 per hari)
        $potonganPerHari = 100000;

        return $jumlahKetidakhadiran * $potonganPerHari;
    }
}
