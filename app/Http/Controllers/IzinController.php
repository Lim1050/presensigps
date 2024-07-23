<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IzinController extends Controller
{
    public function IzinSakitCuti()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataIzin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->where('nik', $nik)
            ->get();
        // dd($dataIzin);
        return view('presensi.sakit_izin', compact('dataIzin'));
    }

    public function CreateIzinAbsen()
    {
        return view('izin.create_izin_absen');
    }
    public function StoreIzinAbsen(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $status = 'izin';
        $keterangan = $request->keterangan;

        // setting kode_izin
        $bulan = date("m", strtotime($tanggal_izin_dari));
        $tahun = date("Y", strtotime($tanggal_izin_dari));
        $thn = substr($tahun, 2,2);
        $last_izin = DB::table('pengajuan_izin')
                        ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan . '"')
                        ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun . '"')
                        ->orderBy('kode_izin', 'desc')
                        ->first();
        $last_kode_izin = $last_izin != null ? $last_izin->kode_izin : "";
        $format = "IA".$bulan.$thn;

        $kode_izin = buatkode($last_kode_izin, $format, 3);

        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tanggal_izin_dari' => $tanggal_izin_dari,
            'tanggal_izin_sampai' => $tanggal_izin_sampai,
            'jumlah_hari' => $jumlah_hari,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => Carbon::now()
        ];

        $save = DB::table('pengajuan_izin')->insert($data);

        if($save){
            return redirect()->route('izin')->with(['success' => 'Data berhasil disimpan!']);
        } else {
            return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
        }
    }

    public function CreateIzinSakit()
    {
        return view('izin.create_izin_sakit');
    }
    public function StoreIzinSakit(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $status = 'sakit';
        $keterangan = $request->keterangan;

        // setting kode_izin
        $bulan = date("m", strtotime($tanggal_izin_dari));
        $tahun = date("Y", strtotime($tanggal_izin_dari));
        $thn = substr($tahun, 2,2);
        $last_izin = DB::table('pengajuan_izin')
                        ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan . '"')
                        ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun . '"')
                        ->orderBy('kode_izin', 'desc')
                        ->first();
        $last_kode_izin = $last_izin != null ? $last_izin->kode_izin : "";
        $format = "IS".$bulan.$thn;

        $kode_izin = buatkode($last_kode_izin, $format, 3);

        //Simpan File Surat Sakit
            if ($request->hasFile('surat_sakit')) {
            $surat_sakit = $kode_izin . "." . $request->file('surat_sakit')->getClientOriginalExtension();
            } else {
            $surat_sakit = null;
            }

        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tanggal_izin_dari' => $tanggal_izin_dari,
            'tanggal_izin_sampai' => $tanggal_izin_sampai,
            'jumlah_hari' => $jumlah_hari,
            'status' => $status,
            'keterangan' => $keterangan,
            'surat_sakit' => $surat_sakit,
            'created_at' => Carbon::now()
        ];

        $save = DB::table('pengajuan_izin')->insert($data);

        if($save){
            //Simpan File Surat Sakit
            if ($request->hasFile('surat_sakit')) {
            $surat_sakit = $kode_izin . "." . $request->file('surat_sakit')->getClientOriginalExtension();
            $folderPath = "public/uploads/surat_sakit/";
            $request->file('surat_sakit')->storeAs($folderPath, $surat_sakit);
            }
            return redirect()->route('izin')->with(['success' => 'Data berhasil disimpan!']);
        } else {
            return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
        }
    }

    // Admin Master Cuti
    public function CutiMaster()
    {
        $cuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izin.admin_cuti_master', compact('cuti'));
    }
    public function CutiMasterStore(Request $request)
    {
        $kode_cuti = $request->kode_cuti;
        $nama_cuti = $request->nama_cuti;
        $jumlah_hari = $request->jumlah_hari;

        $cek_cuti = DB::table('master_cuti')
                        ->where('kode_cuti', $kode_cuti)->count();
        if ($cek_cuti > 0) {
            return redirect()->back()->with(['error' => 'Data Kode Cuti Sudah Ada!']);
        }

        try {
            DB::table('master_cuti')->insert([
                'kode_cuti' => $kode_cuti,
                'nama_cuti' => $nama_cuti,
                'jumlah_hari' => $jumlah_hari,
                'created_at' => Carbon::now(),
            ]);
            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan! ' . $e->getMessage()]);
        }
    }
    public function CutiMasterUpdate(Request $request, $kode_cuti)
    {
        $kode_cuti = $request->kode_cuti;
        $nama_cuti = $request->nama_cuti;
        $jumlah_hari = $request->jumlah_hari;

        try {
            $data = [
                'nama_cuti' => $nama_cuti,
                'jumlah_hari' => $jumlah_hari,
                'updated_at' => Carbon::now(),
            ];

            DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->update($data);

            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate! ' . $e->getMessage()]);
        }
    }
    public function CutiMasterDelete($kode_cuti)
    {
         // Hapus data cuti dari database
        $delete = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->delete();

        if ($delete) {
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);

        } else {
            return redirect()->back()->with(['warning' => 'Data Jam Kerja Tidak Ditemukan!']);
        }
    }

    public function CreateIzinCuti()
    {
        $master_cuti = DB::table('master_cuti')->orderBy('nama_cuti')->get();
        return view('izin.create_izin_cuti', compact('master_cuti'));
    }
    public function StoreIzinCuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $kode_cuti = $request->kode_cuti;
        $status = 'cuti';
        $keterangan = $request->keterangan;

        // setting kode_izin
        $bulan = date("m", strtotime($tanggal_izin_dari));
        $tahun = date("Y", strtotime($tanggal_izin_dari));
        $thn = substr($tahun, 2,2);
        $last_izin = DB::table('pengajuan_izin')
                        ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan . '"')
                        ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun . '"')
                        ->orderBy('kode_izin', 'desc')
                        ->first();
        $last_kode_izin = $last_izin != null ? $last_izin->kode_izin : "";
        $format = "IC".$bulan.$thn;

        $kode_izin = buatkode($last_kode_izin, $format, 3);

        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tanggal_izin_dari' => $tanggal_izin_dari,
            'tanggal_izin_sampai' => $tanggal_izin_sampai,
            'jumlah_hari' => $jumlah_hari,
            'kode_cuti' => $kode_cuti,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => Carbon::now()
        ];

        $save = DB::table('pengajuan_izin')->insert($data);

        if($save){
            return redirect()->route('izin')->with(['success' => 'Data berhasil disimpan!']);
        } else {
            return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
        }
    }
}
