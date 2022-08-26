<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages/user', [
            "title" => "user",
            "users" => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:8',
            'password' => 'required|min:8',
        ]);

        if ($validated) {
            $result = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'bendahara',
            ]);
            if ($result) {
                return redirect('/user')->with('UserSuccess', 'Tambah User Berhasil');
            }
            return redirect('/user')->with('UserError', 'Tambah User Gagal');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            return view('pages/edit_user', [
                "title" => "user",
                "user" => $user
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => "required|min:8",
            'new_password' => 'required|min:8',
        ]);

        if ($validated) {
            $result = User::findOrFail($id)->update([
                'username' => $request->username,
                'password' => Hash::make($request->new_password),
            ]);
            if ($result) {
                return redirect('/user')->with('UserSuccess', 'Edit User Berhasil');
            }
            return redirect('/user')->with('UserError', 'Edit User Gagal');
        }
    }

    public function destroy($id)
    {
        $data = User::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/user')->with('UserSuccess', 'Hapus User Berhasil');
            }
            return redirect('/user')->with('UserError', 'Hapus User Gagal');
        }
    }
}
