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
        $query = Lembur::with(['karyawan', 'presensi']);

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

        // Filter berdasarkan departemen jika user adalah kepala departemen
        // if (Auth::user()->hasRole('kepala_departemen')) {
        //     $kode_dept = Auth::user()->kode_dept;
        //     $query->whereHas('karyawan', function($q) use ($kode_dept) {
        //         $q->where('kode_dept', $kode_dept);
        //     });
        // }

        $lembur = $query->orderBy('tanggal_presensi', 'desc')
                        ->paginate(10);

        return view('lembur.lembur_index', compact('lembur'));
    }

    /**
     * Show the form for creating a new Lembur record.
     */
    public function LemburCreate()
    {
        $karyawan = Karyawan::all();
        $jabatan = Jabatan::all();
        $lokasiPenugasan = LokasiPenugasan::all();
        $cabang = Cabang::all();
        return view('lembur.lembur_create', compact('karyawan', 'jabatan', 'lokasiPenugasan', 'cabang'));
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

            // Default: lembur libur (di luar jam kerja)
            $lembur_libur = 1;

            // Mendapatkan hari dari tanggal presensi
            $hari = strtolower($tanggal_presensi->format('l'));

            // Cek jam kerja karyawan
            $jamKerjaKaryawan = JamKerjaKaryawan::where('nik', $nik)
                ->where('hari', $hari)
                ->first();

            // Jika jam kerja ditemukan, periksa apakah lembur berada dalam jam kerja
            if ($jamKerjaKaryawan) {
                $jamKerja = JamKerja::where('kode_jam_kerja', $jamKerjaKaryawan->kode_jam_kerja)->first();

                if ($jamKerja) {
                    $jam_masuk = Carbon::parse($jamKerja->jam_masuk);
                    $jam_pulang = Carbon::parse($jamKerja->jam_pulang);

                    // Jika jam kerja lintas hari, sesuaikan jam pulang
                    if ($jamKerja->lintas_hari == '1') {
                        $jam_pulang->addDay();
                    }

                    // Cek apakah lembur beririsan dengan jam kerja
                    if ($waktu_mulai->between($jam_masuk, $jam_pulang) ||
                        $waktu_selesai->between($jam_masuk, $jam_pulang) ||
                        ($waktu_mulai <= $jam_pulang && $waktu_selesai >= $jam_masuk)) {
                        $lembur_libur = 0; // Bukan lembur libur karena beririsan dengan jam kerja
                    }
                }
            }
            // Jika jam kerja tidak ditemukan, tetap dianggap sebagai lembur libur (nilai default)

            // Membuat instance Lembur baru
            $lembur = new Lembur();
            $lembur->nik = $nik;
            $lembur->tanggal_presensi = $tanggal_presensi->toDateString();
            $lembur->waktu_mulai = $waktu_mulai->format('H:i:s');
            $lembur->waktu_selesai = $waktu_selesai->format('H:i:s');
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

    /**
     * Display the specified Lembur record.
     */
    public function LemburShow($id)
    {
            $lembur = Lembur::with('karyawan')->findOrFail($id);
            // dd($lembur);

            return view('lembur.lembur_show', compact('lembur'));
    }

    public function LemburEdit($id)
    {
            $lembur = Lembur::with('karyawan')->findOrFail($id);
            // dd($lembur);

            return view('lembur.lembur_edit', compact('lembur'));
    }

    public function LemburUpdate(Request $request, $id)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'tanggal_presensi' => 'required|date',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required',
                'catatan_lembur' => 'required',
            ]);

            DB::beginTransaction();

            $lembur = Lembur::findOrFail($id);

            // Pastikan status masih 'pending' sebelum mengizinkan update
            if ($lembur->status !== 'pending') {
                throw new \Exception('Hanya lembur dengan status pending yang dapat diubah.');
            }

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

            // Default: lembur libur (di luar jam kerja)
            $lembur_libur = 1;

            // Mendapatkan hari dari tanggal presensi
            $hari = strtolower($tanggal_presensi->format('l'));

            // Cek jam kerja karyawan
            $jamKerjaKaryawan = JamKerjaKaryawan::where('nik', $lembur->nik)
                ->where('hari', $hari)
                ->first();

            // Jika jam kerja ditemukan, periksa apakah lembur berada dalam jam kerja
            if ($jamKerjaKaryawan) {
                $jamKerja = JamKerja::where('kode_jam_kerja', $jamKerjaKaryawan->kode_jam_kerja)->first();

                if ($jamKerja) {
                    $jam_masuk = Carbon::parse($jamKerja->jam_masuk);
                    $jam_pulang = Carbon::parse($jamKerja->jam_pulang);

                    // Jika jam kerja lintas hari, sesuaikan jam pulang
                    if ($jamKerja->lintas_hari == '1') {
                        $jam_pulang->addDay();
                    }

                    // Cek apakah lembur beririsan dengan jam kerja
                    if ($waktu_mulai->between($jam_masuk, $jam_pulang) ||
                        $waktu_selesai->between($jam_masuk, $jam_pulang) ||
                        ($waktu_mulai <= $jam_pulang && $waktu_selesai >= $jam_masuk)) {
                        $lembur_libur = 0; // Bukan lembur libur karena beririsan dengan jam kerja
                    }
                }
            }
            // Jika jam kerja tidak ditemukan, tetap dianggap sebagai lembur libur (nilai default)

            // Update data lembur
            $lembur->tanggal_presensi = $tanggal_presensi->toDateString();
            $lembur->waktu_mulai = $waktu_mulai->format('H:i:s');
            $lembur->waktu_selesai = $waktu_selesai->format('H:i:s');
            $lembur->lintas_hari = $is_lintas_hari;
            $lembur->durasi_menit = $durasi_menit;
            $lembur->lembur_libur = $lembur_libur;
            $lembur->catatan_lembur = $catatan_lembur;

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

    public function LemburDelete($id)
    {
        try {
            DB::beginTransaction();

            $lembur = Lembur::where('id', $id)->firstOrFail();

            // Hapus lembur
            $lembur->delete();

            DB::commit();

            return redirect()->route('admin.lembur')->with('success', 'Data Lembur berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.lembur')->with('error', 'Terjadi kesalahan saat menghapus data Lembur: ' . $e->getMessage());
        }
    }

    public function LemburUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'required_if:status,ditolak'
        ]);

        $lembur = Lembur::findOrFail($id);
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
