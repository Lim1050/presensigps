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
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function UserIndex(Request $request)
    {
        $role = DB::table('roles')->orderBy('name')->get();
        // $user = DB::table('users')->orderBy('username')->get();
        // dd($user);
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $query = User::query();

        $query->select('users.id', 'users.username', 'users.name', 'users.email', 'users.foto', 'users.no_hp', 'users.role', 'departemen.kode_departemen', 'departemen.nama_departemen', 'kantor_cabang.kode_cabang', 'kantor_cabang.nama_cabang');
        $query->join('departemen', 'users.kode_departemen', '=', 'departemen.kode_departemen');
        $query->join('kantor_cabang', 'users.kode_cabang', '=', 'kantor_cabang.kode_cabang');
        if(!empty($request->cari_nama)){
            $query->where('users.name', 'like', '%' . $request->cari_nama . '%');
        }
        if(!empty($request->cari_role)){
            $query->where('users.role', 'like', '%' . $request->cari_role . '%');
        }
        $query->orderBy('username');
        $user = $query->paginate(10);
        $user->appends(request()->all());
        // dd($user);
        return view('user.user_index', compact('user', 'departemen', 'cabang', 'role'));
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

    public function AdminProfileUpdateFoto(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'foto' => 'required|image|mimes:jpeg,png|max:5120', // Maksimal 5MB
            ]);

            // Ambil pengguna yang sedang login
            $user = Auth::user();

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::delete('public/uploads/user/' . $user->foto);
            }

            // Simpan foto baru
            $fileName = $user->username . '_' . time() .  '.' .  $request->foto->extension();
            $request->foto->storeAs('public/uploads/user', $fileName);

            // Update nama file di database
            $user->foto = $fileName;
            $user->save();

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Foto profil gagal diperbarui! ' . $e->getMessage());
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
