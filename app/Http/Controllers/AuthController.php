<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function LoginProses(Request $request)
    {
        if (Auth::guard('karyawan')->attempt(['nik' => $request->nik, 'password' => $request->password])){
            // Notification Function
            $notification = array(
                'message' => 'Berhasil Login!',
                'alert-type' => 'success'
            );
            return redirect('dashboard')->with($notification);
        } else {
            // Notification Function
            $notification = array(
                'message' => 'NIK / Password Salah!',
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function AdminProsesLogin(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])){
            // Notification Function
            $notification = array(
                'message' => 'Berhasil Login!',
                'alert-type' => 'success'
            );
            return redirect('/admin/dashboard')->with($notification);
        } else {
            // Notification Function
            $notification = array(
                'message' => 'Email / Password Salah!',
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function AdminLogout()
    {
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/admin/login');
        }
    }

    public function Logout()
    {
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }

    }
}
