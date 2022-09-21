<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($req->only(['username', 'password']))) {
            return redirect('/')->with('LoginSuccess', 'Login Berhasil, Selamat Datang Di SIA Gereja Sinar Kasih');
        }
        return redirect('/login')->with('LoginError', 'Login Gagal, Username/Password Gagal!');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
