<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\LokasiPenugasan;
use App\Models\PengajuanIzin;
use App\Models\Penggajian;
use App\Models\Potongan;
use App\Models\presensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenggajianController extends Controller
{
    public function PenggajianIndex(Request $request)
    {
        try {
            // Query dasar dengan relasi yang diperlukan
            $query = Penggajian::with(['karyawan', 'cabang', 'lokasiPenugasan'])
                ->orderBy('created_at', 'desc');

            // Filter berdasarkan periode (bulan)
            if ($request->filled('bulan')) {
                $query->where('kode_penggajian', 'like', 'PG-' . $request->bulan . '%');
            }

            // Filter berdasarkan cabang
            if ($request->filled('kode_cabang')) {
                $query->where('kode_cabang', $request->kode_cabang);
            }

            // Filter berdasarkan lokasi penugasan
            if ($request->filled('kode_lokasi_penugasan')) {
                $query->where('kode_lokasi_penugasan', $request->kode_lokasi_penugasan);
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan pencarian (NIK atau nama karyawan)
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->whereHas('karyawan', function ($q) use ($searchTerm) {
                    $q->where('nik', 'like', "%{$searchTerm}%")
                        ->orWhere('nama_lengkap', 'like', "%{$searchTerm}%");
                });
            }

            // Ambil data penggajian dengan pagination
            $penggajian = $query->paginate(10);

            // Data untuk dropdown filter
            $cabangList = Cabang::orderBy('nama_cabang')->pluck('nama_cabang', 'kode_cabang');
            $lokasiPenugasanList = LokasiPenugasan::orderBy('nama_lokasi_penugasan')->pluck('nama_lokasi_penugasan', 'kode_lokasi_penugasan');

            // Status untuk dropdown
            $statusList = [
                'draft' => 'Draft',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'dibayar' => 'Dibayar'
            ];

            // Generate daftar bulan (6 bulan terakhir)
            $bulanList = [];
            for ($i = 0; $i < 6; $i++) {
                $date = now()->subMonths($i);
                $bulanList[$date->format('Ym')] = $date->format('F Y');
            }

            // Hitung total-total
            $totalGajiBersih = $penggajian->sum('gaji_bersih');
            $totalGajiKotor = $penggajian->sum('total_gaji_kotor');
            $totalPotongan = $penggajian->sum('total_potongan');

            return view('penggajian.penggajian_index', compact(
                'penggajian',
                'cabangList',
                'lokasiPenugasanList',
                'statusList',
                'bulanList',
                'totalGajiBersih',
                'totalGajiKotor',
                'totalPotongan'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function PenggajianCreate()
    {
        $karyawan = Karyawan::all();
        $cabang = Cabang::all();
        $lokasi_penugasan = LokasiPenugasan::all();
        return view('penggajian.penggajian_create', compact('karyawan', 'cabang', 'lokasi_penugasan'));
    }

    public function previewGaji(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data yang diterima untuk preview gaji:', $request->all());

            // Validasi input
            $request->validate([
                'nik' => 'required',
                'tanggal_gaji' => 'required|date',
                'kode_cabang' => 'required',
                'kode_lokasi_penugasan' => 'required',
            ]);

            $tanggalGaji = Carbon::parse($request->tanggal_gaji);
            $bulan = $tanggalGaji->month;
            $tahun = $tanggalGaji->year;

            // Ambil data karyawan
            $karyawan = Karyawan::with(['jabatan', 'Cabang', 'lokasiPenugasan'])
                ->where('nik', $request->nik)
                ->firstOrFail();

            // Ambil jumlah hari kerja dari lokasi penugasan
            $hariKerjaLokasi = $karyawan->lokasiPenugasan->jumlah_hari_kerja;

            // Ambil komponen gaji
            $komponenGaji = Gaji::with('jenisGaji')
                ->where('kode_jabatan', $karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get();
                // dd($komponenGaji);

            $komponenGajiArray = $komponenGaji->mapWithKeys(function ($item) {
                return [
                    $item->kode_jenis_gaji => [
                        'jumlah_gaji' => $item->jumlah_gaji,
                        'jenis_gaji' => $item->jenisGaji->jenis_gaji ?? $item->kode_jenis_gaji
                        ]
                    ];

            })->toArray();


            $komponenGajiAsli = $komponenGaji->pluck('jumlah_gaji', 'kode_jenis_gaji')->toArray();

            // Ambil komponen potongan
            $komponenPotongan = Potongan::with(relations: 'jenisPotongan')
                ->where('kode_jabatan', $karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        $item->jenisPotongan->jenis_potongan ?? $item->nama_potongan => $item->jumlah_potongan
                    ];
                })
                ->toArray();

            // Hitung kehadiran
            $presensi = Presensi::where('nik', $karyawan->nik)
                ->whereYear('tanggal_presensi', $tahun)
                ->whereMonth('tanggal_presensi', $bulan)
                ->get();

            $totalKehadiran = $presensi->whereIn('status', ['hadir', 'izin', 'sakit', 'cuti'])->count();
            $totalKetidakhadiran = $hariKerjaLokasi - $totalKehadiran;

            // Hitung potongan gaji untuk ketidakhadiran
            if ($totalKetidakhadiran > 0 && isset($komponenGajiArray['GT'])) {
                $potonganPerHari = $komponenGajiArray['GT']['jumlah_gaji'] / $hariKerjaLokasi;
                $totalPotonganKetidakhadiran = $potonganPerHari * $totalKetidakhadiran;
                $komponenGajiArray['GT']['jumlah_gaji'] -= $totalPotonganKetidakhadiran;
                $komponenPotongan['Potongan Ketidakhadiran'] = $totalPotonganKetidakhadiran;
            }

            // Hitung lembur
            $lembur = Lembur::where('nik', $karyawan->nik)
                ->whereYear('tanggal_presensi', $tahun)
                ->whereMonth('tanggal_presensi', $bulan)
                ->where('status', 'disetujui')
                ->sum('durasi_menit');

            // Konversi menit ke jam
            $jamLembur = $lembur / 60;

            // Hitung jumlah lembur berdasarkan tarif per jam (kode_jenis_gaji 'L')
            $tarifLemburPerJam = $komponenGajiAsli['L'] ?? 0;
            $jumlahLembur = $jamLembur * $tarifLemburPerJam;

            // Tambahkan lembur ke komponen gaji
            if (isset($komponenGajiArray['L'])) {
                $komponenGajiArray['L']['jumlah_gaji'] = $jumlahLembur;
            }

            // Hitung total
            $totalGajiAsli = array_sum($komponenGajiAsli);
            $totalGaji = array_sum(array_column($komponenGajiArray, 'jumlah_gaji'));
            $totalPotongan = array_sum($komponenPotongan);
            $gajiBersih = $totalGaji - $totalPotongan;

            return view('penggajian.preview_gaji', compact(
                'karyawan',
                'komponenGajiAsli',
                'komponenGajiArray',
                'komponenPotongan',
                'totalGajiAsli',
                'totalGaji',
                'totalPotongan',
                'gajiBersih',
                'totalKehadiran',
                'totalKetidakhadiran',
                'lembur',
                'hariKerjaLokasi'
            ))->render();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function PenggajianStore(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'nik' => 'required',
                'tanggal_gaji' => 'required|date',
                'kode_cabang' => 'required',
                'kode_lokasi_penugasan' => 'required',
            ]);

            $tanggalGaji = Carbon::parse($request->tanggal_gaji);
            $bulan = $tanggalGaji->format('Y-m');

            // Cek apakah sudah ada penggajian untuk karyawan di bulan yang sama
            $existingPenggajian = Penggajian::where('nik', $request->nik)
                ->where('bulan', $bulan)
                ->first();

            if ($existingPenggajian) {
                throw new \Exception('Penggajian untuk karyawan ini di bulan ' . $bulan . ' sudah ada.');
            }

            // Ambil data karyawan
            $karyawan = Karyawan::with(['jabatan', 'Cabang', 'lokasiPenugasan'])
                ->where('nik', $request->nik)
                ->firstOrFail();

            // Ambil jumlah hari kerja dari lokasi penugasan
            $hariKerjaLokasi = $karyawan->lokasiPenugasan->jumlah_hari_kerja;

            // Ambil komponen gaji
            $komponenGaji = Gaji::with('jenisGaji')
                ->where('kode_jabatan', $karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get();

            $komponenGajiArray = $komponenGaji->mapWithKeys(function ($item) {
                return [
                    $item->kode_jenis_gaji => [
                        'jumlah_gaji' => $item->jumlah_gaji,
                        'jenis_gaji' => $item->jenisGaji->jenis_gaji ?? $item->kode_jenis_gaji
                    ]
                ];
            })->toArray();

            $komponenGajiAsli = $komponenGaji->pluck('jumlah_gaji', 'kode_jenis_gaji')->toArray();

            // Ambil komponen potongan
            $komponenPotongan = Potongan::with('jenisPotongan')
                ->where('kode_jabatan', $karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        $item->jenisPotongan->jenis_potongan ?? $item->nama_potongan => $item->jumlah_potongan
                    ];
                })
                ->toArray();

            // Hitung kehadiran
            $presensi = Presensi::where('nik', $karyawan->nik)
                ->whereYear('tanggal_presensi', $tanggalGaji->year)
                ->whereMonth('tanggal_presensi', $tanggalGaji->month)
                ->get();

            $totalKehadiran = $presensi->whereIn('status', ['hadir', 'izin', 'sakit', 'cuti'])->count();
            $totalKetidakhadiran = $hariKerjaLokasi - $totalKehadiran;

            // Hitung total gaji kotor dari komponen asli (sebelum potongan)
            $totalGajiKotor = array_sum($komponenGajiAsli);


            // Hitung lembur
            $lembur = Lembur::where('nik', $karyawan->nik)
                ->whereYear('tanggal_presensi', $tanggalGaji->year)
                ->whereMonth('tanggal_presensi', $tanggalGaji->month)
                ->where('status', 'disetujui')
                ->sum('durasi_menit');

            // Konversi menit ke jam
            $jamLembur = $lembur / 60;

            // Hitung jumlah lembur sebagai komponen terpisah
            $tarifLemburPerJam = $komponenGajiAsli['L'] ?? 0;
            $jumlahLembur = $jamLembur * $tarifLemburPerJam;

            // Tambahkan komponen lembur ke array komponen gaji
            $komponenGajiArray['L'] = [
                'jumlah_gaji' => $jumlahLembur,
                'jenis_gaji' => 'Lembur'
            ];

            // Update komponen gaji untuk tampilan dan perhitungan detail
            if ($totalKetidakhadiran > 0 && isset($komponenGajiArray['GT'])) {
                $potonganPerHari = $komponenGajiArray['GT']['jumlah_gaji'] / $hariKerjaLokasi;
                $totalPotonganKetidakhadiran = $potonganPerHari * $totalKetidakhadiran;
                $komponenGajiArray['GT']['jumlah_gaji'] -= $totalPotonganKetidakhadiran;
                $komponenPotongan['Potongan Ketidakhadiran'] = $totalPotonganKetidakhadiran;
            }

            // Hitung total potongan
            $totalPotongan = array_sum($komponenPotongan);

            // Hitung gaji bersih (termasuk lembur)
            $gajiBersih = $totalGajiKotor - $totalPotongan + $jumlahLembur;
            // dd(vars: $gajiBersih);
            // Generate kode penggajian
            $lastPenggajian = Penggajian::orderBy('kode_penggajian', 'desc')->first();
            $lastNumber = $lastPenggajian ? intval(substr($lastPenggajian->kode_penggajian, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $kodePenggajian = 'PG' . date('Ym') . $newNumber;

            // Buat record penggajian baru
            $penggajian = new Penggajian();
            $penggajian->kode_penggajian = $kodePenggajian;
            $penggajian->nik = $request->nik;
            $penggajian->kode_cabang = $request->kode_cabang;
            $penggajian->kode_lokasi_penugasan = $request->kode_lokasi_penugasan;
            $penggajian->tanggal_gaji = $tanggalGaji;
            $penggajian->bulan = $bulan;
            $penggajian->jumlah_hari_kerja = $hariKerjaLokasi;
            $penggajian->jumlah_hari_masuk = $totalKehadiran;
            $penggajian->jumlah_hari_tidak_masuk = $totalKetidakhadiran;
            $penggajian->total_jam_lembur = $jamLembur;
            $penggajian->komponen_gaji = json_encode($komponenGajiArray);
            $penggajian->komponen_potongan = json_encode($komponenPotongan);
            $penggajian->total_gaji_kotor = $totalGajiKotor;
            $penggajian->total_potongan = $totalPotongan;
            $penggajian->gaji_bersih = $gajiBersih;
            $penggajian->save();

            DB::commit();
            return redirect()
                    ->route('admin.penggajian')
                    ->with('success', 'Penggajian berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
            ->back()
            ->with('error', 'Gagal menyimpan penggajian: ' . $e->getMessage());
        }
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
