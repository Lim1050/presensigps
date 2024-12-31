<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\LokasiPenugasan;
use App\Models\PersetujuanSakitIzin;
use App\Models\presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPresensiExport;
use App\Models\JamKerjaKaryawan;
use App\Models\Karyawan;
use App\Models\Lembur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    public function gethari($hari)
    {
        // $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak diketahui";
                break;
        }
        return $hari_ini;
    }

    public function PresensiCreate()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_lokasi_penugasan = Auth::guard('karyawan')->user()->kode_lokasi_penugasan;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

        $hari_ini = date("Y-m-d");
        $nama_hari = $this->gethari(date('D', strtotime($hari_ini)));

        // Cek presensi hari sebelumnya untuk lintas hari
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hari_ini)));
        $presensi_sebelumnya = Presensi::with('JamKerja')
            ->where('tanggal_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $cek_lintas_hari = $presensi_sebelumnya != null ? $presensi_sebelumnya->lintas_hari : 0;

        if ($cek_lintas_hari == 1 && $presensi_sebelumnya->foto_keluar == null) {
            $hari_ini = $tgl_sebelumnya;
        }

        // Get presensi hari ini
        $presensi_hari_ini = Presensi::query()
            ->with('PengajuanIzin')
            ->where('nik', $nik)
            ->where('tanggal_presensi', $hari_ini)
            ->first();

        // dd($presensi_hari_ini->kode_lembur, $presensi_hari_ini->mulai_lembur, $presensi_hari_ini->selesai_lembur);

        // Cek lembur yang disetujui
        $lembur_hari_ini = Lembur::where('nik', $nik)
            ->where('tanggal_presensi', $hari_ini)
            ->where('status', 'disetujui')
            ->first();

        // Get jam kerja normal
        $jam_kerja_normal = JamKerjaKaryawan::with('jamKerja')
            ->where('nik', $nik)
            ->where('hari', $nama_hari)
            ->first();

        // Data yang dibutuhkan view
        $data = [
            'nama_hari' => $nama_hari,
            'cek_masuk' => $presensi_hari_ini && $presensi_hari_ini->foto_masuk ? 1 : 0,
            'cek_keluar' => $presensi_hari_ini && $presensi_hari_ini->foto_keluar ? 1 : 0,
            'cek_mulai_lembur' => $presensi_hari_ini && $presensi_hari_ini->kode_lembur && $presensi_hari_ini->mulai_lembur ? 1 : 0,
            'cek_selesai_lembur' => $presensi_hari_ini && $presensi_hari_ini->kode_lembur && $presensi_hari_ini->selesai_lembur ? 1 : 0,
            'foto_keluar' => $presensi_hari_ini ? $presensi_hari_ini->foto_keluar : null,
            'lokasi_penugasan' => LokasiPenugasan::where('kode_lokasi_penugasan', $kode_lokasi_penugasan)->first(),
            'cek_izin' => $presensi_hari_ini,
            'hari_ini' => $hari_ini,
            'cek_lintas_hari' => $cek_lintas_hari,
            'cek_presensi_sebelumnya' => $presensi_sebelumnya,
            'jam_kerja_karyawan' => $jam_kerja_normal, // Jam kerja normal
            'lembur_hari_ini' => $lembur_hari_ini // Data lembur
        ];

        // dd($data);

        // Return view yang sesuai
        if ($data['jam_kerja_karyawan'] == null && !$lembur_hari_ini) {
            return view('presensi.jadwal_kosong', $data);
        } elseif ($presensi_hari_ini && $presensi_hari_ini->status !== 'hadir') {
            $data['presensi_hari_ini'] = $presensi_hari_ini;
            return view('presensi.jadwal_izin', $data);
        } else {
            return view('presensi.create_presensi', $data);
        }
    }


    public function PresensiStore(Request $request)
    {
        try {
            // Inisiasi data
            $hari_ini = date("Y-m-d");
            $jam_sekarang = date("H:i:s");
            $nik = Auth::guard('karyawan')->user()->nik;

            // Cek lintas hari
            $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hari_ini)));
            $cek_presensi_sebelumnya = DB::table('presensi')
                ->where('tanggal_presensi', $tgl_sebelumnya)
                ->where('nik', $nik)
                ->first();

            $tgl_presensi = ($cek_presensi_sebelumnya && !$cek_presensi_sebelumnya->jam_keluar)
                ? $tgl_sebelumnya
                : $hari_ini;

            // Format jam untuk nama file
            $jam_save = str_replace(':', '', $jam_sekarang);

            // Validasi input
            if (!$request->has('image') || !$request->has('lokasi')) {
                throw new \Exception("Data absensi tidak lengkap", 400);
            }

            // Get jam kerja dan lembur
            $nama_hari = $this->gethari(date('D', strtotime($tgl_presensi)));
            $jam_kerja_karyawan = $this->getJamKerja($nik, $nama_hari);
            $lembur_hari_ini = DB::table('lembur')
                ->where('nik', $nik)
                ->where('tanggal_presensi', $tgl_presensi)
                ->where('status', 'disetujui')
                ->first();

            // Jika tidak ada jam kerja dan tidak ada lembur
            if (!$jam_kerja_karyawan && !$lembur_hari_ini) {
                throw new \Exception("Tidak ada jadwal kerja atau lembur untuk hari ini", 422);
            }

            // Validasi lokasi (hanya untuk jam kerja normal)
            $lokasi_penugasan = DB::table('lokasi_penugasan')
                ->where('kode_lokasi_penugasan', Auth::guard('karyawan')->user()->kode_lokasi_penugasan)
                ->first();

            if (!$lokasi_penugasan) {
                throw new \Exception("Lokasi penugasan tidak ditemukan", 404);
            }

            $radius = $this->validateLocation($request->lokasi, $lokasi_penugasan);
            if ($radius > $lokasi_penugasan->radius) {
                throw new \Exception("Maaf, anda diluar radius, jarak anda " . $radius . " meter dari kantor!", 403);
            }

            // Cek presensi hari ini
            $cek_presensi = DB::table('presensi')
                ->where('tanggal_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->first();

                // dd($jam_sekarang);

            // Tentukan jenis dan kondisi absensi
            $kondisi_absensi = $this->tentukan_kondisi_absensi(
                $jam_kerja_karyawan,
                $lembur_hari_ini,
                $cek_presensi,
                $jam_sekarang
            );

            // dd(vars: $kondisi_absensi);

            // Proses absensi sesuai kondisi
            switch($kondisi_absensi['jenis']) {
                // Absen Masuk
                case 'absen_masuk_normal_tanpa_lembur':
                    return $this->prosesAbsenMasukNormal(
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan,
                        $tgl_presensi,
                        $cek_presensi
                    );

                case 'absen_masuk_normal_lembur_setelah':
                    return $this->prosesAbsenMasukNormal(
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan,
                        $tgl_presensi,
                        $cek_presensi
                    );

                case 'absen_masuk_normal_lembur_sebelum':
                    return $this->prosesAbsenMasukNormal(
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan,
                        $tgl_presensi,
                        $cek_presensi
                    );

                // Absen Pulang
                case 'absen_pulang_normal_tanpa_lembur':
                    return $this->prosesAbsenPulangNormal(
                        $cek_presensi,
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan
                    );

                case 'absen_pulang_normal_lembur_setelah':
                    return $this->prosesAbsenPulangNormal(
                        $cek_presensi,
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan
                    );

                case 'absen_pulang_normal_lembur_sebelum':
                    return $this->prosesAbsenPulangNormal(
                        $cek_presensi,
                        $jam_sekarang,
                        $request->image,
                        $request->lokasi,
                        $jam_kerja_karyawan
                    );

                // Absen Lembur
                case 'absen_mulai_lembur_sebelum':
                    return $this->prosesAbsenMasukLembur(
                        $jam_sekarang,
                        $lembur_hari_ini,
                        $cek_presensi,
                        $tgl_presensi
                    );

                case 'absen_selesai_lembur_sebelum':
                    return $this->prosesAbsenSelesaiLembur(
                        $cek_presensi,
                        $jam_sekarang,
                        $lembur_hari_ini
                    );

                case 'absen_mulai_lembur_setelah':
                    return $this->prosesAbsenMasukLembur(
                        $jam_sekarang,
                        $lembur_hari_ini,
                        $cek_presensi,
                        $tgl_presensi
                    );

                case 'absen_selesai_lembur_setelah':
                    return $this->prosesAbsenSelesaiLembur(
                        $cek_presensi,
                        $jam_sekarang,
                        $lembur_hari_ini
                    );

                default:
                    throw new \Exception("Tidak dapat melakukan absensi saat ini", 422);
            }
        } catch (\Exception $e) {
            dd($e);
            // Log error
            Log::error('Presensi Store Error: ' . $e->getMessage(), [
                'nik' => Auth::guard('karyawan')->user()->nik ?? 'Unknown',
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Kembalikan pesan error yang sesuai
            $error_code = $e->getCode() ?: 500;
            $error_message = $e->getMessage();

            // Konversi ke format sebelumnya
            return "error|{$error_message}|system";
        }
    }

    private function tentukan_kondisi_absensi($jam_kerja, $lembur, $presensi, $jam_sekarang)
    {
        // Konversi waktu ke timestamp untuk perbandingan
        $ts_jam_sekarang = strtotime($jam_sekarang);

        // Jika tidak ada jam kerja dan lembur
        if (!$jam_kerja && !$lembur) {
            return ['jenis' => 'error', 'pesan' => 'Tidak ada jadwal'];
        }
        // dd($presensi->jam_keluar);
        // Parameter jam kerja
        $ts_jam_masuk_normal = strtotime($jam_kerja->jam_masuk);
        $ts_jam_pulang_normal = strtotime($jam_kerja->jam_pulang);
        $ts_awal_jam_masuk = strtotime($jam_kerja->awal_jam_masuk);
        $ts_akhir_jam_masuk = strtotime($jam_kerja->akhir_jam_masuk);

        // Parameter lembur
        $ts_mulai_lembur = $lembur ? strtotime($lembur->waktu_mulai) : null;
        $ts_selesai_lembur = $lembur ? strtotime($lembur->waktu_selesai) : null;

        // Skenario 1: Absen Masuk Jam Kerja Normal Tanpa Lembur
        if (!$lembur && !$presensi) {
            if ($ts_jam_sekarang >= $ts_awal_jam_masuk && $ts_jam_sekarang <= $ts_akhir_jam_masuk) {
                return [
                    'jenis' => 'absen_masuk_normal_tanpa_lembur',
                    'pesan' => 'Absen Masuk Normal Tanpa Lembur'
                ];
            }
        }

        // Skenario 2: Absen Masuk Jam Kerja Normal Dengan Lembur Setelah Jam Pulang Normal
        if ($lembur && $ts_mulai_lembur >= $ts_jam_pulang_normal) {
            if ($ts_jam_sekarang >= $ts_awal_jam_masuk && $ts_jam_sekarang <= $ts_akhir_jam_masuk) {
                return [
                    'jenis' => 'absen_masuk_normal_lembur_setelah',
                    'pesan' => 'Absen Masuk Normal Dengan Lembur Setelah Jam Pulang'
                ];
            }
        }

        // Skenario 3: Absen Masuk Jam Kerja Normal Dengan Lembur Sebelum Jam Masuk Normal
        if ($lembur && $ts_selesai_lembur <= $ts_jam_masuk_normal && $presensi && $presensi->selesai_lembur && !$presensi->jam_masuk) {
            if ($ts_jam_sekarang >= $ts_awal_jam_masuk && $ts_jam_sekarang <= $ts_akhir_jam_masuk) {
                return [
                    'jenis' => 'absen_masuk_normal_lembur_sebelum',
                    'pesan' => 'Absen Masuk Normal Dengan Lembur Sebelum Jam Masuk'
                ];
            }
        }

        // Skenario 4: Absen Pulang Jam Kerja Normal Tanpa Lembur
        if (!$lembur && $presensi && $presensi->jam_masuk && !$presensi->jam_keluar) {
            $ts_awal_absen_pulang = strtotime($jam_kerja->jam_pulang . ' - 30 minutes');
            $ts_akhir_absen_pulang = strtotime($jam_kerja->jam_pulang . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_pulang && $ts_jam_sekarang <= $ts_akhir_absen_pulang) {
                return [
                    'jenis' => 'absen_pulang_normal_tanpa_lembur',
                    'pesan' => 'Absen Pulang Normal Tanpa Lembur'
                ];
            }
        }

        // Skenario 5: Absen Pulang Jam Kerja Normal Dengan Lembur Setelah Jam Pulang Normal
        if ($lembur && $ts_mulai_lembur >= $ts_jam_pulang_normal && $presensi && $presensi->jam_masuk && !$presensi->jam_keluar) {
            $ts_awal_absen_pulang = strtotime($jam_kerja->jam_pulang . ' - 30 minutes');
            $ts_akhir_absen_pulang = strtotime($jam_kerja->jam_pulang . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_pulang && $ts_jam_sekarang <= $ts_akhir_absen_pulang) {
                return [
                    'jenis' => 'absen_pulang_normal_lembur_setelah',
                    'pesan' => 'Absen Pulang Normal Dengan Lembur Setelah Jam Pulang'
                ];
            }
        }

        // Skenario 6: Absen Pulang Jam Kerja Normal Dengan Lembur Sebelum Jam Masuk Normal
        if ($lembur && $ts_selesai_lembur <= $ts_jam_masuk_normal && $presensi && !$presensi->jam_keluar) {
            $ts_awal_absen_pulang = strtotime($jam_kerja->jam_pulang . ' - 30 minutes');
            $ts_akhir_absen_pulang = strtotime($jam_kerja->jam_pulang . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_pulang && $ts_jam_sekarang <= $ts_akhir_absen_pulang) {
                return [
                    'jenis' => 'absen_pulang_normal_lembur_sebelum',
                    'pesan' => 'Absen Pulang Normal Dengan Lembur Sebelum Jam Masuk'
                ];
            }
        }
        // dd($presensi->mulai_lembur);
        // Skenario 7: Absen Mulai Lembur Sebelum Jam Kerja Normal
        if ($lembur && $ts_mulai_lembur <= $ts_jam_masuk_normal && !$presensi) {
            $ts_awal_absen_mulai_lembur = strtotime($lembur->waktu_mulai . ' - 30 minutes');
            $ts_akhir_absen_mulai_lembur = strtotime($lembur->waktu_mulai . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_mulai_lembur && $ts_jam_sekarang <= $ts_akhir_absen_mulai_lembur) {
                return [
                    'jenis' => 'absen_mulai_lembur_sebelum',
                    'pesan' => 'Absen Mulai Lembur Sebelum Jam Kerja Normal'
                ];
            }
        }

        // Skenario 8: Absen Selesai Lembur Sebelum Jam Kerja Normal
        if ($lembur && $ts_selesai_lembur <= $ts_jam_masuk_normal && $presensi && $presensi->mulai_lembur != null) {
            $ts_awal_absen_selesai_lembur = strtotime($lembur->waktu_selesai . ' - 30 minutes');
            $ts_akhir_absen_selesai_lembur = strtotime($lembur->waktu_selesai . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_selesai_lembur && $ts_jam_sekarang <= $ts_akhir_absen_selesai_lembur) {
                return [
                    'jenis' => 'absen_selesai_lembur_sebelum',
                    'pesan' => 'Absen Selesai Lembur Sebelum Jam Kerja Normal'
                ];
            }
        }

        // Skenario 9: Absen Mulai Lembur Setelah Jam Kerja Normal
        if ($lembur && $ts_mulai_lembur >= $ts_jam_pulang_normal && $presensi->jam_keluar != null) {
            $ts_awal_absen_mulai_lembur = strtotime($lembur->waktu_mulai . ' - 30 minutes');
            $ts_akhir_absen_mulai_lembur = strtotime($lembur->waktu_mulai . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_mulai_lembur && $ts_jam_sekarang <= $ts_akhir_absen_mulai_lembur) {
                return [
                    'jenis' => 'absen_mulai_lembur_setelah',
                    'pesan' => 'Absen Mulai Lembur Setelah Jam Kerja Normal'
                ];
            }
        }

        // Skenario 10: Absen Selesai Lembur Setelah Jam Kerja Normal
        if ($lembur && $ts_selesai_lembur > $ts_jam_pulang_normal) {
            $ts_awal_absen_selesai_lembur = strtotime($lembur->waktu_selesai . ' - 30 minutes');
            $ts_akhir_absen_selesai_lembur = strtotime($lembur->waktu_selesai . ' + 30 minutes');

            if ($ts_jam_sekarang >= $ts_awal_absen_selesai_lembur && $ts_jam_sekarang <= $ts_akhir_absen_selesai_lembur) {
                return [
                    'jenis' => 'absen_selesai_lembur_setelah',
                    'pesan' => 'Absen Selesai Lembur Setelah Jam Kerja Normal'
                ];
            }
        }

        return ['jenis' => 'error', 'pesan' => 'Tidak dapat melakukan absensi saat ini'];
    }

    private function prosesAbsenMasukNormal($jam_sekarang, $image, $lokasi, $jam_kerja, $tgl_presensi, $presensi)
    {
        // Tentukan jam masuk yang digunakan
        $jam_masuk_minimal = $jam_kerja->awal_jam_masuk;
        $jam_masuk_maksimal = $jam_kerja->akhir_jam_masuk;

        if ($jam_sekarang < $jam_masuk_minimal) {
            return "error|Maaf Belum Waktunya Melakukan Presensi!|in";
        }

        if ($jam_sekarang > $jam_masuk_maksimal) {
            return "error|Maaf Sudah Melewati Waktu Melakukan Presensi!|in";
        }

        // Proses foto
        $foto = $this->processFoto($image, Auth::guard('karyawan')->user()->nik, $tgl_presensi, str_replace(':', '', $jam_sekarang));

        // Data dasar untuk presensi masuk
        $data_masuk = [
            'nik' => Auth::guard('karyawan')->user()->nik,
            'tanggal_presensi' => $tgl_presensi,
            'jam_masuk' => $jam_sekarang,
            'foto_masuk' => $foto,
            'lokasi_masuk' => $lokasi,
            'kode_jam_kerja' => $jam_kerja->kode_jam_kerja,
            'status' => 'hadir',
            'created_at' => Carbon::now()
        ];

        if ($presensi) {
            // Update data presensi jika sudah ada
            DB::table('presensi')->where('id', $presensi->id)->update($data_masuk);
        } else {
            // Insert data baru jika belum ada
            DB::table('presensi')->insert($data_masuk);
        }

        return "success|Terima kasih, Selamat bekerja!|in";
    }

    private function prosesAbsenPulangNormal($presensi, $jam_sekarang, $image, $lokasi, $jam_kerja)
    {
        // Tentukan jam pulang yang digunakan
        $jam_pulang = $jam_kerja->jam_pulang;

        // Toleransi pulang lebih awal (30 menit)
        $toleransi_pulang =  30;
        $jam_pulang_minimal = Carbon::parse($jam_pulang)->subMinutes($toleransi_pulang)->format('H:i:s');

        if (Carbon::parse($jam_sekarang)->lt(Carbon::parse($jam_pulang_minimal))) {
            $sisa_waktu = Carbon::parse($jam_pulang_minimal)->diffInMinutes(Carbon::parse($jam_sekarang));
            return "error|Maaf Belum Waktunya Pulang! Sisa waktu: {$sisa_waktu} menit|out";
        }

        $data_pulang = [
            'jam_keluar' => $jam_sekarang,
            'foto_keluar' => $this->processFoto($image, Auth::guard('karyawan')->user()->nik, $presensi->tanggal_presensi, str_replace(':', '', $jam_sekarang)),
            'lokasi_keluar' => $lokasi,
            'updated_at' => Carbon::now()
        ];

        if (DB::table('presensi')->where('id', $presensi->id)->update($data_pulang)) {
            return "success|Terima kasih, Hati-hati di jalan!|out";
        }

        return "error|Maaf Gagal absen, hubungi Tim IT|out";
    }

    private function prosesAbsenMasukLembur($jam_sekarang, $lembur, $presensi, $tgl_presensi)
    {
        // Data dasar untuk presensi lembur
        // dd($presensi);


        // dd(vars: $data_lembur);

        if ($presensi) {
            $data_lembur = [
                'kode_lembur' => $lembur->kode_lembur,
                'jenis_absen_lembur' => $lembur->jenis_lembur,
                'updated_at' => Carbon::now(),
                'lembur' => 1,
                'mulai_lembur' => $jam_sekarang,
            ];
            // Update data lembur jika sudah ada
            DB::table('presensi')->where('id', $presensi->id)->update($data_lembur);
        } else {
            $data_lembur = [
                'nik' => Auth::guard('karyawan')->user()->nik,
                'tanggal_presensi' => $tgl_presensi,
                'status' => 'hadir',
                'kode_lembur' => $lembur->kode_lembur,
                'jenis_absen_lembur' => $lembur->jenis_lembur,
                'created_at' => Carbon::now(),
                'lembur' => 1,
                'mulai_lembur' => $jam_sekarang,
            ];
            // Insert data baru jika belum ada
            DB::table('presensi')->insert($data_lembur);
        }

        return "success|Terima kasih, Selamat bekerja lembur!|in";
    }

    private function prosesAbsenSelesaiLembur($presensi, $jam_sekarang, $lembur)
    {
        // Update data presensi untuk selesai lembur
        $data_selesai_lembur = [
            'selesai_lembur' => $jam_sekarang,
            'updated_at' => Carbon::now()
        ];

        if (DB::table('presensi')->where('id', $presensi->id)->update($data_selesai_lembur)) {
            return "success|Terima kasih, Anda telah selesai lembur!|out";
        }

        return "error|Maaf Gagal menyelesaikan lembur, hubungi Tim IT|out";
    }

    private function processFoto($image, $nik, $tgl_presensi, $jam_save)
    {
        $folderPath = "public/uploads/absensi/";

        // Tentukan jenis absen (masuk/keluar)
        $cek = DB::table('presensi')
            ->where('tanggal_presensi', $tgl_presensi)
            ->where('nik', $nik)
            ->count();
        $ket = ($cek > 0) ? "keluar" : "masuk";

        // Format nama file
        $formatName = $nik . "-" . $tgl_presensi . "-" . $jam_save . "-" . $ket;
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // Simpan foto
        Storage::put($file, base64_decode(explode(";base64,", $image)[1]));

        return $file;
    }

    private function getJamKerja($nik, $nama_hari)
    {
        $jam_kerja = DB::table('jam_kerja_karyawan')
            ->join('jam_kerja', 'jam_kerja_karyawan.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('hari', $nama_hari)
            ->first();

        if (!$jam_kerja) {
            $jam_kerja = DB::table('jam_kerja_dept_detail')
                ->join('jam_kerja_dept', 'jam_kerja_dept_detail.kode_jk_dept', '=', 'jam_kerja_dept.kode_jk_dept')
                ->join('jam_kerja', 'jam_kerja_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('kode_departemen', Auth::guard('karyawan')->user()->kode_departemen)
                ->where('kode_cabang', Auth::guard('karyawan')->user()->kode_cabang)
                ->where('hari', $nama_hari)
                ->first();
        }

        return $jam_kerja;
    }

    // Menghitung Jarak
    private function validateLocation($lokasi_user, $lokasi_penugasan)
    {
        $lokasi = explode(",", $lokasi_user);
        $latitude_user = $lokasi[0];
        $longitude_user = $lokasi[1];

        $lokasi_penugasan = explode(",", $lokasi_penugasan->lokasi_penugasan);
        $latitude_lokasi_penugasan = $lokasi_penugasan[0];
        $longitude_lokasi_penugasan = $lokasi_penugasan[1];

        return $this->distance($latitude_lokasi_penugasan, $longitude_lokasi_penugasan, $latitude_user, $longitude_user)["meters"];
    }

    private function distance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) +
                (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return compact('meters');
    }

    public function PresensiHistory()
    {
        // $monthNumber = date("m");

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

        // $monthName = $months[$monthNumber];
        return view('presensi.history', compact('months'));
    }

    // public function GetHistory(Request $request)
    // {
    //     $bulan = $request->bulan;
    //     $tahun = $request->tahun;
    //     $nik = Auth::guard('karyawan')->user()->nik;

    //     // Tampilkan data presensi sesuai bulan dan tahun
    //     $history = DB::table('presensi')
    //         ->select('presensi.*',
    //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur" ELSE jam_kerja.nama_jam_kerja END as nama_jam_kerja'),
    //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_kerja_masuk'),
    //             DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai ELSE jam_kerja.jam_pulang END as jam_pulang'),
    //             'pengajuan_izin.keterangan',
    //             'lembur.waktu_mulai as mulai_lembur',
    //             'lembur.waktu_selesai as selesai_lembur')
    //         ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //         ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
    //         ->leftJoin('lembur', function($join) {
    //             $join->on('presensi.nik', '=', 'lembur.nik')
    //                 ->on('presensi.tanggal_presensi', '=', 'lembur.tanggal_presensi')
    //                 ->where('lembur.status', '=', 'disetujui');
    //         })
    //         ->where('presensi.nik', $nik)
    //         ->whereRaw('MONTH(presensi.tanggal_presensi) = ?', [$bulan])
    //         ->whereRaw('YEAR(presensi.tanggal_presensi) = ?', [$tahun])
    //         ->orderBy('presensi.tanggal_presensi', 'desc')
    //         ->get();

    //     return view('presensi.gethistory', compact('history'));
    // }

    public function GetHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $history = Presensi::with(['jamKerja', 'pengajuanIzin', 'lembur'])
            ->where('nik', $nik)
            ->whereMonth('tanggal_presensi', operator: $bulan)
            ->whereYear('tanggal_presensi', operator: $tahun)
            ->orderBy('tanggal_presensi', 'desc')
            ->get()
            ->map(function ($presensi) {
                return [
                    ...$presensi->toArray(),
                    'nama_jam_kerja' => $presensi->nama_jam_kerja,
                    'jam_kerja_masuk' => $presensi->jam_kerja_masuk,
                    'jam_pulang' => $presensi->jam_pulang,
                    'keterangan' => optional($presensi->pengajuanIzin)->keterangan,
                    'waktu_mulai' => optional($presensi->lembur)->waktu_mulai,
                    'waktu_selesai' => optional($presensi->lembur)->waktu_selesai,
                ];
        });

        return view('presensi.gethistory', compact('history'));
    }

    // public function SakitIzin()
    // {
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $dataIzin = DB::table('pengajuan_sakit_izin')
    //         ->where('nik', $nik)
    //         ->get();
    //     return view('presensi.sakit_izin', compact('dataIzin'));
    // }
    // public function CreateSakitIzin()
    // {

    //     return view('presensi.create_sakit_izin');
    // }
    // public function StoreSakitIzin(Request $request)
    // {
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $tanggal_izin = $request->tanggal_izin;
    //     $status = $request->status;
    //     $keterangan = $request->keterangan;

    //     $data = [
    //         'nik' => $nik,
    //         'tanggal_izin' => $tanggal_izin,
    //         'status' => $status,
    //         'keterangan' => $keterangan,
    //         'created_at' => Carbon::now()
    //     ];

    //     $save = DB::table('pengajuan_sakit_izin')->insert($data);

    //     if($save){
    //         return redirect('/presensi/sakit-izin')->with(['success' => 'Data berhasil disimpan!']);
    //     } else {
    //         return redirect()->back()->with(['error' => 'Data gagal disimpan!']);
    //     }
    // }

    // Admin Monitoring Presensi
    public function MonitoringPresensi()
    {
        $user = auth()->user();

        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
            return view('presensi.monitoring_presensi', compact('cabang'));
        } else {
            $cabang = null;
            return view('presensi.monitoring_presensi', compact('cabang'));
        }
    }

    public function MonitoringGetPresensi(Request $request)
    {
        $tanggal_presensi = $request->tanggal_presensi;
        // Mendapatkan user yang sedang login
        $user = auth()->user();

        $presensi_query = DB::table('presensi')
            ->select('presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.kode_jabatan',
                'karyawan.kode_lokasi_penugasan',
                'karyawan.kode_cabang',
                'jabatan.nama_jabatan',
                'lokasi_penugasan.nama_lokasi_penugasan',
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai ELSE jam_kerja.jam_masuk END as jam_masuk_kerja'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai ELSE jam_kerja.jam_pulang END as jam_pulang_kerja'),
                DB::raw('CASE WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur" ELSE jam_kerja.nama_jam_kerja END as nama_jam_kerja'),
                'pengajuan_izin.keterangan as keterangan_izin',
                'lembur.waktu_mulai as mulai_lembur',
                'lembur.waktu_selesai as selesai_lembur')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->join('lokasi_penugasan', 'karyawan.kode_lokasi_penugasan', '=', 'lokasi_penugasan.kode_lokasi_penugasan')
            ->leftJoin('lembur', function($join) use ($tanggal_presensi) {
                $join->on('presensi.nik', '=', 'lembur.nik')
                    ->where('lembur.tanggal_presensi', '=', DB::raw('presensi.tanggal_presensi'))
                    ->where('lembur.status', '=', 'disetujui');
            })
            ->where('presensi.tanggal_presensi', $tanggal_presensi);

        // Pengecekan berdasarkan peran
        if ($user->role === 'admin-cabang') {
            // Jika admin cabang, filter hanya untuk cabang yang sesuai
            $presensi = $presensi_query
                ->where('karyawan.kode_cabang', $user->kode_cabang)
                ->get();

        } else {
            // Untuk super admin atau role lainnya, tampilkan semua data
            $presensi = $presensi_query->get();
        }

        return view('presensi.monitoring_getpresensi', compact('presensi'));
    }

    public function TampilkanPeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->where('id', $id)
        ->first();
        return view('presensi.monitoring_showmap', compact('presensi'));
    }

    public function LaporanPresensi()
    {
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


        $user = auth()->user();
        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first(); // Ubah ke first()
            $cabangs = Cabang::where('kode_cabang', $user->kode_cabang)->get(); // Tambahkan untuk list cabang
            $karyawan = DB::table('karyawan')
            ->where('kode_cabang', $user->kode_cabang)
            ->orderBy('nama_lengkap')
            ->get();
        } else {
            $cabang = null;
            $cabangs = Cabang::all();
            $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        }
        // dd($cabang);
        return view('presensi.laporan_presensi', compact('months', 'karyawan', 'cabang', 'cabangs'));
    }

    public function LaporanPrint(Request $request)
    {
        $nik = $request->nik;
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

        $karyawan = DB::table('karyawan')
                    ->join('kantor_cabang', 'karyawan.kode_cabang', '=', 'kantor_cabang.kode_cabang')
                    ->join('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                    ->where('nik', $nik)
                    ->first();

        $presensi = DB::table('presensi')
                    ->select('presensi.*',
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_mulai
                            ELSE jam_kerja.jam_masuk
                        END as jam_masuk_kerja'),
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN lembur.waktu_selesai
                            ELSE jam_kerja.jam_pulang
                        END as jam_pulang_kerja'),
                        DB::raw('CASE
                            WHEN presensi.kode_jam_kerja = "LEMBUR" THEN "Lembur"
                            ELSE jam_kerja.nama_jam_kerja
                        END as nama_jam_kerja'),
                        'pengajuan_izin.keterangan',
                        'jam_kerja.lintas_hari',
                        'lembur.waktu_mulai as mulai_lembur',
                        'lembur.waktu_selesai as selesai_lembur')
                    ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
                    ->leftJoin('lembur', function($join) {
                        $join->on('presensi.nik', '=', 'lembur.nik')
                            ->whereRaw('DATE(lembur.tanggal_presensi) = DATE(presensi.tanggal_presensi)')
                            ->where('lembur.status', '=', 'disetujui');
                    })
                    ->where('presensi.nik', $nik)
                    ->whereRaw('MONTH(presensi.tanggal_presensi)=?', [$bulan])
                    ->whereRaw('YEAR(presensi.tanggal_presensi)=?', [$tahun])
                    ->orderBy('presensi.tanggal_presensi')
                    ->get();

        // Hitung total hari presensi
        $total_hari = $presensi->groupBy('tanggal_presensi')->count();


        // Hitung total jam dan menit lembur
        $total_jam_lembur = 0;
        $total_menit_lembur = 0;

        foreach ($presensi as $p) {
            if ($p->mulai_lembur && $p->selesai_lembur) {
                $mulai = Carbon::parse($p->mulai_lembur);
                $selesai = Carbon::parse($p->selesai_lembur);

                // Hitung selisih dalam menit
                $selisih_menit = $selesai->diffInMinutes($mulai);

                // Konversi ke jam dan menit
                $total_jam_lembur += floor($selisih_menit / 60);
                $total_menit_lembur += $selisih_menit % 60;
            }
        }

        // Normalisasi jika menit lebih dari 60
        if ($total_menit_lembur >= 60) {
            $jam_tambahan = floor($total_menit_lembur / 60);
            $total_jam_lembur += $jam_tambahan;
            $total_menit_lembur = $total_menit_lembur % 60;
        }



        $imagePath = public_path('assets/img/MASTER-LOGO-PT-GUARD-500-500.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;

        $fotoProfilPath = public_path("storage/uploads/karyawan/" . $karyawan->foto);
        $fotoProfilData = base64_encode(file_get_contents(filename: $fotoProfilPath));
        $srcProfil = 'data:image/png;base64,' . $fotoProfilData;

        // Mengoper data ke pdf
        $pdf = Pdf::loadView('presensi.laporan_print', compact(
            'bulan',
            'tahun',
            'months',
            'karyawan',
            'presensi',
            'total_hari',
            'total_jam_lembur',
            'total_menit_lembur',
            'src',
            'srcProfil',
            ))->setPaper('A4', orientation: 'portrait') // Pastikan orientasi landscape
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // dd($pdf);

        return $pdf->download('laporan_presensi_' . $bulan . '_' . $tahun . '_' . $karyawan->nama_lengkap . '.pdf');

        // if(isset($_POST['export_excel'])) {
        //     $time = date("H:i:s");
        //     header("Content-type: application/vnd-ms-excel");
        //     header("Content-Disposition: attachment; filename=Laporan_Presensi_$time.xls");
        // }

        // return view('presensi.laporan_print', compact(
        //     'bulan',
        //     'tahun',
        //     'months',
        //     'karyawan',
        //     'presensi',
        //     'total_hari',
        //     'total_jam_lembur',
        //     'total_menit_lembur'
        // ));
    }

    public function RekapPresensi()
    {
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

        $user = auth()->user();
        if ($user->role === 'admin-cabang') {
            $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first(); // Ubah ke first()
            $kantor_cabang = Cabang::where('kode_cabang', $user->kode_cabang)->get(); // Tambahkan untuk list cabang
        } else {
            $cabang = null;
            $kantor_cabang = Cabang::all();
        }

        $kantor_cabang = DB::table('kantor_cabang')->get();
        // dd($departemen);

        return view('presensi.rekap_presensi', compact('months', 'kantor_cabang', 'cabang'));
    }

    public function RekapPrint(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_cabang = $request->kode_cabang;
        // dd($kode_cabang);
        $start = $tahun . "-" . $bulan . "-01";
        $dari = date("Y-m-d", strtotime($start));
        // dd($dari);
        $sampai = date("Y-m-t", strtotime($dari));

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

        $select_date = "";
        $field_date = "";
        $tgl = 1;
        while(strtotime($dari) <= strtotime($sampai)){
            $range_tanggal[] = $dari;

            $select_date .= "MAX(IF(tanggal_presensi = '$dari', CONCAT(
                                IFNULL(presensi.jam_masuk, 'NA'), '|',
                                IFNULL(presensi.jam_keluar, 'NA'), '|',
                                IFNULL(presensi.status, 'NA') , '|',
                                IFNULL(jam_kerja.nama_jam_kerja, 'NA') , '|',
                                IFNULL(jam_kerja.jam_masuk, 'NA') , '|',
                                IFNULL(jam_kerja.jam_pulang, 'NA') , '|',
                                IFNULL(presensi.kode_izin, 'NA') , '|',
                                IFNULL(pengajuan_izin.keterangan, 'NA') , '|'
                            ), NULL)) AS tgl_".$tgl . ",";
            $field_date .= "tgl_" . $tgl . ",";
            $tgl++;

            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }

        // dd($select_date);
        $jml_hari = count($range_tanggal);
        $last_range = $jml_hari - 1;
        $sampai = $range_tanggal[$last_range];

        if($jml_hari == 30){
            array_push($range_tanggal, NULL);
        } elseif ($jml_hari == 29) {
            array_push($range_tanggal, NULL, NULL);
        } elseif ($jml_hari == 28) {
            array_push($range_tanggal, NULL, NULL, NULL);
        }

        // dd($range_tanggal);

        $query = Karyawan::query();
        $query->selectRaw("
                            $field_date
                            karyawan.nik,
                            karyawan.nama_lengkap,
                            karyawan.kode_jabatan,
                            karyawan.kode_cabang
                            "
                        );
        $query->leftJoin(
            DB::raw("(
                    SELECT
                        $select_date
                        presensi.nik
                    FROM
                        presensi
                    LEFT JOIN
                        jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                    LEFT JOIN
                        pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                    WHERE
                        presensi.tanggal_presensi BETWEEN  '$range_tanggal[0]' AND '$sampai'
                    GROUP BY
                        presensi.nik
                    ) presensi"),
            function ($join) {
                    $join->on('karyawan.nik', '=', 'presensi.nik');
            }
        );
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();

        // dd($rekap);
        $imagePath = public_path('assets/img/MASTER-LOGO-PT-GUARD-500-500.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;

        // Mengoper data ke pdf
        $pdf = Pdf::loadView('presensi.rekap_print', compact('bulan', 'tahun', 'months', 'rekap', 'range_tanggal', 'jml_hari', 'src'))
                ->setPaper('Legal', orientation: 'landscape') // Pastikan orientasi landscape
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('rekap_presensi_' . $bulan . '_' . $tahun . '_' . $kode_cabang . '.pdf');
    }

}
