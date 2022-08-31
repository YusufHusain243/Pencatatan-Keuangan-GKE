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
        $validated = $request->validate([
            'nama_bank' => 'required',
            'no_rek' => 'required|unique:akun_banks,no_rekening',
        ]);

        if ($validated) {
            $result = AkunBank::create([
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rek,
            ]);
            if ($result) {
                return redirect('/akun-bank')->with('AkunBankSuccess', 'Tambah Akun Bank Berhasil');
            }
            return redirect('/akun-bank')->with('AkunBankError', 'Tambah Akun Bank Gagal');
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
        $validated = $request->validate([
            'nama_bank' => 'required',
            'no_rek' => ['required', Rule::unique('akun_banks')->ignore($id)],
        ]);

        if ($validated) {
            $result = AkunBank::findOrFail($id)->update([
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rek,
            ]);
            if ($result) {
                return redirect('/akun-bank')->with('AkunBankSuccess', 'Edit Akun Bank Berhasil');
            }
            return redirect('/akun-bank')->with('AkunBankError', 'Edit Akun Bank Gagal');
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
