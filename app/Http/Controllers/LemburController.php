<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jabatan;
use App\Models\JamKerja;
use App\Models\JamKerjaKaryawan;
use App\Models\Lembur;
use App\Models\Karyawan;
use App\Models\LokasiPenugasan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    /**
     * Display a listing of Lembur records.
     */
    public function LemburIndex(Request $request)
    {
        $user = auth()->user();

        $query = Lembur::with(['karyawan', 'presensi']);

        // Filter berdasarkan cabang untuk admin cabang
        if ($user->role === 'admin-cabang') {
            $query->whereHas('karyawan', function($q) use ($user) {
                $q->where('kode_cabang', $user->kode_cabang);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('waktu_mulai') && $request->has('waktu_selesai')) {
            $query->whereBetween('tanggal_presensi', [
                $request->waktu_mulai,
                $request->waktu_selesai
            ]);
        }

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lembur = $query->orderBy('tanggal_presensi', 'desc')
                        ->paginate(10);

        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
            return view('lembur.lembur_index', compact('lembur','cabang'));
        } else {
            $cabang = null;
            return view('lembur.lembur_index', compact('lembur','cabang'));
        }
    }

    /**
     * Show the form for creating a new Lembur record.
     */
    public function LemburCreate()
    {
        $karyawan = Karyawan::all();
        $jabatan = Jabatan::all();
        $lokasiPenugasan = LokasiPenugasan::all();
        $user = auth()->user();
        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->get();
        } else {
             $cabang = Cabang::all();
        }
        return view('lembur.lembur_create', compact('karyawan', 'jabatan', 'lokasiPenugasan', 'cabang'));
    }

    public function getJamKerja(Request $request)
    {
        $nik = $request->input('nik');
        $tanggal = $request->input('tanggal');

        // Konversi tanggal ke hari
        $hari = strtolower(Carbon::parse($tanggal)->translatedFormat('l'));

        // Cari jam kerja karyawan
        $jamKerjaKaryawan = JamKerjaKaryawan::where('nik', $nik)
            ->where('hari', $hari)
            ->first();

        if ($jamKerjaKaryawan) {
            // Ambil detail jam kerja
            $jamKerja = JamKerja::where('kode_jam_kerja', $jamKerjaKaryawan->kode_jam_kerja)->first();

            if ($jamKerja) {
                return response()->json([
                    'success' => true,
                    'kode_jam_kerja' => $jamKerja->kode_jam_kerja,
                    'jam_masuk' => $jamKerja->jam_masuk,
                    'jam_pulang' => $jamKerja->jam_pulang,
                    'lintas_hari' => $jamKerja->lintas_hari
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ditemukan jadwal jam kerja untuk karyawan pada tanggal tersebut.'
        ]);
    }

    /**
     * Store a newly created Lembur record.
     */
    public function LemburStore(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nik' => 'required',
                'tanggal_presensi' => 'required|date',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required',
                'catatan_lembur' => 'required',
            ]);

            DB::beginTransaction();

            $nik = $validatedData['nik'];
            $tanggal_presensi = Carbon::parse($validatedData['tanggal_presensi']);
            $waktu_mulai = Carbon::parse($validatedData['waktu_mulai']);
            $waktu_selesai = Carbon::parse($validatedData['waktu_selesai']);
            $catatan_lembur = $validatedData['catatan_lembur'];

            // Set waktu mulai dan selesai lengkap dengan tanggal
            $datetime_mulai = $tanggal_presensi->copy()->setTimeFromTimeString($waktu_mulai->format('H:i:s'));
            $datetime_selesai = $tanggal_presensi->copy()->setTimeFromTimeString($waktu_selesai->format('H:i:s'));

            // Cek apakah lintas hari
            $is_lintas_hari = 0;
            if ($waktu_selesai < $waktu_mulai) {
                $is_lintas_hari = 1;
                $datetime_selesai->addDay();
            }

            // Hitung durasi lembur dalam menit
            $durasi_menit = $datetime_mulai->diffInMinutes($datetime_selesai);

            // Mendapatkan hari dari tanggal presensi
            $hari = strtolower($tanggal_presensi->translatedFormat('l'));

            // Cek jam kerja karyawan untuk mendapatkan kode jam kerja
            $jamKerjaKaryawan = JamKerjaKaryawan::where('nik', $nik)
                ->where('hari', $hari)
                ->first();
            // dd($jamKerjaKaryawan);
            // Inisialisasi variabel
            $jam_masuk_asli = null;
            $jam_pulang_asli = null;
            $lembur_libur = 1; // Default: lembur libur
            $jenis_lembur = 'reguler'; // Default: lembur reguler


            // Jika jam kerja karyawan ditemukan
            if ($jamKerjaKaryawan) {
                // Ambil detail jam kerja berdasarkan kode jam kerja
                $jamKerja = JamKerja::where('kode_jam_kerja', $jamKerjaKaryawan->kode_jam_kerja)->first();
                // dd($jamKerja);

                // Tentukan lembur libur berdasarkan ada tidaknya jam kerja
                // $lembur_libur = $jamKerja ? 0 : 1;

                if ($jamKerja) {
                    // Set jam masuk dan jam pulang asli
                    $jam_masuk_asli = $jamKerja->jam_masuk;
                    $jam_pulang_asli = $jamKerja->jam_pulang;

                    // Konversi jam kerja ke Carbon
                    $jam_masuk = $tanggal_presensi->copy()->setTimeFromTimeString($jam_masuk_asli);
                    $jam_pulang = $tanggal_presensi->copy()->setTimeFromTimeString($jam_pulang_asli);

                    // Jika jam kerja lintas hari, sesuaikan jam pulang
                    if ($jamKerja->lintas_hari == '1') {
                        $jam_pulang->addDay();
                    }

                    // Tentukan lembur libur
                    $lembur_libur = 0;

                    // Tentukan jenis lembur
                    if ($datetime_mulai < $jam_masuk) {
                        // Lembur dimulai sebelum jam masuk (penebalan)
                        $jenis_lembur = 'penebalan';
                    } elseif ($datetime_mulai > $jam_pulang) {
                        // Lembur dimulai setelah jam pulang (reguler)
                        $jenis_lembur = 'reguler';
                    }
                }
            }

            // Membuat kode lembur secara acak
            $kode_lembur = $this->generateKodeLembur();

            // Membuat instance Lembur baru
            $lembur = new Lembur();
            $lembur->kode_lembur = $kode_lembur;
            $lembur->nik = $nik;
            $lembur->tanggal_presensi = $tanggal_presensi->toDateString();
            $lembur->waktu_mulai = $waktu_mulai->format('H:i:s');
            $lembur->waktu_selesai = $waktu_selesai->format('H:i:s');
            $lembur->jam_masuk_asli = $jam_masuk_asli;
            $lembur->jam_pulang_asli = $jam_pulang_asli;
            $lembur->jenis_lembur = $jenis_lembur;
            $lembur->lembur_libur = $lembur_libur;
            $lembur->lintas_hari = $is_lintas_hari;
            $lembur->durasi_menit = $durasi_menit;
            $lembur->catatan_lembur = $catatan_lembur;
            $lembur->status = 'pending';

            // Menyimpan data lembur
            $lembur->save();

            DB::commit();

            return redirect()
                ->route('admin.lembur')
                ->with('success', 'Data lembur berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data lembur: ' . $e->getMessage());
        }
    }

    private function generateKodeLembur()
    {
        $randomString = strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3)); // 3 huruf acak
        $randomNumber = rand(100, 999); // 3 angka acak
        return 'L' . $randomString . $randomNumber; // Kombinasi L + huruf + angka
    }

    /**
     * Display the specified Lembur record.
     */
    public function LemburShow($kode_lembur)
    {
            $lembur = Lembur::with('karyawan')->findOrFail($kode_lembur);
            // dd($lembur->kode_lembur);

            return view('lembur.lembur_show', compact('lembur'));
    }

    public function LemburEdit($kode_lembur)
    {
            $lembur = Lembur::with('karyawan')->findOrFail($kode_lembur);
            // dd($lembur);

            return view('lembur.lembur_edit', compact('lembur'));
    }

    public function LemburUpdate(Request $request, $kode_lembur)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nik' => 'required',
                'tanggal_presensi' => 'required|date',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required',
                'catatan_lembur' => 'required',
            ]);

            DB::beginTransaction();

            $lembur = Lembur::findOrFail($kode_lembur);

            // Pastikan status masih 'pending' sebelum mengizinkan update
            if ($lembur->status !== 'pending') {
                throw new \Exception('Hanya lembur dengan status pending yang dapat diubah.');
            }

            $nik = $validatedData['nik'];
            $tanggal_presensi = Carbon::parse($validatedData['tanggal_presensi']);
            $waktu_mulai = Carbon::parse($validatedData['waktu_mulai']);
            $waktu_selesai = Carbon::parse($validatedData['waktu_selesai']);
            $catatan_lembur = $validatedData['catatan_lembur'];

            // Set waktu mulai dan selesai lengkap dengan tanggal
            $datetime_mulai = $tanggal_presensi->copy()->setTimeFromTimeString($waktu_mulai->format('H:i:s'));
            $datetime_selesai = $tanggal_presensi->copy()->setTimeFromTimeString($waktu_selesai->format('H:i:s'));

            // Cek apakah lintas hari
            $is_lintas_hari = 0;
            if ($waktu_selesai < $waktu_mulai) {
                $is_lintas_hari = 1;
                $datetime_selesai->addDay();
            }

            // Hitung durasi lembur dalam menit
            $durasi_menit = $datetime_mulai->diffInMinutes($datetime_selesai);

            // Mendapatkan hari dari tanggal presensi
            $hari = strtolower($tanggal_presensi->translatedFormat('l'));

            // Cek jam kerja karyawan untuk mendapatkan kode jam kerja
            $jamKerjaKaryawan = JamKerjaKaryawan::where('nik', $nik)
                ->where('hari', $hari)
                ->first();

            // Inisialisasi variabel
            $jam_masuk_asli = null;
            $jam_pulang_asli = null;
            $lembur_libur = 1; // Default: lembur libur
            $jenis_lembur = 'reguler'; // Default: lembur reguler

            // Jika jam kerja karyawan ditemukan
            if ($jamKerjaKaryawan) {
                // Ambil detail jam kerja berdasarkan kode jam kerja
                $jamKerja = JamKerja::where('kode_jam_kerja', $jamKerjaKaryawan->kode_jam_kerja)->first();

                if ($jamKerja) {
                    // Set jam masuk dan jam pulang asli
                    $jam_masuk_asli = $jamKerja->jam_masuk;
                    $jam_pulang_asli = $jamKerja->jam_pulang;

                    // Konversi jam kerja ke Carbon
                    $jam_masuk = $tanggal_presensi->copy()->setTimeFromTimeString($jam_masuk_asli);
                    $jam_pulang = $tanggal_presensi->copy()->setTimeFromTimeString($jam_pulang_asli);

                    // Jika jam kerja lintas hari, sesuaikan jam pulang
                    if ($jamKerja->lintas_hari == '1') {
                        $jam_pulang->addDay();
                    }

                    // Tentukan lembur libur
                    $lembur_libur = 0;

                    // Tentukan jenis lembur
                    if ($datetime_mulai < $jam_masuk) {
                        // Lembur dimulai sebelum jam masuk (penebalan)
                        $jenis_lembur = 'penebalan';
                    } elseif ($datetime_mulai > $jam_pulang) {
                        // Lembur dimulai setelah jam pulang (reguler)
                        $jenis_lembur = 'reguler';
                    }
                }
            }

            // Update data lembur
            $lembur->tanggal_presensi = $tanggal_presensi->toDateString();
            $lembur->waktu_mulai = $waktu_mulai->format('H:i:s');
            $lembur->waktu_selesai = $waktu_selesai->format('H:i:s');
            $lembur->jam_masuk_asli = $jam_masuk_asli;
            $lembur->jam_pulang_asli = $jam_pulang_asli;
            $lembur->jenis_lembur = $jenis_lembur;
            $lembur->lembur_libur = $lembur_libur;
            $lembur->lintas_hari = $is_lintas_hari;
            $lembur->durasi_menit = $durasi_menit;
            $lembur->catatan_lembur = $catatan_lembur;

            // Menyimpan data lembur
            $lembur->save();

            DB::commit();

            return redirect()
                ->route('admin.lembur')
                ->with('success', 'Data lembur berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data lembur: ' . $e->getMessage());
        }
    }

    public function LemburDelete($kode_lembur)
    {
        try {
            DB::beginTransaction();

            $lembur = Lembur::where('kode_lembur', $kode_lembur)->firstOrFail();

            // Hapus lembur
            $lembur->delete();

            DB::commit();

            return redirect()->route('admin.lembur')->with('success', 'Data Lembur berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.lembur')->with('error', 'Terjadi kesalahan saat menghapus data Lembur: ' . $e->getMessage());
        }
    }

    public function LemburUpdateStatus(Request $request, $kode_lembur)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'required_if:status,ditolak'
        ]);

        $lembur = Lembur::findOrFail($kode_lembur);
        $lembur->status = $request->status;

        if ($request->status === 'ditolak') {
            $lembur->alasan_penolakan = $request->alasan_penolakan;
        }

        if ($lembur->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 500);
        }
    }
}
