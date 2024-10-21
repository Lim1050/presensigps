<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function Profile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        // dd($karyawan);
        return view('profile.profile', compact('karyawan'));
    }

    // public function ProfileUpdate(Request $request)
    // {
    //     // get data dari form
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $nama_lengkap = $request->nama_lengkap;
    //     // $jabatan = $request->jabatan;
    //     $no_wa = $request->no_wa;
    //     $password = Hash::make($request->password);

    //     // get data karyawan dari table
    //     $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
    //     $request->validate([
    //         'foto' => 'image|mimes:jpeg,png,jpg|max:1000'
    //     ]);

    //     // cek apakah ada foto dari form
    //     if($request->hasFile('foto')){
    //         $foto = $nik . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
    //          // Hapus foto lama jika ada
    //         if (!empty($karyawan->foto)) {
    //         Storage::delete("public/uploads/karyawan/{$karyawan->foto}");
    //         }
    //     } else {
    //         // Jika tidak ada file foto yang diunggah, gunakan foto yang sudah ada
    //         $foto = $karyawan->foto;
    //     }

    //     // if($request->hasFile('foto')){
    //     //     $file = $request->file('foto');
    //     //     @unlink(public_path('storage/uploads/karyawan/' . $karyawan->foto));
    //     //     $filename = $nik . "." . $file->getClientOriginalExtension();
    //     //     $file->move(public_path('storage/uploads/karyawan/'), $filename);
    //     //     $foto = $filename;
    //     // } else {
    //     //     $foto = $karyawan->foto;
    //     // }

    //     // cek apakah mengubah password / tidak
    //     if(empty($request->password)){
    //         $data = [
    //         'nama_lengkap' => $nama_lengkap,
    //         // 'jabatan' => $jabatan,
    //         'no_wa' => $no_wa,
    //         'foto' => $foto,
    //         'updated_at' => Carbon::now()
    //         ];
    //     } else {
    //         $data = [
    //         'nama_lengkap' => $nama_lengkap,
    //         // 'jabatan' => $jabatan,
    //         'no_wa' => $no_wa,
    //         'foto' => $foto,
    //         'password' => $password,
    //         'updated_at' => Carbon::now()
    //         ];
    //     }

    //     // update data to table karyawan
    //     $update = DB::table('karyawan')->where('nik', $nik)->update($data);
    //     if($update) {
    //         // save foto ke storage
    //         if($request->hasFile('foto')){
    //             $folderPath = "public/uploads/karyawan/";
    //             $request->file('foto')->storeAs($folderPath, $foto);
    //         }
    //         return redirect()->back()->with(['success' => 'Data Berhasil Diupdate!']);
    //     } else {
    //         return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
    //     }

    // }

    public function ProfileUpdateFoto(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'croppedImage' => 'required',
            ]);

            // Ambil pengguna yang sedang login
            $nik = Auth::guard('karyawan')->user()->nik;
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            // Decode base64 image
            $croppedImage = $request->input('croppedImage');
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

            // Validasi ukuran file
            if (strlen($image) > 5 * 1024 * 1024) { // 5MB dalam bytes
                return redirect()->back()->with('error', 'Ukuran file tidak boleh lebih dari 5MB.');
            }

            // Generate nama file
            $fileName = $nik . '_' . time() . '.jpg';
            // dd($fileName);

            // Hapus foto lama jika ada
            if ($karyawan->foto) {
                Storage::delete('public/uploads/karyawan/' . $karyawan->foto);
            }

            // Simpan foto baru
            Storage::put('public/uploads/karyawan/' . $fileName, $image);

            // Update nama file di database
            $data = [
            'foto' => $fileName,
            ];
            DB::table('karyawan')->where('nik', $nik)->update($data);

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating profile photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Foto profil gagal diperbarui! Silakan coba lagi.');
        }
    }

    public function ProfileUpdateDetail(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_lengkap' => 'nullable|string|max:255',
                'no_wa' => 'nullable|string|max:20',
            ]);

            // Ambil NIK karyawan yang sedang login
            $nik = Auth::guard('karyawan')->user()->nik;

            // Ambil data karyawan dari database
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            // Inisialisasi array untuk menyimpan data yang akan diupdate
            $updateData = [];

            // Cek dan tambahkan nama_lengkap ke updateData jika ada perubahan
            if ($request->filled('nama_lengkap') && $request->nama_lengkap !== $karyawan->nama_lengkap) {
                $updateData['nama_lengkap'] = $request->nama_lengkap;
            }

            // Cek dan tambahkan no_wa ke updateData jika ada perubahan
            if ($request->filled('no_wa') && $request->no_wa !== $karyawan->no_wa) {
                $updateData['no_wa'] = $request->no_wa;
            }

            // Update data jika ada perubahan
            if (!empty($updateData)) {
                DB::table('karyawan')->where('nik', $nik)->update($updateData);
                $message = 'Profil berhasil diperbarui';
            } else {
                $message = 'Tidak ada perubahan pada profil';
            }

            // Redirect kembali ke halaman profil dengan pesan yang sesuai
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating profile details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Profil gagal diperbarui! Silakan coba lagi.');
        }
    }

    public function ProfileUpdatePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:8|different:old_password',
                'password_confirmation' => 'required|same:new_password',
            ]);

            $nik = Auth::guard('karyawan')->user()->nik;
            $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

            if (!Hash::check($request->old_password, $karyawan->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
            }

            DB::table('karyawan')->where('nik', $nik)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->back()->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Password gagal diperbarui! Silakan coba lagi.');
        }
    }
}
