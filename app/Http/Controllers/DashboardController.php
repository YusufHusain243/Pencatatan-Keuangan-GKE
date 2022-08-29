<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $saldo_kas = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->sum('danas.nominal');
        $saldo_bank = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->sum('danas.nominal');
        $saldo_akhir = $saldo_kas + $saldo_bank;
        return view('pages/dashboard', [
            "title" => "dashboard",
            "saldo_kas" => $saldo_kas,
            "saldo_bank" => $saldo_bank,
            "saldo_akhir" => $saldo_akhir,
        ]);
    }
}
