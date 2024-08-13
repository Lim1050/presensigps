<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function UserIndex(Request $request)
    {
        $role = DB::table('roles')->orderBy('name')->get();
        // dd($role);
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $query = User::query();

        $query->select('users.id', 'users.username', 'users.name', 'users.email', 'users.foto', 'users.no_hp', 'users.role', 'departemen.kode_departemen', 'departemen.nama_departemen', 'kantor_cabang.kode_cabang', 'kantor_cabang.nama_cabang', 'roles.id as role_id', 'roles.name as role_name');
        $query->join('departemen', 'users.kode_departemen', '=', 'departemen.kode_departemen');
        $query->join('kantor_cabang', 'users.kode_cabang', '=', 'kantor_cabang.kode_cabang');
        $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id');
        $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if(!empty($request->cari_nama)){
            $query->where('users.name', 'like', '%' . $request->cari_nama . '%');
        }
        if(!empty($request->cari_role)){
            $query->where('users.role', 'like', '%' . $request->cari_role . '%');
        }
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

    // public function UserUpdate(Request $request, $id)
    // {
    //     $id = $request->id;
    //     $username = $request->username;
    //     $name = $request->name;
    //     $email = $request->email;
    //     $no_hp = $request->no_hp;
    //     $password = Hash::make($request->password);
    //     $role = $request->role;
    //     $kode_departemen = $request->kode_departemen;
    //     $kode_cabang = $request->kode_cabang;

    //     $data_foto = DB::table('users')->select('foto')->where('id', $id)->first();

    //     $old_foto = $data_foto->foto;

    //     // cek apakah ada foto dari form
    //     if($request->hasFile('foto')){
    //         $foto = $username . "_" . time() . "." . $request->file('foto')->getClientOriginalExtension();
    //     } else {
    //         // Jika tidak ada file foto yang diunggah, gunakan foto sebelumnya
    //         $foto = $old_foto;
    //     }



    //     if(isset($request->password)){
    //         $data = [
    //             'username' => $username,
    //             'name' => $name,
    //             'email' => $email,
    //             'no_hp' => $no_hp,
    //             'password' => $password,
    //             'role' => $role,
    //             'foto' => $foto,
    //             'kode_departemen' => $kode_departemen,
    //             'kode_cabang' => $kode_cabang,
    //         ];
    //     } else {
    //         $data = [
    //             'username' => $username,
    //             'name' => $name,
    //             'email' => $email,
    //             'role' => $role,
    //             'foto' => $foto,
    //             'no_hp' => $no_hp,
    //             'kode_departemen' => $kode_departemen,
    //             'kode_cabang' => $kode_cabang,
    //         ];
    //     }

    //     // dd($data);

    //     DB::beginTransaction();
    //     try {
    //         $user = DB::table('users')->where('id', $id)->update($data);
    //         // DB::table('model_has_roles')->where('model_id', $id)
    //         //     ->update([
    //         //         'role_id' => $role,
    //         //     ]);
    //         $user->syncRoles($role);
    //         DB::commit();
    //         if($request->hasFile('foto')){
    //                 $folderPath = "public/uploads/user/";
    //                 $request->file('foto')->storeAs($folderPath, $foto);

    //                 // Hapus foto lama jika berbeda dengan foto baru
    //                 if ($old_foto !== $foto) {
    //                     Storage::delete($folderPath . $old_foto);
    //                 }
    //             }
    //         return redirect()->route('admin.konfigurasi.user')->with(['success' => 'Data Berhasil Diupdate!']);
    //     } catch (\Throwable $e) {
    //         dd($e);
    //         return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
    //     }
    // }

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
}
