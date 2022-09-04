<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'no_sub_kode' => 'required',
            'nama_sub_kode' => 'required',
        ]);

        if ($validated) {
            $request->no_sub_kode = substr($request->no_sub_kode, -1);
            $cek = DB::table('sub_kodes')
                ->where('id_kode', '=', $request->no_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_sub_kode', '=',  $request->no_sub_kode)
                        ->orWhere('nama_sub_kode', '=', $request->nama_sub_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = SubKode::create([
                    'id_kode' => $request->no_kode,
                    'no_sub_kode' => $request->no_sub_kode,
                    'nama_sub_kode' => $request->nama_sub_kode,
                ]);
                if ($result) {
                    return redirect('/sub-kode')->with('SubKodeSuccess', 'Tambah Sub Kode Berhasil');
                }
                return redirect('/sub-kode')->with('SubKodeError', 'Tambah Sub Kode Gagal');
            } else {
                return redirect('/sub-kode')->with('SubKodeError', 'Tambah Sub Kode Gagal, Sub Kode Sudah Ada!');
            }
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
            'no_sub_kode' => 'required',
            'nama_sub_kode' => 'required',
        ]);

        if ($validated) {
            $request->no_sub_kode = substr($request->no_sub_kode, -1);
            $cek = DB::table('sub_kodes')
                ->where('id_kode', '=', $request->no_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_sub_kode', '=',  $request->no_sub_kode)
                        ->orWhere('nama_sub_kode', '=', $request->nama_sub_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = SubKode::findOrFail($id)->update([
                    'id_kode' => $request->no_kode,
                    'no_sub_kode' => $request->no_sub_kode,
                    'nama_sub_kode' => $request->nama_sub_kode,
                ]);
                if ($result) {
                    return redirect('/sub-kode')->with('SubKodeSuccess', 'Edit Sub Kode Berhasil');
                }
                return redirect('/sub-kode')->with('SubKodeError', 'Edit Sub Kode Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = SubKode::findOrFail($id)->update([
                        'id_kode' => $request->no_kode,
                        'no_sub_kode' => $request->no_sub_kode,
                        'nama_sub_kode' => $request->nama_sub_kode,
                    ]);
                    if ($result) {
                        return redirect('/sub-kode')->with('SubKodeSuccess', 'Edit Sub Kode Berhasil');
                    }
                    return redirect('/sub-kode')->with('SubKodeError', 'Edit Sub Kode Gagal');
                } else {
                    return redirect('/sub-kode')->with('SubKodeError', 'Tambah Sub Kode Gagal, Sub Kode Sudah Ada!');
                }
            }
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
