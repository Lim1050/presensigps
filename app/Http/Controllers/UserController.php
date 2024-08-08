<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function UserIndex()
    {
        $user = DB::table('users')
                    ->select('users.id', 'users.name', 'users.email', 'users.foto', 'users.no_hp', 'departemen.nama_departemen', 'kantor_cabang.nama_cabang', 'roles.name as role_name')
                    ->join('departemen', 'users.kode_departemen', '=', 'departemen.kode_departemen')
                    ->join('kantor_cabang', 'users.kode_cabang', '=', 'kantor_cabang.kode_cabang')
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->get();
        return view('user.user_index', compact('user'));
    }
}
