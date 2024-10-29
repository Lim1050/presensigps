<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\LokasiPenugasan;
use App\Models\Thr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ThrController extends Controller
{
    public function getTHR($nik)
    {
        try {
            $karyawan = Karyawan::where('nik', $nik)->first();
            Log::info('Karyawan found:', $karyawan ? $karyawan->toArray() : 'No karyawan found');

            if (!$karyawan) {
                return response()->json(['error' => 'Karyawan tidak ditemukan'], 404);
            }

            $gaji = Gaji::where('kode_jabatan', $karyawan->kode_jabatan)
                        ->where('kode_lokasi_penugasan', $karyawan->kode_lokasi_penugasan)
                        ->where('kode_cabang', $karyawan->kode_cabang)
                        ->where('kode_jenis_gaji', 'GT')
                        ->first();

            Log::info('Gaji found:', $gaji ? $gaji->toArray() : 'No gaji found');

            $jumlah_thr = $gaji->jumlah_gaji ? $gaji->jumlah_gaji : null;
            Log::info('Jumlah THR:', ['value' => $jumlah_thr]);

            return response()->json([
                'success' => true,
                'jumlah_thr' => $jumlah_thr
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getTHR:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function ThrIndex()
    {
        $thr = Thr::with(['jabatan', 'lokasiPenugasan', 'kantorCabang', 'karyawan'])->get();

        return view('thr.thr_index', compact(var_name: 'thr'));
    }
    public function ThrCreate()
    {
        $karyawan = Karyawan::all();
        $jabatan = Jabatan::all();
        $lokasiPenugasan = LokasiPenugasan::all();
        $cabang = Cabang::all();

        return view('thr.thr_create', compact('karyawan', 'jabatan', 'lokasiPenugasan', 'cabang'));
    }

    public function ThrStore(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nik' => 'required|exists:karyawan,nik',
            'kode_jabatan' => 'required|exists:jabatan,kode_jabatan',
            'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
            'nama_thr' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_thr' => 'required|numeric|min:0',
            'tanggal_penyerahan' => 'required|date',
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'notes' => 'nullable|string',
        ]);

        // Generate kode THR
            $kode_thr = $this->generateKodeTHR();

        try {
            DB::beginTransaction();

            // Jika field disabled, gunakan nilai dari hidden input
            $kode_jabatan = $request->input('kode_jabatan') ?: $request->input('kode_jabatan_hidden');
            $kode_lokasi_penugasan = $request->input('kode_lokasi_penugasan') ?: $request->input('kode_lokasi_penugasan_hidden');
            $kode_cabang = $request->input('kode_cabang') ?: $request->input('kode_cabang_hidden');

            // Buat record THR baru
            $thr = new Thr();
            $thr->kode_thr = $kode_thr;
            $thr->nik = $validated['nik'];
            $thr->kode_jabatan = $kode_jabatan;
            $thr->kode_lokasi_penugasan = $kode_lokasi_penugasan;
            $thr->kode_cabang = $kode_cabang;
            $thr->nama_thr = $validated['nama_thr'];
            $thr->tahun = $validated['tahun'];
            $thr->jumlah_thr = $validated['jumlah_thr'];
            $thr->tanggal_penyerahan = $validated['tanggal_penyerahan'];
            $thr->status = $validated['status'];
            $thr->notes = $validated['notes'];

            // Simpan THR
            $thr->save();

            DB::commit();

            return redirect()->route('admin.thr')->with('success', 'Data THR berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function generateKodeTHR()
    {
        $randomString = strtoupper(string: Str::random(3)) . rand(100, 999); // 3 huruf kapital dan 3 angka
        return 'THR' . $randomString; // Menggabungkan dengan 'THR'
    }

    public function ThrShow($kode_thr)
    {
        $thr = Thr::with(['jabatan', 'lokasiPenugasan', 'kantorCabang', 'karyawan'])->findOrFail($kode_thr);
        return view('thr.thr_show', compact(var_name: 'thr'));
    }

    public function ThrEdit($kode_thr)
    {
        $thr = Thr::with(['jabatan', 'lokasiPenugasan', 'kantorCabang', 'karyawan'])->findOrFail($kode_thr);
        $karyawan = Karyawan::all();
        $jabatan = Jabatan::all();
        $lokasiPenugasan = LokasiPenugasan::all();
        $cabang = Cabang::all();


        return view('thr.thr_edit', compact('thr', 'karyawan', 'jabatan', 'lokasiPenugasan', 'cabang'));
    }

    public function ThrUpdate(Request $request, $kode_thr)
    {
        $thr = Thr::find($kode_thr);

        if (!$thr) {
            return redirect()->back()->with('error', 'Data THR tidak ditemukan.');
        }

        // Validasi input
        $validated = $request->validate([
            'nama_thr' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah_thr' => 'required|numeric|min:0',
            'tanggal_penyerahan' => 'required|date',
            'status' => 'required|in:Pending,Disetujui,Ditolak',
            'notes' => 'nullable|string',
            'catatan_perubahan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Cek perubahan data
            $isChanged = false;
            $changes = [];

            // Array field yang akan dicek perubahannya
            $fieldsToCheck = [
                'nama_thr',
                'tahun',
                'jumlah_thr',
                'tanggal_penyerahan',
                'status',
                'notes'
            ];

            foreach ($fieldsToCheck as $field) {
                if ($thr->$field != $validated[$field]) {
                    $changes[$field] = [
                        'from' => $thr->$field,
                        'to' => $validated[$field]
                    ];
                    $isChanged = true;
                }
            }

            // Jika ada perubahan, update data
            if ($isChanged) {
                // Update field yang berubah
                foreach ($changes as $field => $value) {
                    $thr->$field = $validated[$field];
                }

                // Update metadata
                $thr->catatan_perubahan = $validated['catatan_perubahan'];
                $thr->diubah_oleh = Auth::user()->name;
                $thr->save();

                DB::commit();

                return redirect()->route('admin.thr')
                    ->with('success', 'Data THR berhasil diupdate.');
            } else {
                DB::rollback();
                return redirect()->back()
                    ->with('warning', 'Tidak ada perubahan data yang dilakukan.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function ThrDelete($kode_thr)
    {
        try {
            DB::beginTransaction();

            $thr = Thr::where('kode_thr', $kode_thr)->firstOrFail();

            // Hapus THR
            $thr->delete();

            DB::commit();

            return redirect()->route('admin.thr')->with('success', 'Data THR berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.thr')->with('error', 'Terjadi kesalahan saat menghapus data THR: ' . $e->getMessage());
        }
    }

    public function ExportPDF($kode_thr)
    {
        $thr = Thr::with(['jabatan', 'lokasiPenugasan', 'kantorCabang', 'karyawan'])->findOrFail($kode_thr);

        $pdf = Pdf::loadView('thr.thr_export', compact('thr'));

        return $pdf->download($thr->kode_thr . '_' . $thr->karyawan->nama_lengkap . '_' . $thr->nama_thr . '_' . $thr->tahun . '.pdf');
    }

    // public function ThrPersetujuan(Request $request, $kode_thr)
    // {
    //     $thr = Thr::findOrFail($kode_thr);
    //     $thr->update([
    //         'status' => 'approved',
    //         'notes' => $request->notes
    //     ]);

    //     return redirect()->back()->with('success', 'THR berhasil disetujui');
    // }
}
