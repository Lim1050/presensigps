<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Jabatan;
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
                'lembur_libur' => 'sometimes',
            ]);

            DB::beginTransaction();

            // Mengatur nilai lembur_libur
            $lembur_libur = $request->has('lembur_libur') ? 1 : 0;

            // Konversi waktu mulai dan selesai ke format Carbon
            $tanggal_presensi = Carbon::parse($validatedData['tanggal_presensi']);
            $waktu_mulai = Carbon::parse($validatedData['waktu_mulai']);
            $waktu_selesai = Carbon::parse($validatedData['waktu_selesai']);

            // Set waktu mulai dan selesai lengkap dengan tanggal
            $datetime_mulai = Carbon::create(
                $tanggal_presensi->year,
                $tanggal_presensi->month,
                $tanggal_presensi->day,
                $waktu_mulai->hour,
                $waktu_mulai->minute,
                0
            );

            $datetime_selesai = Carbon::create(
                $tanggal_presensi->year,
                $tanggal_presensi->month,
                $tanggal_presensi->day,
                $waktu_selesai->hour,
                $waktu_selesai->minute,
                0
            );

            // Cek apakah lintas hari
            $is_lintas_hari = 0;
            if ($waktu_selesai < $waktu_mulai) {
                $is_lintas_hari = 1;
                $datetime_selesai->addDay(); // Tambah 1 hari jika lintas hari
            }

            // Hitung durasi lembur dalam menit
            $durasi_menit = $datetime_mulai->diffInMinutes($datetime_selesai);

            // Membuat instance Lembur baru
            $lembur = new Lembur();
            $lembur->nik = $validatedData['nik'];
            $lembur->tanggal_presensi = $validatedData['tanggal_presensi'];
            $lembur->waktu_mulai = $validatedData['waktu_mulai'];
            $lembur->waktu_selesai = $validatedData['waktu_selesai'];
            $lembur->lembur_libur = $lembur_libur;
            $lembur->lintas_hari = $is_lintas_hari;
            $lembur->durasi_menit = $durasi_menit;
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

    /**
     * Show the form for editing the specified Lembur record.
     */
    public function edit(Lembur $Lembur)
    {
        if ($Lembur->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat diubah.');
        }

        $karyawan = Karyawan::where('status', 'aktif')->get();
        return view('Lembur.edit', compact('Lembur', 'karyawan'));
    }

    /**
     * Update the specified Lembur record.
     */
    public function update(Request $request, Lembur $Lembur)
    {
        if ($Lembur->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat diubah.');
        }

        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'alasan' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Hitung durasi lembur
            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);
            $duration = $end->diffInHours($start);

            $Lembur->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $duration,
                'is_holiday_Lembur' => $request->has('is_holiday_Lembur'),
                'alasan' => $request->alasan,
                'updated_by' => Auth::id()
            ]);

            DB::commit();
            return redirect()->route('Lembur.index')->with('success', 'Pengajuan lembur berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve Lembur request
     */
    public function approve(Lembur $Lembur)
    {
        if ($Lembur->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat disetujui.');
        }

        try {
            DB::beginTransaction();

            $Lembur->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Update presensi record
            $presensi = Presensi::firstOrCreate(
                [
                    'nik' => $Lembur->nik,
                    'tanggal_presensi' => $Lembur->tanggal_presensi
                ],
                [
                    'status' => 'hadir',
                    'is_Lembur' => true
                ]
            );

            $presensi->update([
                'is_Lembur' => true,
                'Lembur_start' => $Lembur->start_time,
                'Lembur_end' => $Lembur->end_time
            ]);

            DB::commit();
            return redirect()->route('Lembur.index')->with('success', 'Pengajuan lembur berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject Lembur request
     */
    public function reject(Request $request, Lembur $Lembur)
    {
        if ($Lembur->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat ditolak.');
        }

        $request->validate([ 'alasan_reject' => 'required|string|max:255' ]);

        try {
            DB::beginTransaction();

            $Lembur->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'alasan_reject' => $request->alasan_reject
            ]);

            DB::commit();
            return redirect()->route('Lembur.index')->with('success', 'Pengajuan lembur berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
