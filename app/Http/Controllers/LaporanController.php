<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use App\Models\Dana;
use App\Models\Kode;
use App\Models\SubKode;
use App\Models\SubSubKode;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $title = "laporan";

        return view('pages.laporan', compact('title'));
    }

    public function show(Request $request)
    {
        $title = "lihat_laporan";

        $explode = explode('-', $request->daterangepicker);
        $explode[0] = str_replace(' ', '', $explode[0]);
        $explode[1] = str_replace(' ', '', $explode[1]);
        $tanggalAwal = date('Y-m-d', strtotime($explode[0]));
        $tanggalAkhir = date('Y-m-d', strtotime($explode[1]));

        // $kodes = Kode::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();
        // $kodes = Kode::join('danas', 'danas.id_kode', '=', 'kodes.id')
        // ->whereBetween('danas.tanggal', [$tanggalAwal, $tanggalAkhir])
        // ->get();
        $kodes = Kode::with(['kodeToSubKode'])
            ->whereHas('kodeToSubKode', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
            })
            ->get();

        // dd($kodes[0]->KodeToSubKode[0]->subKodeToSubSubKode[0]->SubSubKodeToDana);


        // get saldo penerimaan
        $saldo_penerimaan = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('danas.tanggal', '<=', $tanggalAwal)
            ->sum('danas.nominal');

        // get saldo pengeluaran
        $saldo_pengeluaran = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Pengeluaran')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('danas.tanggal', '<=', $tanggalAwal)
            ->sum('danas.nominal');
        // get saldo kas
        $saldo_kas = ($saldo_penerimaan - $saldo_pengeluaran);

        // get saldo bank
        $saldo_bank = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->where('danas.tanggal', '<=', $tanggalAwal)
            ->sum('danas.nominal');

        // sum saldo akhir
        $saldo_akhir = ($saldo_kas + $saldo_bank);

        return view('pages.cetak_laporan', compact('title', 'kodes', 'tanggalAwal', 'tanggalAkhir', 'saldo_akhir'));
    }

    public function export()
    {
        // return Excel::download(new LaporanExport, 'laporan-' . date('d-m-Y') . '.xlsx');
    }
}
