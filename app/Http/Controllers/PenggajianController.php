<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Cashbon;
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
                $query->where('bulan', 'like',  $request->bulan . '%');
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
                $bulanList[$date->format('Y-m')] = $date->translatedFormat('F Y');
            }
            // dd($bulanList);

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
                ->whereYear('tanggal_presensi', $tahun)
                ->whereMonth('tanggal_presensi', $bulan)
                ->get();

            $totalKehadiran = $presensi->whereIn('status', ['hadir', 'izin', 'sakit', 'cuti'])->count();
            $totalKetidakhadiran = $hariKerjaLokasi - $totalKehadiran;

            // Hitung potongan gaji untuk ketidakhadiran hanya sekali
            if ($totalKetidakhadiran > 0 && isset($komponenGajiArray['GT'])) {
                // Pastikan potongan ketidakhadiran hanya dihitung sekali
                if (!isset($komponenPotongan['Potongan Ketidakhadiran'])) {
                    $potonganPerHari = $komponenGajiArray['GT']['jumlah_gaji'] / $hariKerjaLokasi;
                    $totalPotonganKetidakhadiran = $potonganPerHari * $totalKetidakhadiran;
                    $komponenGajiArray['GT']['jumlah_gaji'] -= $totalPotonganKetidakhadiran;
                    $komponenPotongan['Potongan Ketidakhadiran'] = $totalPotonganKetidakhadiran;
                }
            }

            // Hitung Cashbon jika ada
            $cashbon = Cashbon::where('nik', $karyawan->nik)
                ->whereYear('tanggal_pengajuan', $tanggalGaji->year)
                ->whereMonth('tanggal_pengajuan', $tanggalGaji->month)
                ->where('status', 'diterima')
                ->sum('jumlah');
            // dd($cashbon);

            // Jika ada cashbon, kurangi dari total gaji bersih
            if ($cashbon > 0) {
                $komponenPotongan['Cashbon'] = $cashbon;
            }

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

            // dd($komponenGajiAsli);

            // Tambahkan komponen lembur ke array komponen gaji
            $komponenGajiArray['L'] = [
                'jumlah_gaji' => $jumlahLembur,
                'jenis_gaji' => 'Lembur'
            ];
            // $komponenGajiAsli['L'] = $jumlahLembur;
            // dd($komponenGajiArray);

            // Hitung total
            $totalGajiAsli = array_sum($komponenGajiAsli);
            $total_gaji_tanpa_lembur = array_sum(array_filter($komponenGajiAsli, function($key) {
                return $key !== 'L';
            }, ARRAY_FILTER_USE_KEY));
            $totalGaji = array_sum(array_column($komponenGajiArray, 'jumlah_gaji'));
            $totalPotongan = array_sum($komponenPotongan);
            $gajiBersih = $total_gaji_tanpa_lembur - $totalPotongan + $jumlahLembur;
            // dd($totalPotongan);

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
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
                'status' => 'required',
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

            // Ubah $komponenGajiAsli agar memiliki format yang sama
            $komponenGajiAsli = $komponenGaji->mapWithKeys(function ($item) {
                return [
                    $item->kode_jenis_gaji => [
                        'jumlah_gaji' => $item->jumlah_gaji,
                        'jenis_gaji' => $item->jenisGaji->jenis_gaji ?? $item->kode_jenis_gaji
                    ]
                ];
            })->toArray();

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

            // Hitung total gaji kotor dari komponen asli ( sebelum potongan)
            $totalGajiKotor = array_sum(array_column($komponenGajiAsli, 'jumlah_gaji'));

            // Hitung Cashbon jika ada
            $cashbon = Cashbon::where('nik', $karyawan->nik)
                ->whereYear('tanggal_pengajuan', $tanggalGaji->year)
                ->whereMonth('tanggal_pengajuan', $tanggalGaji->month)
                ->where('status', 'diterima')
                ->sum('jumlah');
            // dd($cashbon);

            // Jika ada cashbon, kurangi dari total gaji bersih
            if ($cashbon > 0) {
                $komponenPotongan['Cashbon'] = $cashbon;
            }

            // Hitung lembur
            $lembur = Lembur::where('nik', $karyawan->nik)
                ->whereYear('tanggal_presensi', $tanggalGaji->year)
                ->whereMonth('tanggal_presensi', $tanggalGaji->month)
                ->where('status', 'disetujui')
                ->sum('durasi_menit');

            // Konversi menit ke jam
            $jamLembur = $lembur / 60;

            // Hitung jumlah lembur sebagai komponen terpisah
            $tarifLemburPerJam = $komponenGajiAsli['L']['jumlah_gaji'] ?? 0;
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
            // dd(vars: $totalPotongan);
            $total_gaji_tanpa_lembur = array_sum(array_map(function($value) {
                return (float)$value['jumlah_gaji']; // Mengambil nilai jumlah_gaji dan mengkonversinya ke float
            }, array_filter($komponenGajiAsli, function($value, $key) {
                return $key !== 'L'; // Mengabaikan komponen L
            }, ARRAY_FILTER_USE_BOTH)));

            // dd($total_gaji_tanpa_lembur);
            // Hitung gaji bersih (termasuk lembur)
            $gajiBersih = $total_gaji_tanpa_lembur - $totalPotongan + $jumlahLembur;

            // Generate kode penggajian
            $lastPenggajian = Penggajian::orderBy('kode_penggajian', 'desc')->first();
            $lastNumber = $lastPenggajian ? intval(substr($lastPenggajian->kode_penggajian, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $kodePenggajian = 'PG' . date('Ym') . $newNumber;

            // dd($komponenGajiAsli);

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
            $penggajian->komponen_gaji_kotor = json_encode($komponenGajiAsli);
            $penggajian->komponen_gaji = json_encode($komponenGajiArray);
            $penggajian->komponen_potongan = json_encode($komponenPotongan);
            $penggajian->total_gaji_kotor = $totalGajiKotor;
            $penggajian->total_potongan = $totalPotongan;
            $penggajian->gaji_bersih = $gajiBersih;
            $penggajian->catatan = $request->catatan;
            $penggajian->status = $request->status;
            $penggajian->diproses_oleh = Auth::user()->name;
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

    public function PenggajianShow($kode_penggajian)
    {
        // Mengambil data penggajian beserta relasi karyawan, cabang, dan lokasi penugasan
        $penggajian = Penggajian::with('karyawan', 'cabang', 'lokasiPenugasan')->findOrFail($kode_penggajian);

        // Decode komponen gaji dan potongan dari JSON
        $komponenGajiKotor = json_decode($penggajian->komponen_gaji_kotor, true);
        // dd($komponenGajiKotor);
        $komponenGaji = json_decode($penggajian->komponen_gaji, true);
        // dd($komponenGaji);
        $komponenPotongan = json_decode($penggajian->komponen_potongan, true);

        // dd($komponenPotongan);

        // Mengoper data ke view
        return view('penggajian.penggajian_show', compact('penggajian', 'komponenGajiKotor', 'komponenGaji', 'komponenPotongan'));
    }

    public function PenggajianEdit($kode_penggajian)
    {
        // Mengambil data penggajian beserta relasi karyawan, cabang, dan lokasi penugasan
        $penggajian = Penggajian::with('karyawan', 'cabang', 'lokasiPenugasan')->findOrFail($kode_penggajian);

        // Decode komponen gaji dan potongan dari JSON
        $komponenGajiKotor = json_decode($penggajian->komponen_gaji_kotor, true);
        $komponenGaji = json_decode($penggajian->komponen_gaji, true);
        // dd($komponenGaji);
        $komponenPotongan = json_decode($penggajian->komponen_potongan, true);
        // dd($komponenPotongan);
        return view('penggajian.penggajian_edit', compact('penggajian', 'komponenGajiKotor', 'komponenGaji', 'komponenPotongan'));
    }

    public function previewEditGaji(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data yang diterima untuk preview Edit gaji:', $request->all());

            // Validasi input
            $request->validate([
                'jumlah_hari_masuk' => 'required|integer|min:0',
                'jumlah_hari_tidak_masuk' => 'required|integer|min:0',
                'total_jam_lembur' => 'required|numeric|min:0',
                'tanggal_gaji' => 'required|date',
                'status' => 'required',
                'catatan' => 'required',
                'catatan_perubahan' => 'required',
            ]);

            $tanggalGaji = Carbon::parse($request->tanggal_gaji);
            $bulan = $tanggalGaji->month;
            $tahun = $tanggalGaji->year;

            // Ambil data karyawan
            $karyawan = Penggajian::with(['karyawan','jabatan', 'cabang', 'lokasiPenugasan'])
                ->where('nik', $request->nik)
                ->where('kode_penggajian', $request->kode_penggajian)
                ->firstOrFail();
            // dd($karyawan);
            // Ambil jumlah hari kerja dari lokasi penugasan
            $hariKerjaLokasi = $karyawan->lokasiPenugasan->jumlah_hari_kerja;

            // Ambil komponen gaji
            $komponenGaji = Gaji::with('jenisGaji')
                ->where('kode_jabatan', $karyawan->karyawan->kode_jabatan)
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
            // dd($komponenGajiArray);
            $komponenGajiAsli = $komponenGaji->pluck('jumlah_gaji', 'kode_jenis_gaji')->toArray();
            // dd($komponenGajiAsli);

            // Ambil komponen potongan
            $komponenPotongan = Potongan::with('jenisPotongan')
                ->where('kode_jabatan', $karyawan->karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        $item->jenisPotongan->jenis_potongan ?? $item->nama_potongan => $item->jumlah_potongan
                    ];
                })
                ->toArray();

            // dd($komponenPotongan);

            // Total kehadiran diambil dari request
            $totalKehadiran = $request->jumlah_hari_masuk;
            $totalKetidakhadiran = $hariKerjaLokasi - $totalKehadiran;

            // Hitung potongan gaji untuk ketidakhadiran
            if ($totalKetidakhadiran > 0 && isset($komponenGajiArray['GT'])) {
                // Pastikan potongan ketidakhadiran hanya dihitung sekali
                if (!isset($komponenPotongan['Potongan Ketidakhadiran'])) {
                    $potonganPerHari = $komponenGajiArray['GT']['jumlah_gaji'] / $hariKerjaLokasi;
                    $totalPotonganKetidakhadiran = $potonganPerHari * $totalKetidakhadiran;
                    $komponenGajiArray['GT']['jumlah_gaji'] -= $totalPotonganKetidakhadiran;
                    $komponenPotongan['Potongan Ketidakhadiran'] = $totalPotonganKetidakhadiran;
                }
            }

            // Hitung Cashbon jika ada
            $cashbon = Cashbon::where('nik', $karyawan->nik)
                ->whereYear('tanggal_pengajuan', $tanggalGaji->year)
                ->whereMonth('tanggal_pengajuan', $tanggalGaji->month)
                ->where('status', 'diterima')
                ->sum('jumlah');
            // dd($cashbon);

            // Jika ada cashbon, kurangi dari total gaji bersih
            if ($cashbon > 0) {
                $komponenPotongan['Cashbon'] = $cashbon;
            }

            // Hitung lembur
            $jamLembur = $request->total_jam_lembur;

            // Hitung jumlah lembur sebagai komponen terpisah
            $tarifLemburPerJam = $komponenGajiAsli['L'] ?? 0;
            $jumlahLembur = $jamLembur * $tarifLemburPerJam;

            // Tambahkan komponen lembur ke array komponen gaji
            $komponenGajiArray['L'] = [
                'jumlah_gaji' => $jumlahLembur,
                'jenis_gaji' => 'Lembur'
            ];

            // Hitung total
            $totalGajiAsli = array_sum($komponenGajiAsli);
            $total_gaji_tanpa_lembur = array_sum(array_filter($komponenGajiAsli, function($key) {
                return $key !== 'L';
            }, ARRAY_FILTER_USE_KEY));
            $totalGaji = array_sum(array_column($komponenGajiArray, 'jumlah_gaji'));
            $totalPotongan = array_sum($komponenPotongan);
            // dd(vars: $totalPotongan);
            $gajiBersih = $total_gaji_tanpa_lembur - $totalPotongan + $jumlahLembur;
            // dd($totalGaji);

            return view('penggajian.preview_edit_gaji', compact(
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
                'jamLembur',
                'jumlahLembur',
                'hariKerjaLokasi'
            ))->render();

        } catch (\Exception $e) {
            // Cek apakah pesan kesalahan berisi "Undefined array key 'L'"
            if (strpos($e->getMessage(), 'Undefined array key "L"') !== false) {
                $errorMessage = 'Karyawan ini tidak memiliki gaji lembur.';
            } else {
                $errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            }

            return response()->json([
                'error' => true,
                'message' => $errorMessage
            ], 500);
        }
    }

    public function PenggajianUpdate(Request $request, $kode_penggajian)
    {
        try {
            DB::beginTransaction();
            // Log data yang diterima
            Log::info('Data yang diterima untuk preview Edit gaji:', $request->all());

            // Validasi input
            $request->validate([
                'jumlah_hari_masuk' => 'required|integer|min:0',
                'jumlah_hari_tidak_masuk' => 'required|integer|min:0',
                'total_jam_lembur' => 'required|numeric|min:0',
                'tanggal_gaji' => 'required|date',
                'status' => 'required',
                'catatan' => 'required',
                'catatan_perubahan' => 'required',
            ]);

            $tanggalGaji = Carbon::parse($request->tanggal_gaji);
            $bulan = $tanggalGaji->format('Y-m');
            // $tahun = $tanggalGaji->year;

            // Ambil data karyawan
            $karyawan = Penggajian::with(['karyawan','jabatan', 'cabang', 'lokasiPenugasan'])
                ->where('nik', $request->nik)
                ->where('kode_penggajian', $request->kode_penggajian)
                ->firstOrFail();
            // dd($karyawan);
            // Ambil jumlah hari kerja dari lokasi penugasan
            $hariKerjaLokasi = $karyawan->lokasiPenugasan->jumlah_hari_kerja;

            // Ambil komponen gaji
            $komponenGaji = Gaji::with('jenisGaji')
                ->where('kode_jabatan', $karyawan->karyawan->kode_jabatan)
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
            // dd($komponenGajiArray);
            $komponenGajiAsli = $komponenGaji->mapWithKeys(function ($item) {
                return [
                    $item->kode_jenis_gaji => [
                        'jumlah_gaji' => $item->jumlah_gaji,
                        'jenis_gaji' => $item->jenisGaji->jenis_gaji ?? $item->kode_jenis_gaji
                    ]
                ];
            })->toArray();
            // dd($komponenGajiAsli);

            // Ambil komponen potongan
            $komponenPotongan = Potongan::with('jenisPotongan')
                ->where('kode_jabatan', $karyawan->karyawan->kode_jabatan)
                ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                ->where('kode_cabang', $karyawan->kode_cabang)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        $item->jenisPotongan->jenis_potongan ?? $item->nama_potongan => $item->jumlah_potongan
                    ];
                })
                ->toArray();

            // dd($komponenPotongan);

            // Total kehadiran diambil dari request
            $totalKehadiran = $request->jumlah_hari_masuk;
            $totalKetidakhadiran = $hariKerjaLokasi - $totalKehadiran;

            $totalGajiKotor = array_sum(array_column($komponenGajiArray, 'jumlah_gaji'));
            // dd($komponenPotongan);

             // Hitung Cashbon jika ada
            $cashbon = Cashbon::where('nik', $karyawan->nik)
                ->whereYear('tanggal_pengajuan', $tanggalGaji->year)
                ->whereMonth('tanggal_pengajuan', $tanggalGaji->month)
                ->where('status', 'diterima')
                ->sum('jumlah');
            // dd($cashbon);

            // Jika ada cashbon, kurangi dari total gaji bersih
            if ($cashbon > 0) {
                $komponenPotongan['Cashbon'] = $cashbon;
            }

            // Cek apakah karyawan memiliki komponen gaji lembur
            if ($request->total_jam_lembur > 0) {
                // Cek apakah karyawan memiliki komponen gaji lembur
                if (!array_key_exists('L', $komponenGajiAsli) || $komponenGajiAsli['L'] === null) {
                    return redirect()
                            ->back()
                            ->with('error', 'Gagal menyimpan penggajian. Karyawan ini tidak memiliki gaji lembur. Silakan periksa data gaji.');
                }
            }

            // Hitung lembur
            $jamLembur = $request->total_jam_lembur;

            // Hitung jumlah lembur sebagai komponen terpisah
            $tarifLemburPerJam = $komponenGajiAsli['L']['jumlah_gaji'] ?? 0;
            $jumlahLembur = $jamLembur * $tarifLemburPerJam;

            // Tambahkan komponen lembur ke array komponen gaji
            $komponenGajiArray['L'] = [
                'jumlah_gaji' => $jumlahLembur,
                'jenis_gaji' => 'Lembur'
            ];

            // Hitung potongan gaji untuk ketidakhadiran
            if ($totalKetidakhadiran > 0 && isset($komponenGajiArray['GT'])) {
                // Pastikan potongan ketidakhadiran hanya dihitung sekali
                if (!isset($komponenPotongan['Potongan Ketidakhadiran'])) {
                    $potonganPerHari = $komponenGajiArray['GT']['jumlah_gaji'] / $hariKerjaLokasi;
                    $totalPotonganKetidakhadiran = $potonganPerHari * $totalKetidakhadiran;
                    $komponenGajiArray['GT']['jumlah_gaji'] -= $totalPotonganKetidakhadiran;
                    $komponenPotongan['Potongan Ketidakhadiran'] = $totalPotonganKetidakhadiran;
                }
            }

            // Hitung total
            $totalPotongan = array_sum($komponenPotongan);
            // dd(vars: $totalPotongan);
            $total_gaji_tanpa_lembur = array_sum(array_map(function($value) {
                return (float)$value['jumlah_gaji']; // Mengambil nilai jumlah_gaji dan mengkonversinya ke float
            }, array_filter($komponenGajiAsli, function($value, $key) {
                return $key !== 'L'; // Mengabaikan komponen L
            }, ARRAY_FILTER_USE_BOTH)));
            $gajiBersih = $total_gaji_tanpa_lembur - $totalPotongan + $jumlahLembur;
            // dd(vars: $gajiBersih);


            // Ambil data penggajian berdasarkan kode_penggajian
            $penggajian = Penggajian::where('kode_penggajian', $kode_penggajian)->firstOrFail();

            $penggajian->tanggal_gaji = $tanggalGaji;
            $penggajian->bulan = $bulan;
            $penggajian->jumlah_hari_kerja = $hariKerjaLokasi;
            $penggajian->jumlah_hari_masuk = $totalKehadiran;
            $penggajian->jumlah_hari_tidak_masuk = $totalKetidakhadiran;
            $penggajian->total_jam_lembur = $jamLembur;
            $penggajian->komponen_gaji_kotor = json_encode($komponenGajiAsli);
            $penggajian->komponen_gaji = json_encode($komponenGajiArray);
            $penggajian->komponen_potongan = json_encode($komponenPotongan);
            $penggajian->total_gaji_kotor = $totalGajiKotor;
            $penggajian->total_potongan = $totalPotongan;
            $penggajian->gaji_bersih = $gajiBersih;
            $penggajian->status = $request->status;
            $penggajian->catatan = $request->catatan;
            $penggajian->alasan_perubahan = $request->catatan_perubahan;
            $penggajian->diubah_oleh = Auth::user()->name;
            $penggajian->waktu_perubahan = Carbon::now();
            $penggajian->save();

            DB::commit();
            return redirect()
                    ->route('admin.penggajian')
                    ->with('success', 'Penggajian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
            ->back()
            ->with('error', 'Gagal menyimpan penggajian: ' . $e->getMessage());
        }
    }

    public function PenggajianDelete($kode_penggajian)
    {
        $penggajian = Penggajian::findOrFail($kode_penggajian);

        try {
            $penggajian->delete();
            return redirect()->route('admin.penggajian')->with('success', 'Data penggajian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.penggajian')->with('error', 'Gagal menghapus data penggajian. ' . $e->getMessage());
        }
    }

    public function ExportPDF($kode_penggajian)
    {
        ini_set('max_execution_time', 120); // Mengatur batas waktu eksekusi menjadi 120 detik

        // Mengambil data penggajian beserta relasi karyawan, cabang, dan lokasi penugasan
        $penggajian = Penggajian::with('karyawan', 'cabang', 'lokasiPenugasan')->findOrFail($kode_penggajian);

        // Decode komponen gaji dan potongan dari JSON
        $komponenGajiKotor = json_decode($penggajian->komponen_gaji_kotor, true);
        $komponenGaji = json_decode($penggajian->komponen_gaji, true);
        $komponenPotongan = json_decode($penggajian->komponen_potongan, true);

        $imagePath = public_path('assets/img/MASTER-LOGO-PT-GUARD-500-500.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;

        // Mengoper data ke pdf
        $pdf = Pdf::loadView('penggajian.penggajian_export', compact('penggajian', 'komponenGajiKotor', 'komponenGaji', 'komponenPotongan', 'src'))
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('penggajian_' . $penggajian->kode_penggajian . '_' . $penggajian->karyawan->nama_lengkap . '_' . $penggajian->bulan . '.pdf');
    }
}
