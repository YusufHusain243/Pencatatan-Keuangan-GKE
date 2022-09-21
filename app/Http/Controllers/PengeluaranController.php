<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use App\Models\Kode;
use App\Models\SubKode;
use App\Models\AkunBank;
use App\Models\DetailBank;
use App\Models\SubSubKode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PengeluaranController extends Controller
{
    public function index()
    {
        $kodes = Kode::where('jenis_kode', 'Pengeluaran')->get();
        $sub_kodes = Kode::where('jenis_kode', 'Pengeluaran')
            ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
            ->get();
        $sub_sub_kodes = Kode::where('jenis_kode', 'Pengeluaran')
            ->join('sub_kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
            ->join('sub_sub_kodes', 'sub_sub_kodes.id_sub_kode', '=', 'sub_kodes.id')
            ->get();
        $akun_bank = AkunBank::all();
        $danas = Dana::all();
        return view('pages/pengeluaran', [
            "title" => "pengeluaran",
            "akun_bank" => $akun_bank,
            "kodes" => $kodes,
            "sub_kodes" => $sub_kodes,
            "sub_sub_kodes" => $sub_sub_kodes,
            "danas" => $danas,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->jenis_transaksi == 'Transfer Bank') {
            $validated = $request->validate([
                'kode_anggaran' => 'required',
                'sub_kode_anggaran' => 'required',
                'sub_sub_kode_anggaran' => 'required',
                'tanggal' => 'required',
                'keterangan' => 'required',
                'nominal' => 'required',
                'jenis_transaksi' => 'required',
                'akun_bank' => 'required',
            ]);
        } else {
            $validated = $request->validate([
                'kode_anggaran' => 'required',
                'sub_kode_anggaran' => 'required',
                'sub_sub_kode_anggaran' => 'required',
                'tanggal' => 'required',
                'keterangan' => 'required',
                'nominal' => 'required',
                'jenis_transaksi' => 'required',
            ]);
        }

        if ($validated) {
            $request->nominal = (int) filter_var($request->nominal, FILTER_SANITIZE_NUMBER_INT);
            if ($request->jenis_transaksi == 'Transfer Bank') {
                $result = Dana::create([
                    'id_kode' => $request->kode_anggaran,
                    'id_sub_kode' => $request->sub_kode_anggaran,
                    'id_sub_sub_kode' => $request->sub_sub_kode_anggaran,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'nominal' => $request->nominal,
                    'transaksi' => $request->jenis_transaksi,
                ]);

                $result = DetailBank::create([
                    'id_dana' => $result->id,
                    'id_bank' => $request->akun_bank,
                ]);
            } else {
                $result = Dana::create([
                    'id_kode' => $request->kode_anggaran,
                    'id_sub_kode' => $request->sub_kode_anggaran,
                    'id_sub_sub_kode' => $request->sub_sub_kode_anggaran,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'nominal' => $request->nominal,
                    'transaksi' => $request->jenis_transaksi,
                ]);
            }
            if ($result) {
                return redirect('/pengeluaran')->with('DanaSuccess', 'Tambah Pengeluaran Berhasil');
            }
            return redirect('/pengeluaran')->with('DanaError', 'Tambah Pengeluaran Gagal');
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $kodes = Kode::where('jenis_kode', 'Pengeluaran')->get();
        $sub_kodes = SubKode::join('kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', 'Pengeluaran')
            ->get(['kodes.id AS idKodes', 'kodes.*', 'sub_kodes.*']);
        $sub_sub_kodes = SubSubKode::join('sub_kodes', 'sub_sub_kodes.id_sub_kode', '=', 'sub_kodes.id')
            ->join('kodes', 'sub_kodes.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', 'Pengeluaran')
            ->get();
        $akun_bank = AkunBank::all();
        $dana = Dana::findOrFail($id);
        return view('pages/edit_pengeluaran', [
            "title" => "pengeluaran",
            "akun_bank" => $akun_bank,
            "kodes" => $kodes,
            "sub_kodes" => $sub_kodes,
            "sub_sub_kodes" => $sub_sub_kodes,
            "dana" => $dana,
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        if ($request->jenis_transaksi === 'Tunai/Cash') {
            $validated = $request->validate([
                'kode_anggaran' => 'required',
                'sub_kode_anggaran' => 'required',
                'sub_sub_kode_anggaran' => 'required',
                'tanggal' => 'required',
                'keterangan' => 'required',
                'nominal' => 'required',
                'jenis_transaksi' => 'required',
            ]);
        } else {
            $validated = $request->validate([
                'kode_anggaran' => 'required',
                'sub_kode_anggaran' => 'required',
                'sub_sub_kode_anggaran' => 'required',
                'tanggal' => 'required',
                'keterangan' => 'required',
                'nominal' => 'required',
                'jenis_transaksi' => 'required',
                'akun_bank' => 'required',
            ]);
        }

        if ($validated) {
            $request->nominal = (int) filter_var($request->nominal, FILTER_SANITIZE_NUMBER_INT);
            $data_penerimaan = Dana::findOrFail($id);
            if ($data_penerimaan->transaksi == 'Transfer Bank') {
                if ($request->jenis_transaksi == 'Tunai/Cash') {
                    $detail_bank = DetailBank::where('id_dana', $id)->where('id_bank', $request->akun_bank)->first();
                    $result = DetailBank::findOrFail($detail_bank->id)->delete();
                }
            }
            if ($request->jenis_transaksi == 'Transfer Bank') {
                $result_dana = Dana::findOrFail($id)->update([
                    'id_kode' => $request->kode_anggaran,
                    'id_sub_kode' => $request->sub_kode_anggaran,
                    'id_sub_sub_kode' => $request->sub_sub_kode_anggaran,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'nominal' => $request->nominal,
                    'transaksi' => $request->jenis_transaksi,
                ]);

                if ($result_dana) {
                    $check_detail_bank = DetailBank::where('id_dana', $id)->where('id_bank', $request->akun_bank)->first();

                    if ($check_detail_bank) {
                        $result = DetailBank::findOrFail($check_detail_bank->id)->update([
                            'id_dana' => $id,
                            'id_bank' => $request->akun_bank,
                        ]);
                    } else {
                        $result = DetailBank::create([
                            'id_dana' => $id,
                            'id_bank' => $request->akun_bank,
                        ]);
                    }
                }
            } else {
                $result = Dana::findOrFail($id)->update([
                    'id_kode' => $request->kode_anggaran,
                    'id_sub_kode' => $request->sub_kode_anggaran,
                    'id_sub_sub_kode' => $request->sub_sub_kode_anggaran,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'nominal' => $request->nominal,
                    'transaksi' => $request->jenis_transaksi,
                ]);
            }
            if ($result) {
                return redirect('/pengeluaran')->with('DanaSuccess', 'Edit Pengeluaran Berhasil');
            }
            return redirect('/pengeluaran')->with('DanaError', 'Edit Pengeluaran Gagal');
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $data = Dana::findOrFail($id);
        if ($data) {
            $result = $data->danaToDetailBank()->delete();
            if ($result) {
                $result = $data->delete();
                if ($result) {
                    return redirect('/pengeluaran')->with('DanaSuccess', 'Hapus Pengeluaran Berhasil');
                }
                return redirect('/pengeluaran')->with('DanaError', 'Hapus Pengeluaran Gagal');
            }
        }
    }
}
