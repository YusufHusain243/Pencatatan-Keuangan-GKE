<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubKode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubKodeController extends Controller
{
    public function index()
    {
        $sub_kodes = SubKode::all();
        $kodes = Kode::all();
        return view('pages/sub_kode', [
            "title" => "sub_kode",
            "sub_kodes" => $sub_kodes,
            "kodes" => $kodes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kode' => 'required',
            'no_sub_kode' => 'required|unique:sub_kodes,no_sub_kode',
            'nama_sub_kode' => 'required|unique:sub_kodes,nama_sub_kode',
        ]);

        if ($validated) {
            $result = SubKode::create([
                'id_kode' => $request->no_kode,
                'no_sub_kode' => $request->no_sub_kode,
                'nama_sub_kode' => $request->nama_sub_kode,
            ]);
            if ($result) {
                return redirect('/sub-kode')->with('SubKodeSuccess', 'Tambah Sub Kode Berhasil');
            }
            return redirect('/sub-kode')->with('SubKodeError', 'Tambah Sub Kode Gagal');
        }
    }
    
    public function edit($id)
    {
        $subKode = SubKode::findOrFail($id);
        $kodes = Kode::all();
        if ($subKode) {
            return view('pages/edit_sub_kode', [
                "title" => "sub_kode",
                "sub_kode" => $subKode,
                "kodes" => $kodes,
            ]);
        }
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_kode' => 'required',
            'no_sub_kode' => ['required', Rule::unique('sub_kodes')->ignore($id)],
            'nama_sub_kode' => ['required', Rule::unique('sub_kodes')->ignore($id)],
        ]);

        if ($validated) {
            $result = SubKode::findOrFail($id)->update([
                'id_kode' => $request->no_kode,
                'no_sub_kode' => $request->no_sub_kode,
                'nama_sub_kode' => $request->nama_sub_kode,
            ]);
            if ($result) {
                return redirect('/sub-kode')->with('SubKodeSuccess', 'Edit Sub Kode Berhasil');
            }
            return redirect('/sub-kode')->with('SubKodeError', 'Edit Sub Kode Gagal');
        }
    }
    
    public function destroy($id)
    {
        $data = SubKode::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/sub-kode')->with('SubKodeSuccess', 'Hapus Sub Kode Berhasil');
            }
            return redirect('/sub-kode')->with('SubKodeError', 'Hapus Sub Kode Gagal');
        }
    }
}
