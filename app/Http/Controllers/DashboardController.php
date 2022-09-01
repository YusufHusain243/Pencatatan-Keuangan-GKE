<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        //get saldo kas
        $saldo_kas = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->sum('danas.nominal');

        // get saldo bank
        $saldo_bank = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->sum('danas.nominal');

        // sum saldo akhir
        $saldo_akhir = $saldo_kas + $saldo_bank;

        // ----start get grafik bulanan----
        $num_bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $data_bulan_penerimaan = [];
        $data_bulan_pengeluaran = [];

        for ($i = 0; $i < count($num_bulan); $i++) {
            $data_bulan_penerimaan[] = Dana::whereRaw('MONTH(tanggal) = ' . $num_bulan[$i])
                ->whereRaw('YEAR(tanggal) = ' . Carbon::now()->format('Y'))
                ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
                ->where('kodes.jenis_kode', '=', 'Penerimaan')
                ->sum('danas.nominal');
            $data_bulan_pengeluaran[] = Dana::whereRaw('MONTH(tanggal) = ' . $num_bulan[$i])
                ->whereRaw('YEAR(tanggal) = ' . Carbon::now()->format('Y'))
                ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
                ->where('kodes.jenis_kode', '=', 'Pengeluaran')
                ->sum('danas.nominal');
        }
        // ----end get grafik bulanan----

        return view('pages/dashboard', [
            "title" => "dashboard",
            "saldo_kas" => $saldo_kas,
            "saldo_bank" => $saldo_bank,
            "saldo_akhir" => $saldo_akhir,
            "data_bulan_penerimaan" => json_encode($data_bulan_penerimaan, JSON_NUMERIC_CHECK),
            "data_bulan_pengeluaran" => json_encode($data_bulan_pengeluaran, JSON_NUMERIC_CHECK),
        ]);
    }
}
