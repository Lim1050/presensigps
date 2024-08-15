<?php

namespace App\Http\Controllers;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function Roleindex()
    {
        $role = Role::all();

        return view('user.role_index', compact('role'));
    }

    public function RoleStore(Request $request)
    {
        try {
            Role::create([
                'name' => $request->name,
                'created_at' => Carbon::now()
            ]);
        return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function RoleUpdate(Request $request, $id)
    {
        // $id = $request->id;
        try {
            Role::find($id)->update([
                'name' => $request->name,
                'updated_at' => Carbon::now()
            ]);
        return redirect()->back()->with('success', 'Data Berhasil Diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function RoleDelete($id)
    {
        try {
            Role::find($id)->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }
    }

    public function RolesInPermissionsIndex()
    {
        $roles = Role::all()->sortBy('name');
        $permissions = Permission::all();
        $permission_groups = User::GetPermissionGroup();
        return view('user.add_roles_in_permissions_index', compact('roles', 'permissions', 'permission_groups'));
    }

    public function RolesInPermissionsStore(Request $request)
    {
        $data = array();
        $permissions = $request->permission;

        try {
            foreach ($permissions as $key => $item) {
                $data['role_id'] = $request->role_id;
                $data['permission_id'] = $item;

                DB::table('role_has_permissions')->insert($data);
            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function RolesInPermissionsEdit($id)
    {
        $roles = Role::find($id);
        $permissions = Permission::all();
        // dd($permissions);
        $permission_groups = User::GetPermissionGroup();
        return view('user.add_roles_in_permissions_edit', compact('roles', 'permissions', 'permission_groups'));
    }

    public function RolesInPermissionsUpdate(Request $request, $id)
    {
        $roles = Role::find($id);
        $permissions = $request->permission;

        // dd($permissions);

        if(!empty($permissions)){
            $roles->syncPermissions($permissions);
            return redirect()->route('admin.konfigurasi.add-role-in-permission')->with('success', 'Data Berhasil Diperbarui');
        } elseif (empty($permissions)) {
            $roles->revokePermissionTo($permissions);
            return redirect()->route('admin.konfigurasi.add-role-in-permission')->with('success', 'Data Berhasil Diperbarui');
        } else {
            return redirect()->back()->with(['error' => 'Data Gagal Diperbarui!']);
        }
    }

    public function RolesInPermissionsDelete($id)
    {
        $roles = Role::find($id);
        if (!is_null($roles)){
            $roles->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
        } else {
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
