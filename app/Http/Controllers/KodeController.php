<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KodeController extends Controller
{
    public function index()
    {
        $kodes = Kode::all();
        return view('pages/kode', [
            "title" => "kode",
            "kodes" => $kodes
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kode' => 'required',
            'no_kode' => 'required',
            'nama_kode' => 'required',
        ]);

        if ($validated) {
            $cek = DB::table('kodes')
                ->where('jenis_kode', '=', $request->jenis_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_kode', '=',  $request->no_kode)
                        ->orWhere('nama_kode', '=', $request->nama_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = Kode::create([
                    'jenis_kode' => $request->jenis_kode,
                    'no_kode' => $request->no_kode,
                    'nama_kode' => $request->nama_kode,
                ]);
                if ($result) {
                    return redirect('/kode')->with('KodeSuccess', 'Tambah Kode Berhasil');
                }
                return redirect('/kode')->with('KodeError', 'Tambah Kode Gagal');
            } else {
                return redirect('/kode')->with('KodeError', 'Tambah Kode Gagal, Kode Sudah Ada!');
            }
        }
    }

    public function edit($id)
    {
        $kode = Kode::findOrFail($id);
        if ($kode) {
            return view('pages/edit_kode', [
                "title" => "kode",
                "kode" => $kode
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_kode' => 'required',
            'no_kode' =>  'required',
            'nama_kode' =>  'required',
        ]);

        if ($validated) {
            $cek = DB::table('kodes')
                ->where('jenis_kode', '=', $request->jenis_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_kode', '=',  $request->no_kode)
                        ->orWhere('nama_kode', '=', $request->nama_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = Kode::findOrFail($id)->update([
                    'jenis_kode' => $request->jenis_kode,
                    'no_kode' => $request->no_kode,
                    'nama_kode' => $request->nama_kode,
                ]);
                if ($result) {
                    return redirect('/kode')->with('KodeSuccess', 'Edit Kode Berhasil');
                }
                return redirect('/kode')->with('KodeError', 'Edit Kode Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = Kode::findOrFail($id)->update([
                        'jenis_kode' => $request->jenis_kode,
                        'no_kode' => $request->no_kode,
                        'nama_kode' => $request->nama_kode,
                    ]);
                    if ($result) {
                        return redirect('/kode')->with('KodeSuccess', 'Edit Kode Berhasil');
                    }
                    return redirect('/kode')->with('KodeError', 'Edit Kode Gagal');
                } else {
                    return redirect('/kode')->with('KodeError', 'Kode Sudah Ada');
                }
            }
        }
    }

    public function destroy($id)
    {
        $data = Kode::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/kode')->with('KodeSuccess', 'Hapus Kode Berhasil');
            }
            return redirect('/kode')->with('KodeError', 'Hapus Kode Gagal');
        }
    }
}
