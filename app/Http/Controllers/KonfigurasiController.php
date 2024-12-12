<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\JamKerja;
use App\Models\JamKerjaDept;
use App\Models\JamKerjaDeptDetail;
use App\Models\LokasiPenugasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KonfigurasiController extends Controller
{
    // public function LokasiKantor()
    // {
    //     $lokasi_kantor = DB::table('lokasi_kantor')->where('id', 1)->first();
    //     return view('konfigurasi.lokasi_kantor', compact('lokasi_kantor'));
    // }

    // public function UpdateLokasiKantor(Request $request)
    // {
    //     $lokasi_kantor = $request->lokasi_kantor;
    //     $radius = $request->radius;

    //     $update = DB::table('lokasi_kantor')->where('id', 1)->update([
    //         'lokasi_kantor' => $lokasi_kantor,
    //         'radius' => $radius
    //     ]);

    //     if($update){
    //         return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
    //     } else {
    //         return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
    //     }
    // }

    public function JamKerja(Request $request)
    {
        $user = auth()->user();
        // Query dasar
        $query = JamKerja::query();
        if ($user->role === 'admin-cabang') {
            $query->where('kode_cabang', $user->kode_cabang);
        }

        // Filter berdasarkan nama jam kerja
        if ($request->filled('nama_jam_kerja')) {
            $query->where('nama_jam_kerja', 'like', '%' . $request->nama_jam_kerja . '%');
        }

        // Filter berdasarkan kode cabang
        if ($request->filled('kode_cabang')) {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        // Filter berdasarkan kode lokasi penugasan
        if ($request->filled('kode_lokasi_penugasan')) {
            $query->where('kode_lokasi_penugasan', $request->kode_lokasi_penugasan);
        }

        // Filter berdasarkan lintas hari
        if ($request->filled('lintas_hari')) {
            $query->where('lintas_hari', $request->lintas_hari);
        }

        // Ambil data jam kerja dengan filter
        $jam_kerja = $query->orderBy('kode_jam_kerja')->paginate(10);

        // Ambil data pendukung untuk form
        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::with('lokasiPenugasan')->where('kode_cabang', $user->kode_cabang)->orderBy('kode_cabang')->get();
            $lokasiPenugasan = LokasiPenugasan::with('cabang')->where('kode_cabang', $user->kode_cabang)->get();
            $nama_cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
            // dd($nama_cabang);
        } else {
            $cabang = Cabang::with('lokasiPenugasan')->orderBy('kode_cabang')->get();
            $lokasiPenugasan = LokasiPenugasan::with('cabang')->get();
            $nama_cabang = null;
        }
        // $lokasiPenugasan = LokasiPenugasan::all();
        // $cabang = Cabang::all();

        // Kembalikan view dengan data
        return view('konfigurasi.jam_kerja', [
            'jam_kerja' => $jam_kerja,
            'lokasiPenugasan' => $lokasiPenugasan,
            'cabang' => $cabang,
            'nama_cabang' => $nama_cabang,
            // Kembalikan input request untuk mempertahankan filter
            'request' => $request
        ]);
    }

    public function JamKerjaStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            // 'kode_jam_kerja' => 'required|unique:jam_kerja,kode_jam_kerja',
            'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
            'nama_jam_kerja' => 'required',
            'awal_jam_masuk' => 'required|date_format:H:i',
            'jam_masuk' => 'required|date_format:H:i',
            'akhir_jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'lintas_hari' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            DB::beginTransaction();

            $kode_lokasi_penugasan = $request->kode_lokasi_penugasan; // Misalkan ada field 'kode' di tabel lokasi_penugasan
            $kode_cabang = $request->kode_cabang; // Misalkan ada field 'kode' di tabel cabang

            // Ambil huruf pertama dari setiap kata di nama_jam_kerja
            $nama_jam_kerja = $request->nama_jam_kerja;
            $inisial_nama_jam_kerja = strtoupper(implode('', array_map(function($word) {
                return substr($word, 0, 1);
            }, explode(' ', $nama_jam_kerja))));

            // Generate kode_jam_kerja
            $kode_jam_kerja = 'JK' . $kode_lokasi_penugasan . $kode_cabang . $inisial_nama_jam_kerja;

            // Cek apakah kode_jam_kerja sudah ada
            if (JamKerja::where('kode_jam_kerja', $kode_jam_kerja)->exists()) {
                return redirect()->back()->with(['error' => 'Kode Jam Kerja sudah ada!'])->withInput();
            }

            // Simpan data
            JamKerja::create([
                'kode_jam_kerja' => $kode_jam_kerja,
                'kode_lokasi_penugasan' => $request->kode_lokasi_penugasan,
                'kode_cabang' => $request->kode_cabang,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintas_hari' => $request->lintas_hari,
            ]);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam.kerja')
                            ->with('success', 'Data Jam Kerja berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan Jam Kerja: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                            ->withInput();
        }
    }

    public function JamKerjaUpdate(Request $request, $kode_jam_kerja)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kode_lokasi_penugasan' => 'required|exists:lokasi_penugasan,kode_lokasi_penugasan',
            'kode_cabang' => 'required|exists:kantor_cabang,kode_cabang',
            'nama_jam_kerja' => 'required',
            'awal_jam_masuk' => 'required',
            'jam_masuk' => 'required',
            'akhir_jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'lintas_hari' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cari data jam kerja berdasarkan kode
            $jamKerja = JamKerja::where('kode_jam_kerja', $kode_jam_kerja)->first();

            if (!$jamKerja) {
                return redirect()->back()
                                ->with('error', 'Data Jam Kerja tidak ditemukan.')
                                ->withInput();
            }

            // Menyiapkan data untuk update
            $dataUpdate = [
                'kode_lokasi_penugasan' => $request->kode_lokasi_penugasan,
                'kode_cabang' => $request->kode_cabang,
                'nama_jam_kerja' => $request->nama_jam_kerja,
                'awal_jam_masuk' => $request->awal_jam_masuk,
                'jam_masuk' => $request->jam_masuk,
                'akhir_jam_masuk' => $request->akhir_jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'lintas_hari' => $request->lintas_hari,
            ];

            // Cek apakah ada perubahan data
            $hasChanges = false;
            foreach ($dataUpdate as $key => $value) {
                if ($jamKerja->$key != $value) {
                    $hasChanges = true;
                    break;
                }
            }

            if ($hasChanges) {
                // Update data jika ada perubahan
                $jamKerja->update($dataUpdate);
                DB::commit();

                return redirect()->route('admin.konfigurasi.jam.kerja')
                                ->with('success', 'Data Jam Kerja berhasil diperbarui!');
            } else {
                DB::rollBack();
                return redirect()->back()
                                ->with('info', 'Tidak ada perubahan pada data Jam Kerja.')
                                ->withInput();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui Jam Kerja: ' . $e->getMessage());

            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.')
                            ->withInput();
        }
    }

    public function JamKerjaDelete($kode_jam_kerja)
    {

        // Hapus data cabang dari database
        $delete = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();

        if ($delete) {
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);

        } else {
            return redirect()->back()->with(['warning' => 'Data Jam Kerja Tidak Ditemukan!']);
        }
    }

    public function JamKerjaDept(Request $request)
    {
        $user = auth()->user();
        // Ambil parameter pencarian dari request
        $kode_cabang = $request->input('kode_cabang');
        $kode_departemen = $request->input('kode_departemen');

        // Query untuk mengambil data jam kerja departemen menggunakan model
        $query = JamKerjaDept::with(['cabang', 'departemen']);

        if ($user->role === 'admin-cabang') {
            $query->where('kode_cabang', $user->kode_cabang);
        }

        // Tambahkan kondisi pencarian jika parameter ada
        if ($kode_cabang) {
            $query->where('kode_cabang', $kode_cabang);
        }

        if ($kode_departemen) {
            $query->where('kode_departemen', $kode_departemen);
        }

        // Ambil hasil query
        $jam_kerja_dept = $query->get();

        // Ambil data cabang dan departemen untuk dropdown menggunakan model
        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->orderBy('kode_cabang')->get();
            $nama_cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
            // dd($nama_cabang);
        } else {
            $cabang = Cabang::orderBy('nama_cabang')->get();
            $nama_cabang = null;
        }

        $departemen = Departemen::orderBy('nama_departemen')->get();

        // Kembalikan view dengan data yang diperlukan
        return view('konfigurasi.jam_kerja_dept', compact('jam_kerja_dept', 'cabang', 'departemen', 'nama_cabang'));
    }
    public function JamKerjaDeptCreate()
    {
        $user = auth()->user();

        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->orderBy('kode_cabang')->get();
        } else {
            $cabang = Cabang::orderBy('nama_cabang')->get();
        }

        $jam_kerja = JamKerja::orderBy('kode_jam_kerja')->get();
        // dd($jam_kerja);
        $departemen = Departemen::orderBy('nama_departemen')->get();
        return view('konfigurasi.jam_kerja_dept_create', compact('jam_kerja', 'cabang', 'departemen'));
    }

    public function getJamKerjaByCabang($kode_cabang)
    {
        $jam_kerja = JamKerja::where('kode_cabang', $kode_cabang)->get();
        return response()->json($jam_kerja);
    }

    public function JamKerjaDeptStore(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kode_cabang' => 'required|string',
            'kode_departemen' => 'required|string',
            'hari' => 'required|array',
            'kode_jam_kerja' => 'required|array',
            'kode_jam_kerja.*' => 'string', // Validasi setiap item dalam array
        ]);

        // if ($validator->fails()) {
        //     return redirect()->route('admin.konfigurasi.jam-kerja-dept')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $kode_cabang = $request->kode_cabang;
        $kode_departemen = $request->kode_departemen;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja;
        $kode_jk_dept = "J" . $kode_cabang . $kode_departemen;

        DB::beginTransaction();
        try {
            // Menyimpan data ke table jam_kerja_dept
            JamKerjaDept::insert([
                'kode_jk_dept' => $kode_jk_dept,
                'kode_cabang' => $kode_cabang,
                'kode_departemen' => $kode_departemen,
                'created_at' => Carbon::now()
            ]);

            // Menyimpan data ke table jam_kerja_dept_detail
            $data = [];
            for ($i = 0; $i < count($hari); $i++) {
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i],
                    'created_at' => Carbon::now()
                ];
            }
            JamKerjaDeptDetail::insert($data);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam-kerja-dept')->with(['success' => 'Jam Kerja Berhasil Disimpan!']);
        } catch (\Exception $e) {
            // Log error ke file log
            // \Log::error('Error saving jam kerja: '.$e->getMessage());
            DB::rollBack();
            // dd($e);
            // Tangani error duplicate entry secara spesifik
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorCode = $e->errorInfo[1];

                // Kode error 1062 adalah untuk duplicate entry
                if ($errorCode == 1062) {
                    // Ekstrak informasi yang spesifik
                    preg_match("/Duplicate entry '(.+)' for key/", $e->getMessage(), $matches);

                    $duplicateValue = $matches[1] ?? 'Data';

                    return redirect()->back()
                        ->with('error', "Jam Kerja dengan kode {$duplicateValue} sudah ada. Silakan gunakan Cabang atau Departemen yang berbeda.")
                        ->withInput();
                }
            }
            return redirect()->back()->with(key: ['error' => 'Jam Kerja Gagal Disimpan!' .$e->getMessage()]);
        }
    }

    public function JamKerjaDeptView($kode_jk_dept)
    {
        $jam_kerja_dept = JamKerjaDept::with(['cabang', 'departemen'])
                                        ->where('kode_jk_dept', $kode_jk_dept)
                                        ->first();

        $jam_kerja_dept_detail = JamKerjaDeptDetail::with('jamKerja')
                                                    ->where('kode_jk_dept', $kode_jk_dept)
                                                    ->get();

        // Ambil hanya jam kerja yang sesuai dengan kode cabang
        $jam_kerja = JamKerja::where('kode_cabang', $jam_kerja_dept->kode_cabang)
                    ->orderBy('kode_jam_kerja')
                    ->get();

        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_departemen')->get();

        return view('konfigurasi.jam_kerja_dept_view', compact('jam_kerja', 'cabang', 'departemen', 'jam_kerja_dept', 'jam_kerja_dept_detail'));
    }
    public function JamKerjaDeptEdit($kode_jk_dept)
    {
        // Ambil data jam kerja departemen
        $jam_kerja_dept = JamKerjaDept::where('kode_jk_dept', $kode_jk_dept)->first();

        // Periksa apakah data jam kerja departemen ditemukan
        if (!$jam_kerja_dept) {
            return redirect()->route('admin.konfigurasi.jam-kerja-dept')
                ->with('error', 'Data Jam Kerja Departemen tidak ditemukan');
        }

        // Ambil detail jam kerja departemen
        $jam_kerja_dept_detail = JamKerjaDeptDetail::where('kode_jk_dept', $kode_jk_dept)->get();

        // Ambil jam kerja berdasarkan kode cabang dari jam_kerja_dept
        $jam_kerja = JamKerja::where('kode_cabang', $jam_kerja_dept->kode_cabang) // Filter berdasarkan kode cabang
                            ->orderBy('kode_jam_kerja')
                            ->get();

        // Ambil data cabang dan departemen
        $cabang = Cabang::orderBy('nama_cabang')->get();
        $departemen = Departemen::orderBy('nama_departemen')->get();

        // Kembalikan view dengan data yang diperlukan
        return view('konfigurasi.jam_kerja_dept_edit', compact('jam_kerja', 'cabang', 'departemen', 'jam_kerja_dept', 'jam_kerja_dept_detail'));
    }

    public function JamKerjaDeptUpdate($kode_jk_dept, Request $request)
    {
        // Validasi input
        $request->validate([
            'hari' => 'required|array',
            'kode_jam_kerja' => 'required|array',
            'hari.*' => 'required|string',
            'kode_jam_kerja.*' => 'required|exists:jam_kerja,kode_jam_kerja'
        ], [
            'hari.required' => 'Pilih hari kerja.',
            'kode_jam_kerja.required' => 'Pilih jam kerja untuk setiap hari.',
            'kode_jam_kerja.*.exists' => 'Jam kerja yang dipilih tidak valid.'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah data jam kerja departemen ada
            $jamKerjaDept = JamKerjaDept::where('kode_jk_dept', $kode_jk_dept)->first();

            if (!$jamKerjaDept) {
                throw new \Exception('Data Jam Kerja Departemen tidak ditemukan.');
            }

            // Validasi tidak ada duplikat hari
            $uniqueHari = collect($request->hari)->unique();
            if (count($uniqueHari) !== count($request->hari)) {
                throw new \Exception('Terdapat duplikasi hari yang dipilih.');
            }

            // Ambil data lama untuk dibandingkan
            $oldData = JamKerjaDeptDetail::where('kode_jk_dept', $kode_jk_dept)
                                            ->get()
                                            ->keyBy('hari');

            // Siapkan data baru untuk perbandingan
            $newData = collect($request->hari)->mapWithKeys(function ($hari, $index) use ($request) {
                return [$hari => $request->kode_jam_kerja[$index]];
            });

            // Cek apakah ada perubahan
            $hasChanges = $newData->diffAssoc($oldData->pluck('kode_jam_kerja', 'hari'))->isNotEmpty();

            if (!$hasChanges) {
                // Tidak ada perubahan, kembalikan dengan pesan
                return redirect()->route('admin.konfigurasi.jam-kerja-dept')
                    ->with('info', 'Tidak ada perubahan data jam kerja.');
            }

            // Hapus data lama
            JamKerjaDeptDetail::where('kode_jk_dept', $kode_jk_dept)
                                ->delete();

            // Siapkan data baru
            $data = [];
            foreach ($request->hari as $index => $hari) {
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari,
                    'kode_jam_kerja' => $request->kode_jam_kerja[$index],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            // Masukkan data baru
            JamKerjaDeptDetail::insert($data);

            DB::commit();

            return redirect()->route('admin.konfigurasi.jam-kerja-dept')
                ->with('success', 'Jam Kerja Berhasil Diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Jam Kerja Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function JamKerjaDeptDelete($kode_jk_dept)
    {
        try {
            // Hapus data dari table jam_kerja_dept_detail
            JamKerjaDeptDetail::where('kode_jk_dept', $kode_jk_dept)->delete();
            // Hapus data dari table jam_kerja_dept
            JamKerjaDept::where('kode_jk_dept', $kode_jk_dept)->delete();
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['warning' => 'Data Gagal Dihapus!']);
        }

    }
}
