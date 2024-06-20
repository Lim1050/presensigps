<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Login(Request $request)
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

    public function Logout()
    {
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }
}
