<?php

namespace App\Http\Controllers;

use App\Models\SubSubKode;
use Illuminate\Http\Request;
use App\Models\SubKode;

class SubSubKodeController extends Controller
{
    public function index()
    {
        $sub_sub_kodes = SubSubKode::all();
        $sub_kodes = SubKode::all();
        return view('pages/sub_sub_kode', [
            "title" => "sub_sub_kode",
            "sub_sub_kodes" => $sub_sub_kodes,
            "sub_kodes" => $sub_kodes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_sub_kode' => 'required',
            'no_sub_sub_kode' => 'required',
            'nama_sub_sub_kode' => 'nullable',
        ]);

        if ($validated) {
            $result = SubSubKode::create([
                'id_sub_kode' => $request->no_sub_kode,
                'no_sub_sub_kode' => $request->no_sub_sub_kode,
                'nama_sub_sub_kode' => $request->nama_sub_sub_kode,
            ]);
            if ($result) {
                return redirect('/sub-sub-kode')->with('SubSubKodeSuccess', 'Tambah Sub Sub-Kode Berhasil');
            }
            return redirect('/sub-sub-kode')->with('SubSubKodeError', 'Tambah Sub Sub-Kode Gagal');
        }
    }

    public function edit($id)
    {
        $subSubKode = SubSubKode::findOrFail($id);
        $subKodes = SubKode::all();
        return view('pages/edit_sub_sub_kode', [
            "title" => "sub_sub_kode",
            "sub_sub_kode" => $subSubKode,
            "sub_kodes" => $subKodes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_sub_kode' => 'required',
            'no_sub_sub_kode' => 'required',
            'nama_sub_sub_kode' => 'nullable',
        ]);

        if ($validated) {
            $result = SubSubKode::findOrFail($id)->update([
                'id_sub_kode' => $request->no_sub_kode,
                'no_sub_sub_kode' => $request->no_sub_sub_kode,
                'nama_sub_sub_kode' => $request->nama_sub_sub_kode,
            ]);
            if ($result) {
                return redirect('/sub-sub-kode')->with('SubSubKodeSuccess', 'Edit Sub Sub-Kode Berhasil');
            }
            return redirect('/sub-sub-kode')->with('SubSubKodeError', 'Edit Sub Sub-Kode Gagal');
        }
    }

    public function destroy($id)
    {
        $data = SubSubKode::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/sub-sub-kode')->with('SubSubKodeSuccess', 'Hapus Sub Sub-Kode Berhasil');
            }
            return redirect('/sub-sub-kode')->with('SubSubKodeError', 'Hapus Sub Sub-Kode Gagal');
        }
    }
}
