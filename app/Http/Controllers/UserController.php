<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserIndex()
    {
        $role = DB::table('roles')->orderBy('name')->get();
        $departemen = DB::table('departemen')->orderBy('nama_departemen')->get();
        $cabang = DB::table('kantor_cabang')->orderBy('nama_cabang')->get();
        $user = DB::table('users')
                    ->select('users.id', 'users.username', 'users.name', 'users.email', 'users.foto', 'users.no_hp', 'departemen.kode_departemen', 'departemen.nama_departemen', 'kantor_cabang.kode_cabang', 'kantor_cabang.nama_cabang', 'roles.name as role_name')
                    ->join('departemen', 'users.kode_departemen', '=', 'departemen.kode_departemen')
                    ->join('kantor_cabang', 'users.kode_cabang', '=', 'kantor_cabang.kode_cabang')
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->get();
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
}