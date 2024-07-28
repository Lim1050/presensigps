<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PersetujuanSakitIzin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function IzinSakitCuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $months = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        if(!empty($request->bulan) && !empty($request->tahun)){
            $dataIzin = DB::table('pengajuan_izin')
                            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                            ->where('nik', $nik)
                            ->whereRaw('MONTH(tanggal_izin_dari)="' . $bulan . '"')
                            ->whereRaw('YEAR(tanggal_izin_dari)="' . $tahun . '"')
                            ->orderBy('tanggal_izin_dari', 'desc')
                            ->get();
        } else {
            $dataIzin = DB::table('pengajuan_izin')
                ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                ->where('nik', $nik)
                ->orderBy('tanggal_izin_dari', 'desc')
                // ->limit(5)
                ->get();
        }



        return view('presensi.sakit_izin', compact('dataIzin', 'months'));
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
    public function EditIzinAbsen($kode_izin)
    {
        $data_izin = DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->first();
        return view('izin.edit_izin_absen', compact('data_izin'));
    }
    public function UpdateIzinAbsen($kode_izin, Request $request)
    {
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $keterangan = $request->keterangan;

        try {
            $data = [
                'tanggal_izin_dari' => $tanggal_izin_dari,
                'tanggal_izin_sampai' => $tanggal_izin_sampai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $keterangan,
                'updated_at' => Carbon::now()
            ];

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update($data);
            return redirect()->route('izin')->with(['success' => 'Data berhasil diupdate!']);
        } catch (\Exception $e) {
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
    public function EditIzinSakit($kode_izin)
    {
        $data_izin = DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->first();
        return view('izin.edit_izin_sakit', compact('data_izin'));
    }
    public function UpdateIzinSakit(Request $request, $kode_izin)
    {
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $keterangan = $request->keterangan;

        $data_izin = DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->first();

        $old_surat_sakit = $data_izin->surat_sakit;

        //Simpan File Surat Sakit
            if ($request->hasFile('surat_sakit')) {
                Storage::delete('public/uploads/surat_sakit/' . $old_surat_sakit);
                $surat_sakit = $kode_izin . "." . $request->file('surat_sakit')->getClientOriginalExtension();
            } elseif ($old_surat_sakit != null) {
                $surat_sakit = $old_surat_sakit;
            } else {
                $surat_sakit = null;
            }

        try {
            $data = [
                'tanggal_izin_dari' => $tanggal_izin_dari,
                'tanggal_izin_sampai' => $tanggal_izin_sampai,
                'jumlah_hari' => $jumlah_hari,
                'keterangan' => $keterangan,
                'surat_sakit' => $surat_sakit,
                'updated_at' => Carbon::now()
            ];

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update($data);

            //Simpan File Surat Sakit
            if ($request->hasFile('surat_sakit')) {
                $surat_sakit = $kode_izin . "." . $request->file('surat_sakit')->getClientOriginalExtension();
                $folderPath = "public/uploads/surat_sakit/";
                $request->file('surat_sakit')->storeAs($folderPath, $surat_sakit);
            }

            return redirect()->route('izin')->with(['success' => 'Data berhasil diupdate!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data gagal diupdate!']);
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
    public function EditIzinCuti($kode_izin)
    {
        $data_izin = DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->first();
        $master_cuti = DB::table('master_cuti')->orderBy('nama_cuti')->get();
        return view('izin.edit_izin_cuti', compact('data_izin', 'master_cuti'));
    }
    public function UpdateIzinCuti(Request $request, $kode_izin)
    {
        $tanggal_izin_dari = $request->tanggal_izin_dari;
        $tanggal_izin_sampai = $request->tanggal_izin_sampai;
        $jumlah_hari = $request->jumlah_hari;
        $kode_cuti = $request->kode_cuti;
        $keterangan = $request->keterangan;

        try {
            $data = [
                'tanggal_izin_dari' => $tanggal_izin_dari,
                'tanggal_izin_sampai' => $tanggal_izin_sampai,
                'jumlah_hari' => $jumlah_hari,
                'kode_cuti' => $kode_cuti,
                'keterangan' => $keterangan,
                'updated_at' => Carbon::now()
            ];
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update($data);
            return redirect()->route('izin')->with(['success' => 'Data berhasil diupdate!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data gagal diupdate!']);
        }
    }

    public function DeleteIzin($kode_izin)
    {
        $cek_data_izin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $surat_sakit = $cek_data_izin->surat_sakit;
        try {

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if($surat_sakit != null) {
                Storage::delete('public/uploads/surat_sakit/' . $surat_sakit);
            }
            return redirect()->back()->with(['success' => 'Data berhasil dihapus!']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['success' => 'Data berhasil dihapus!']);
        }
    }

    public function IzinShowAct($kode_izin)
    {
        $data_izin = DB::table('pengajuan_izin')
            ->where('kode_izin', $kode_izin)
            ->first();

        return view('izin.izin_showact', compact('data_izin'));
    }

    public function PersetujuanSakitIzin(Request $request)
    {

        $query = PersetujuanSakitIzin::query();
        $query->select('kode_izin', 'tanggal_izin_dari', 'tanggal_izin_sampai', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');
        $query->orderBy('tanggal_izin_dari', 'desc');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal_izin_dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', 'like', '%' .  $request->nik . "%");
        }
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%'. $request->nama_lengkap . '%');
        }
        if (!empty($request->jabatan)) {
            $query->where('jabatan', 'like', '%'. $request->jabatan . '%');
        }
        if ($request->status_approved != '') {
            $query->where('status_approved', $request->status_approved);
        }

        $sakit_izin = $query->paginate('10');
        $sakit_izin->appends($request->all());
        // dd($sakit_izin);
        return view('presensi.persetujuan_sakit_izin', compact('sakit_izin'));
    }
    public function ApprovalSakitIzin(Request $request)
    {
        $kode_izin = $request->kode_izin;
        $status_approved = $request->status_approved;
        $dataIzin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $tanggal_dari = $dataIzin->tanggal_izin_dari;
        $tanggal_sampai = $dataIzin->tanggal_izin_sampai;
        $nik = $dataIzin->nik;
        $status = $dataIzin->status;

        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
               while(strtotime($tanggal_dari) <= strtotime($tanggal_sampai)){
                    // save data to table presensi
                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tanggal_presensi' => $tanggal_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin,
                        'created_at' => Carbon::now(),
                    ]);
                    $tanggal_dari = date("Y-m-d", strtotime("+1 days", strtotime($tanggal_dari)));
                }
            }

            // save data to table pengajuan izin
            DB::table('pengajuan_izin')
                ->where('kode_izin', $kode_izin)
                ->update([
                    'status_approved' => $status_approved
                ]);

            DB::commit();
            return redirect()->back()->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
    public function BatalkanSakitIzin($kode_izin)
    {
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin')
                        ->where('kode_izin', $kode_izin)
                        ->update([
                            'status_approved' => 0
                        ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }
        // $update = DB::table('pengajuan_izin')
        //     ->where('kode_izin', $kode_izin)
        //     ->update([
        //         'status_approved' => 0
        //     ]);

        // if($update){
        //     return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
        // } else {
        //     return redirect()->back()->with(['warning' => 'Data Gagal Diupdate!']);
        // }
    }

    public function CekPengajuanSakitIzin(Request $request)
    {
        $tanggal_izin = $request->tanggal_izin;
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tanggal_izin_dari', $tanggal_izin)->count();
        return $cek;
    }
}
