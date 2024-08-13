<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function Permissionindex()
    {
        $permissions = Permission::all();

        return view('user.permission_index', compact('permissions'));
    }

    public function PermissionStore(Request $request)
    {
        try {
            Permission::create([
                'name' => $request->name,
                'group_name' => $request->group_name,
                'created_at' => Carbon::now()
            ]);
        return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function PermissionUpdate(Request $request, $id)
    {
        // $id = $request->id;
        try {
            Permission::find($id)->update([
                'name' => $request->name,
                'group_name' => $request->group_name,
                'updated_at' => Carbon::now()
            ]);
        return redirect()->back()->with('success', 'Data Berhasil Diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function PermissionDelete($id)
    {
        try {
            Permission::find($id)->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

}
