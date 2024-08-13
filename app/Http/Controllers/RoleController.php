<?php

namespace App\Http\Controllers;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    public function PermissionExport()
    {
        return Excel::download(new PermissionExport, 'permission.xlsx');
    }

    public function PermissionImport(Request $request)
    {
        try {
            Excel::import(new PermissionImport, $request->import_file);
            return redirect()->back()->with('success', 'Data Berhasil Diimport');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Diimport!']);
        }
    }

}
