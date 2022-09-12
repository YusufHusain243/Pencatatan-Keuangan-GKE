<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubSubKode;
use Illuminate\Http\Request;
use App\Models\SubKode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubSubKodeController extends Controller
{
    public function index($param)
    {
        $sub_kodes = SubKode::all();
        switch ($param) {
            case "all":
                $sub_sub_kodes = Kode::join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->join('sub_sub_kodes', 'sub_sub_kodes.id_sub_kode', '=', 'sub_kodes.id')
                    ->get();
                break;
            case "penerimaan":
                $sub_sub_kodes = Kode::where('jenis_kode', 'Penerimaan')
                    ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->join('sub_sub_kodes', 'sub_sub_kodes.id_sub_kode', '=', 'sub_kodes.id')
                    ->get();
                break;
            case "pengeluaran":
                $sub_sub_kodes = Kode::where('jenis_kode', 'Pengeluaran')
                    ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
                    ->join('sub_sub_kodes', 'sub_sub_kodes.id_sub_kode', '=', 'sub_kodes.id')
                    ->get();
                break;
        }
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
            $request->no_sub_sub_kode = substr($request->no_sub_sub_kode, -1);
            $cek = DB::table('sub_sub_kodes')
                ->where('id_sub_kode', '=', $request->no_sub_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_sub_sub_kode', '=',  $request->no_sub_sub_kode)
                        ->orWhere('nama_sub_sub_kode', '=', $request->nama_sub_sub_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = SubSubKode::create([
                    'id_sub_kode' => $request->no_sub_kode,
                    'no_sub_sub_kode' => $request->no_sub_sub_kode,
                    'nama_sub_sub_kode' => $request->nama_sub_sub_kode,
                ]);
                if ($result) {
                    return redirect('/sub-sub-kode/all')->with('SubSubKodeSuccess', 'Tambah Sub Sub-Kode Berhasil');
                }
                return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Tambah Sub Sub-Kode Gagal');
            } else {
                return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Tambah Sub Sub-Kode Gagal, Sub Sub-Kode Sudah Ada');
            }
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
            $request->no_sub_sub_kode = substr($request->no_sub_sub_kode, -1);
            $cek = DB::table('sub_sub_kodes')
                ->where('id_sub_kode', '=', $request->no_sub_kode)
                ->where(function ($query) use ($request) {
                    $query->where('no_sub_sub_kode', '=',  $request->no_sub_sub_kode)
                        ->orWhere('nama_sub_sub_kode', '=', $request->nama_sub_sub_kode);
                })
                ->get();

            if (count($cek) <= 0) {
                $result = SubSubKode::findOrFail($id)->update([
                    'id_sub_kode' => $request->no_sub_kode,
                    'no_sub_sub_kode' => $request->no_sub_sub_kode,
                    'nama_sub_sub_kode' => $request->nama_sub_sub_kode,
                ]);
                if ($result) {
                    return redirect('/sub-sub-kode/all')->with('SubSubKodeSuccess', 'Edit Sub Sub-Kode Berhasil');
                }
                return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Edit Sub Sub-Kode Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = SubSubKode::findOrFail($id)->update([
                        'id_sub_kode' => $request->no_sub_kode,
                        'no_sub_sub_kode' => $request->no_sub_sub_kode,
                        'nama_sub_sub_kode' => $request->nama_sub_sub_kode,
                    ]);
                    if ($result) {
                        return redirect('/sub-sub-kode/all')->with('SubSubKodeSuccess', 'Edit Sub Sub-Kode Berhasil');
                    }
                    return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Edit Sub Sub-Kode Gagal');
                } else {
                    return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Tambah Sub Sub-Kode Gagal, Sub Sub-Kode Sudah Ada');
                }
            }
        }
    }

    public function destroy($id)
    {
        $data = SubSubKode::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/sub-sub-kode/all')->with('SubSubKodeSuccess', 'Hapus Sub Sub-Kode Berhasil');
            }
            return redirect('/sub-sub-kode/all')->with('SubSubKodeError', 'Hapus Sub Sub-Kode Gagal');
        }
    }
}
