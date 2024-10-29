<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\PengajuanIzin;
use App\Models\Penggajian;
use App\Models\presensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // $totalIzin = PengajuanIzin::where('nik', $request->nik)
        //                             ->where('status_approved', 1)
        //                             ->whereIn('status', ['izin', 'sakit', 'cuti'])
        //                             ->whereBetween('tanggal_izin_dari', [
        //                                 Carbon::parse($request->tanggal_gaji)->startOfMonth(),
        //                                 Carbon::parse($request->tanggal_gaji)->endOfMonth()
        //                             ])
        //                             ->count();

        // Menghitung total ketidakhadiran
        $totalKetidakhadiran = $totalHariDalamBulan - $totalKehadiran;

        $karyawan = Karyawan::where('nik', $request->nik)->first();
        // $jabatan = $karyawan->kode_jabatan;
        // Mengambil data gaji berdasarkan jabatan
        $gajiTetap = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                    ->where('kode_jenis_gaji', 'GT')
                    ->first();
        $gajiTetap = $gajiTetap ? $gajiTetap->jumlah_gaji : 0;

        $gajiTunjangan = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                            ->where('kode_jenis_gaji', 'TJ')
                            ->first();
        $gajiTunjangan = $gajiTunjangan ? $gajiTunjangan->jumlah_gaji : 0;

        $uangMakan = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                            ->where('kode_jenis_gaji', 'MKN')
                            ->first();
        $uangMakan = $uangMakan ? $uangMakan->jumlah_gaji : 0;

        $transportasi = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                            ->where('kode_jenis_gaji', 'TR')
                            ->first();
        $transportasi = $transportasi ? $transportasi->jumlah_gaji : 0;

        $jumlahUangMakan = $uangMakan * $totalHariDalamBulan;
        $jumlahTransportasi = $transportasi * $totalHariDalamBulan;

        $gajiKaryawan = $gajiTetap + $gajiTunjangan + $jumlahUangMakan + $jumlahTransportasi;

        // Potongan dihitung sebagai gaji per hari dikali dengan jumlah ketidakhadiran
        $potongan = ($uangMakan + $transportasi) * $totalKetidakhadiran;

        // Total gaji setelah potongan
        $totalGaji = $gajiKaryawan - $potongan;

        Penggajian::create([
            'nik' => $request->nik,
            'bulan' => $namaBulan,
            'jumlah_hari_dalam_bulan' => $totalHariDalamBulan,
            'jumlah_hari_masuk' => $totalKehadiran,
            'jumlah_hari_tidak_masuk' => $totalKetidakhadiran,
            'gaji_tetap' => $gajiTetap,
            'tunjangan_jabatan' => $gajiTunjangan,
            'uang_makan' => $jumlahUangMakan,
            'transportasi' => $jumlahTransportasi,
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
        // dd($penggajian->updated_at);
        return view('penggajian.penggajian_show', compact('penggajian'));
    }

    public function ExportPDF($id)
    {
        $penggajian = Penggajian::with('karyawan')->findOrFail($id);

        $pdf = Pdf::loadView('penggajian.penggajian_export', compact('penggajian'));

        return $pdf->download('penggajian_' . $penggajian->karyawan->nama_lengkap . '_' . $penggajian->bulan . '.pdf');
    }

    public function PenggajianEdit($id)
    {
        $penggajian = Penggajian::with('karyawan')->findOrFail($id);
        return view('penggajian.penggajian_edit', compact('penggajian'));
    }

    public function PenggajianUpdate(Request $request, $id)
    {
        $penggajian = Penggajian::findOrFail($id);
        // dd($request->all());
        $validatedData = $request->validate([
            // 'bulan' => 'required|date_format:Y-m',
            'jumlah_hari_dalam_bulan' => 'required|integer',
            'jumlah_hari_masuk' => 'required|integer',
            'jumlah_hari_tidak_masuk' => 'required|integer',
            'gaji_tetap' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
            'uang_makan' => 'required|numeric',
            'transportasi' => 'required|numeric',
            'catatan_perubahan' => 'required|string',
        ]);

        // Calculate gaji
        $gaji = $validatedData['gaji_tetap'] + $validatedData['tunjangan_jabatan'] +
                $validatedData['uang_makan'] + $validatedData['transportasi'];

        // Calculate potongan
        $potongan = (($validatedData['uang_makan'] + $validatedData['transportasi']) / $validatedData['jumlah_hari_dalam_bulan']) * $validatedData['jumlah_hari_tidak_masuk'];

        // Calculate total_gaji
        $total_gaji = $gaji - $potongan;

        // Update penggajian
        $penggajian->update([
            // 'bulan' => $validatedData['bulan'],
            // 'jumlah_hari_dalam_bulan' => $validatedData['jumlah_hari_dalam_bulan'],
            'jumlah_hari_masuk' => $validatedData['jumlah_hari_masuk'],
            'jumlah_hari_tidak_masuk' => $validatedData['jumlah_hari_tidak_masuk'],
            // 'gaji_tetap' => $validatedData['gaji_tetap'],
            // 'tunjangan_jabatan' => $validatedData['tunjangan_jabatan'],
            // 'uang_makan' => $validatedData['uang_makan'],
            // 'transportasi' => $validatedData['transportasi'],
            'gaji' => $gaji,
            'potongan' => $potongan,
            'total_gaji' => $total_gaji,
            'catatan_perubahan' => $validatedData['catatan_perubahan'],
            'diubah_oleh' => Auth::user()->name,
            'updated_at' => Carbon::now(),
            // 'tanggal_gaji' => Carbon::parse($validatedData['bulan'])->endOfMonth(),
        ]);

        return redirect()->route('admin.penggajian')->with('success', 'Data penggajian berhasil diperbarui.');
    }

    public function PenggajianDelete($id)
    {
        $penggajian = Penggajian::findOrFail($id);

        try {
            $penggajian->delete();
            return redirect()->route('admin.penggajian')->with('success', 'Data penggajian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.penggajian')->with('error', 'Gagal menghapus data penggajian. ' . $e->getMessage());
        }
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
