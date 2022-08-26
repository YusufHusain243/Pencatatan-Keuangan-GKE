<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubKode;
use Illuminate\Http\Request;

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_kode' => 'required',
            'no_sub_kode' => 'required',
            'nama_sub_kode' => 'required',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
