<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KodeController extends Controller
{
    public function index($param)
    {
        switch ($param) {
            case "all":
                $kodes = Kode::all();
                break;
            case "penerimaan":
                $kodes = Kode::where('jenis_kode', 'Penerimaan')->get();
                break;
            case "pengeluaran":
                $kodes = Kode::where('jenis_kode', 'Pengeluaran')->get();
                break;
        }
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
            $pisah = explode('.', $request->no_kode);
            $request->no_kode = $pisah[1];
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
                    return redirect('/kode/all')->with('KodeSuccess', 'Tambah Kode Berhasil');
                }
                return redirect('/kode/all')->with('KodeError', 'Tambah Kode Gagal');
            } else {
                return redirect('/kode/all')->with('KodeError', 'Tambah Kode Gagal, Kode Sudah Ada!');
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
            $pisah = explode('.', $request->no_kode);
            $request->no_kode = $pisah[1];
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
                    return redirect('/kode/all')->with('KodeSuccess', 'Edit Kode Berhasil');
                }
                return redirect('/kode/all')->with('KodeError', 'Edit Kode Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = Kode::findOrFail($id)->update([
                        'jenis_kode' => $request->jenis_kode,
                        'no_kode' => $request->no_kode,
                        'nama_kode' => $request->nama_kode,
                    ]);
                    if ($result) {
                        return redirect('/kode/all')->with('KodeSuccess', 'Edit Kode Berhasil');
                    }
                    return redirect('/kode/all')->with('KodeError', 'Edit Kode Gagal');
                } else {
                    return redirect('/kode/all')->with('KodeError', 'Kode Sudah Ada');
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
                return redirect('/kode/all')->with('KodeSuccess', 'Hapus Kode Berhasil');
            }
            return redirect('/kode/all')->with('KodeError', 'Hapus Kode Gagal');
        }
    }
}
