<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use App\Models\Kode;
use App\Models\SubKode;
use App\Models\DetailBank;
use App\Models\SubSubKode;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use App\Models\AkunBank;
use Illuminate\Support\Facades\DB;
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

        $kodes = Kode::with(['kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana' => function ($q) use ($tanggalAwal, $tanggalAkhir) {
            $q->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }])
            ->get();

        // get saldo akhir
        $saldo_akhir = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->where('danas.tanggal', '<=', $tanggalAwal)
            ->sum('danas.nominal');

        $saldo_penerimaan_tunai = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->sum('danas.nominal');

        $saldo_pengeluaran_tunai = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('kodes.jenis_kode', '=', 'Pengeluaran')
            ->sum('danas.nominal');

        $saldo_tunai = ($saldo_penerimaan_tunai - $saldo_pengeluaran_tunai);

        $saldo_penerimaan_bank = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get();

        $saldo_penerimaan_banks = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get()
            ->toArray();

        $saldo_pengeluaran_banks = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->where('kodes.jenis_kode', '=', 'Pengeluaran')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get()
            ->toArray();

        $saldo_banks = [];
        $count1 = count($saldo_penerimaan_banks);
        $count2 = count($saldo_pengeluaran_banks);

        if ($count1 > $count2) {
            foreach ($saldo_penerimaan_banks as $i => $saldo_penerimaan_bank) {
                foreach ($saldo_pengeluaran_banks as $j => $saldo_pengeluaran_bank) {
                    if ($saldo_penerimaan_bank['nama_bank'] == $saldo_pengeluaran_bank['nama_bank']) {
                        $saldo_banks[$i]['nama_bank'] = $saldo_penerimaan_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = ($saldo_penerimaan_bank['nominalDana'] - $saldo_pengeluaran_bank['nominalDana']);
                    } else {
                        $saldo_banks[$i]['nama_bank'] = $saldo_penerimaan_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = $saldo_penerimaan_bank['nominalDana'];
                    }
                }
            }
        }

        if ($count2 > $count1) {
            foreach ($saldo_pengeluaran_banks as $i => $saldo_pengeluaran_bank) {
                foreach ($saldo_penerimaan_banks as $j => $saldo_penerimaan_bank) {
                    if ($saldo_penerimaan_bank['nama_bank'] == $saldo_pengeluaran_bank['nama_bank']) {
                        $saldo_banks[$i]['nama_bank'] = $saldo_pengeluaran_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = ($saldo_penerimaan_bank['nominalDana'] - $saldo_pengeluaran_bank['nominalDana']);
                    } else {
                        $saldo_banks[$i]['nama_bank'] = $saldo_pengeluaran_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = (0 - $saldo_pengeluaran_bank['nominalDana']);
                    }
                }
            }
        }

        $saldo_banks = collect($saldo_banks);

        return view('pages.cetak_laporan', compact('title', 'kodes', 'tanggalAwal', 'tanggalAkhir', 'saldo_akhir', 'saldo_tunai', 'saldo_banks'));
    }

    public function export()
    {
        // return Excel::download(new LaporanExport, 'laporan-' . date('d-m-Y') . '.xlsx');
    }
}
