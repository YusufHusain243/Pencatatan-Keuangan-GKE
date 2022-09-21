<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubKode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SubKodeController extends Controller
{
    public function index($param)
    {
        $kodes = Kode::all();
        switch ($param) {
            case "all":
                $sub_kodes = Kode::join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->get();
                break;
            case "penerimaan":
                $sub_kodes = Kode::where('jenis_kode', 'Penerimaan')
                    ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->get();
                break;
            case "pengeluaran":
                $sub_kodes = Kode::where('jenis_kode', 'Pengeluaran')
                    ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->get();
                break;
        }
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
            $pisah = explode('.', $request->no_sub_kode);
            $request->no_sub_kode = $pisah[2];
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
                    return redirect('/sub-kode/all')->with('SubKodeSuccess', 'Tambah Sub Kode Berhasil');
                }
                return redirect('/sub-kode/all')->with('SubKodeError', 'Tambah Sub Kode Gagal');
            } else {
                return redirect('/sub-kode/all')->with('SubKodeError', 'Tambah Sub Kode Gagal, Sub Kode Sudah Ada!');
            }
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
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
        $id = Crypt::decrypt($id);
        $validated = $request->validate([
            'no_kode' => 'required',
            'no_sub_kode' => 'required',
            'nama_sub_kode' => 'required',
        ]);

        if ($validated) {
            $pisah = explode('.', $request->no_sub_kode);
            $request->no_sub_kode = $pisah[2];
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
                    return redirect('/sub-kode/all')->with('SubKodeSuccess', 'Edit Sub Kode Berhasil');
                }
                return redirect('/sub-kode/all')->with('SubKodeError', 'Edit Sub Kode Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = SubKode::findOrFail($id)->update([
                        'id_kode' => $request->no_kode,
                        'no_sub_kode' => $request->no_sub_kode,
                        'nama_sub_kode' => $request->nama_sub_kode,
                    ]);
                    if ($result) {
                        return redirect('/sub-kode/all')->with('SubKodeSuccess', 'Edit Sub Kode Berhasil');
                    }
                    return redirect('/sub-kode/all')->with('SubKodeError', 'Edit Sub Kode Gagal');
                } else {
                    return redirect('/sub-kode/all')->with('SubKodeError', 'Tambah Sub Kode Gagal, Sub Kode Sudah Ada!');
                }
            }
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $data = SubKode::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/sub-kode/all')->with('SubKodeSuccess', 'Hapus Sub Kode Berhasil');
            }
            return redirect('/sub-kode/all')->with('SubKodeError', 'Hapus Sub Kode Gagal');
        }
    }
}
