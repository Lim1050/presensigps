<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function UserIndex(Request $request)
    {
        // Get roles, departments, and branches
        $roles = DB::table('roles')->orderBy('name')->get();
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();

        // Build the query
        $query = User::with(['departemen', 'cabang']) // Eager load relationships
            ->select('id', 'username', 'name', 'email', 'foto', 'no_hp', 'role', 'kode_departemen', 'kode_cabang');

        // Apply filters using when
        $query->when($request->cari_nama, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->cari_nama . '%');
        });

        $query->when($request->cari_role, function ($q) use ($request) {
            return $q->where('role', 'like', '%' . $request->cari_role . '%');
        });

        $query->when($request->cari_cabang, function ($q) use ($request) {
            return $q->where('kode_cabang', 'like', '%' . $request->cari_cabang . '%');
        });

        // Order by username
        $query->orderBy('username');

        // Paginate results
        $user = $query->get();
        // $user->appends($request->all()); // Maintain query parameters in pagination links

        // Return view with compacted data
        return view('user.user_index', compact('user', 'departemen', 'cabang', 'roles'));
    }

    public function UserStore(Request $request)
    {
        $username = $request->username;
        $name = $request->name;
        $email = $request->email;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $role = $request->role;
        $kode_departemen = $request->kode_departemen;
        $kode_cabang = $request->kode_cabang;

        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $username . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            // Jika tidak ada file foto yang diunggah, gunakan foto default
            $foto = null;
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'no_hp' => $no_hp,
                'password' => $password,
                'role' => $role,
                'kode_departemen' => $kode_departemen,
                'kode_cabang' => $kode_cabang,
                'foto' => $foto,
                'created_at' => Carbon::now()
            ]);
            $user->assignRole($role);

            if($user){
                // save foto ke storage
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/user/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            DB::commit();
            return redirect()->route('admin.konfigurasi.user')->with(['success' => 'Data Berhasil Disimpan!']);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            if($e->getCode()==23000){
                $message = " Data dengan username " . $username . " Sudah ada!";
            } else {
                $message = " Hubungi Tim IT";
            }
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!' . $message]);
        }
    }

    public function UserUpdate(Request $request, $id)
    {
        $username = $request->username;
        $name = $request->name;
        $email = $request->email;
        $no_hp = $request->no_hp;
        $password = $request->password ? Hash::make($request->password) : null;
        $role = $request->role;
        $kode_departemen = $request->kode_departemen;
        $kode_cabang = $request->kode_cabang;

        $user = User::findOrFail($id);

        $old_foto = $user->foto;

        // cek apakah ada foto dari form
        if($request->hasFile('foto')){
            $foto = $username . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        // Set data yang akan diupdate
        $user->username = $username;
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;
        $user->no_hp = $no_hp;
        $user->kode_departemen = $kode_departemen;
        $user->kode_cabang = $kode_cabang;
        $user->foto = $foto;

        if ($password) {
            $user->password = $password;
        }

        DB::beginTransaction();
        try {
            // Update data user
            $user->save();

            // Update role user
            $user->syncRoles($role);

            DB::commit();

            // Handle file upload
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/user/";
                $request->file('foto')->storeAs($folderPath, $foto);

                // Hapus foto lama jika berbeda dengan foto baru
                if ($old_foto !== $foto) {
                    Storage::delete($folderPath . $old_foto);
                }
            }

            return redirect()->route('admin.konfigurasi.user')->with(['success' => 'Data Berhasil Diupdate!']);
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }
    }


    public function UserDelete($id)
    {
        $id = Crypt::decrypt($id);
        $data_foto = DB::table('users')->select('foto')->where('id', $id)->first();
        $folderPath = "public/uploads/user/";
        // dd($data_foto->foto);
        try {
            DB::table('users')->where('id', $id)->delete();
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            Storage::delete($folderPath . $data_foto->foto);
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e){
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    public function UserResetPassword($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);
        $password = Hash::make('password123');
        $user->password = $password;
        try {
            $user->save();
            return redirect()->back()->with(['success' => 'Password Berhasil Direset!']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => 'Password Gagal Direset!']);
        }

    }

    public function AdminProfile()
    {
        $user = Auth::user();
        return view('profile.admin_profile', compact('user'));
    }

    // public function AdminProfileUpdateFoto(Request $request)
    // {
    //     try {
    //         // Validasi input
    //         $request->validate([
    //             'foto' => 'required|image|mimes:jpeg,png|max:5120', // Maksimal 5MB
    //         ]);

    //         // Ambil pengguna yang sedang login
    //         $user = Auth::user();

    //         // Hapus foto lama jika ada
    //         if ($user->foto) {
    //             Storage::delete('public/uploads/user/' . $user->foto);
    //         }

    //         // Simpan foto baru
    //         $fileName = $user->username . '_' . time() .  '.' .  $request->foto->extension();
    //         $request->foto->storeAs('public/uploads/user', $fileName);

    //         // Update nama file di database
    //         $user->foto = $fileName;
    //         $user->save();

    //         return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    //     } catch (\Exception $e) {
    //         // dd($e);
    //         return redirect()->back()->with('error', 'Foto profil gagal diperbarui! ' . $e->getMessage());
    //     }
    // }

    public function AdminProfileUpdateFoto(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'croppedImage' => 'required',
            ]);

            // Ambil pengguna yang sedang login
            $user = Auth::user();

            // Decode base64 image
            $croppedImage = $request->input('croppedImage');
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));

            // Validasi ukuran file
            if (strlen($image) > 5 * 1024 * 1024) { // 5MB dalam bytes
                return redirect()->back()->with('error', 'Ukuran file tidak boleh lebih dari 5MB.');
            }

            // Generate nama file
            $fileName = $user->username . '_' . time() . '.jpg';

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::delete('public/uploads/user/' . $user->foto);
            }

            // Simpan foto baru
            Storage::put('public/uploads/user/' . $fileName, $image);

            // Update nama file di database
            $user->foto = $fileName;
            $user->save();

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating profile photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Foto profil gagal diperbarui! Silakan coba lagi.');
        }
    }
    public function AdminProfileUpdateDetail(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'no_hp' => 'nullable|string|max:15',
            ]);

            // Ambil pengguna yang sedang login
            $user = Auth::user();

            // Update detail pengguna hanya jika field diisi
            if ($request->has('username')) {
                $user->username = $request->username;
            }
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('no_hp')) {
                $user->no_hp = $request->no_hp;
            }

            // Simpan perubahan ke database
            $user->save();

            return redirect()->back()->with('success', value: 'Detail akun berhasil diperbarui!');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Detail akun gagal diperbarui! ' . $e->getMessage());
        }
    }

    public function AdminProfileUpdatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // Password baru harus memiliki minimal 8 karakter
        ]);

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        try {
            // Cek apakah password saat ini cocok
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }

            // Update password baru
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.']);
        }
    }
}
