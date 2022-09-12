<?php

namespace App\Http\Controllers;

use App\Models\AkunBank;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunBankController extends Controller
{
    public function index()
    {
        $akun_banks = AkunBank::all();
        return view('pages/akun_bank', [
            "title" => "akun_bank",
            "akun_banks" => $akun_banks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nama_bank' => 'required',
                'no_rekening' => 'required',
            ],
            [
                'nama_bank.required' => 'Nama Bank Tidak Boleh Kosong',
                'no_rekening.required' => 'Nomor Rekening Tidak Boleh Kosong',
            ]
        );

        if ($validated) {
            $cek = AkunBank::where('no_rekening', $request->no_rekening)->get();
            if (count($cek) <= 0) {
                $result = AkunBank::create([
                    'nama_bank' => $request->nama_bank,
                    'no_rekening' => $request->no_rekening,
                ]);
                if ($result) {
                    return redirect('/akun-bank')->with('AkunBankSuccess', 'Tambah Akun Bank Berhasil');
                }
                return redirect('/akun-bank')->with('AkunBankError', 'Tambah Akun Bank Gagal');
            } else {
                return redirect('/akun-bank')->with('AkunBankError', 'Tambah Akun Bank Gagal, No Rekening Sudah Ada');
            }
        }
    }

    public function edit($id)
    {
        $akun_bank = AkunBank::findOrFail($id);
        return view('pages/edit_akun_bank', [
            "title" => "akun_bank",
            "akun_bank" => $akun_bank
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'nama_bank' => 'required',
                'no_rekening' => 'required',
            ],
            [
                'nama_bank.required' => 'Nama Bank Tidak Boleh Kosong',
                'no_rekening.required' => 'Nomor Rekening Tidak Boleh Kosong',
            ]
        );

        if ($validated) {
            $cek = AkunBank::where('no_rekening', $request->no_rekening)->get();
            if (count($cek) <= 0) {
                $result = AkunBank::findOrFail($id)->update([
                    'nama_bank' => $request->nama_bank,
                    'no_rekening' => $request->no_rekening,
                ]);
                if ($result) {
                    return redirect('/akun-bank')->with('AkunBankSuccess', 'Edit Akun Bank Berhasil');
                }
                return redirect('/akun-bank')->with('AkunBankError', 'Edit Akun Bank Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = AkunBank::findOrFail($id)->update([
                        'nama_bank' => $request->nama_bank,
                        'no_rekening' => $request->no_rekening,
                    ]);
                    if ($result) {
                        return redirect('/akun-bank')->with('AkunBankSuccess', 'Edit Akun Bank Berhasil');
                    }
                    return redirect('/akun-bank')->with('AkunBankError', 'Edit Akun Bank Gagal');
                } else {
                    return redirect('/akun-bank')->with('AkunBankError', 'Edit Akun Bank Gagal, No Rekening Sudah Ada');
                }
            }
        }
    }

    public function destroy($id)
    {
        $data = AkunBank::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/akun-bank')->with('AkunBankSuccess', 'Hapus Akun Bank Berhasil');
            }
            return redirect('/akun-bank')->with('AkunBankError', 'Hapus Akun Bank Gagal');
        }
    }
}
