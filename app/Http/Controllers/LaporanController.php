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

        $danas_penerimaan = Dana::query()
            ->with('danaToKode')
            ->where('tanggal', '>=', $tanggalAwal)
            ->where('tanggal', '<=', $tanggalAkhir)
            ->whereHas('danaToKode', function ($q) {
                $q->where('jenis_kode', 'Penerimaan');
            })
            ->orderBy('id_kode')
            ->get();

        $danas_pengeluaran = Dana::query()
            ->with('danaToKode')
            ->where('tanggal', '>=', $tanggalAwal)
            ->where('tanggal', '<=', $tanggalAkhir)
            ->whereHas('danaToKode', function ($q) {
                $q->where('jenis_kode', 'Pengeluaran');
            })
            ->orderBy('id_kode')
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

        if ($count1 >= $count2) {
            if ($count1 > 0) {
                foreach ($saldo_penerimaan_banks as $i => $saldo_penerimaan_bank) {
                    if ($count2 > 0) {
                        foreach ($saldo_pengeluaran_banks as $j => $saldo_pengeluaran_bank) {
                            if ($saldo_penerimaan_bank['nama_bank'] == $saldo_pengeluaran_bank['nama_bank']) {
                                $saldo_banks[$i]['nama_bank'] = $saldo_penerimaan_bank['nama_bank'];
                                $saldo_banks[$i]['nominalDana'] = ($saldo_penerimaan_bank['nominalDana'] - $saldo_pengeluaran_bank['nominalDana']);
                            } else {
                                $saldo_banks[$i]['nama_bank'] = $saldo_penerimaan_bank['nama_bank'];
                                $saldo_banks[$i]['nominalDana'] = $saldo_penerimaan_bank['nominalDana'];
                            }
                        }
                    } else {
                        $saldo_banks[$i]['nama_bank'] = $saldo_penerimaan_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = $saldo_penerimaan_bank['nominalDana'];
                    }
                }
            }
        }

        if ($count2 >= $count1) {
            if ($count2 > 0) {
                foreach ($saldo_pengeluaran_banks as $i => $saldo_pengeluaran_bank) {
                    if ($count1 > 0) {
                        foreach ($saldo_penerimaan_banks as $j => $saldo_penerimaan_bank) {
                            if ($saldo_penerimaan_bank['nama_bank'] == $saldo_pengeluaran_bank['nama_bank']) {
                                $saldo_banks[$i]['nama_bank'] = $saldo_pengeluaran_bank['nama_bank'];
                                $saldo_banks[$i]['nominalDana'] = ($saldo_penerimaan_bank['nominalDana'] - $saldo_pengeluaran_bank['nominalDana']);
                            } else {
                                $saldo_banks[$i]['nama_bank'] = $saldo_pengeluaran_bank['nama_bank'];
                                $saldo_banks[$i]['nominalDana'] = (0 - $saldo_pengeluaran_bank['nominalDana']);
                            }
                        }
                    } else {
                        $saldo_banks[$i]['nama_bank'] = $saldo_pengeluaran_bank['nama_bank'];
                        $saldo_banks[$i]['nominalDana'] = 0;
                    }
                }
            }
        }

        $kodes_penerimaan = Kode::query()
            ->where('jenis_kode', '=', 'Penerimaan')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana')
            ->with('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->get();

        $kode_penerimaans = [];
        $listAllowIdSubKodePenerimaan = [];
        foreach ($kodes_penerimaan as $keyKode => $kode_penerimaan) {
            $kode_penerimaans['kode'][$keyKode]['no_kode'] = $kode_penerimaan->no_kode;
            $kode_penerimaans['kode'][$keyKode]['nama_kode'] = $kode_penerimaan->nama_kode;
            foreach ($kode_penerimaan->kodeToSubKode as $keySubKode => $sub_kode) {
                $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['id'] = $sub_kode->id;
                $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['no_sub_kode'] = $sub_kode->no_sub_kode;
                $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['nama_sub_kode'] = $sub_kode->nama_sub_kode;
                foreach ($sub_kode->subKodeToSubSubKode as $keySubSubKode => $sub_sub_kode) {
                    $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['no_sub_sub_kode'] = $sub_sub_kode->no_sub_sub_kode;
                    $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['nama_sub_sub_kode'] = $sub_sub_kode->nama_sub_sub_kode;
                    foreach ($sub_sub_kode->subSubKodeToDana as $keyDana => $dana) {
                        $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['tanggal'] = $dana->tanggal;
                        $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['keterangan'] = $dana->keterangan;
                        $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['transaksi'] = $dana->transaksi;
                        $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['nominal'] = $dana->nominal;
                        $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['id_sub_kode'] = $dana->id_sub_kode;
                        $listAllowIdSubKodePenerimaan[] = $kode_penerimaans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['id_sub_kode'];
                    }
                }
            }
        }

        $kodes_pengeluaran = Kode::query()
            ->where('jenis_kode', '=', 'Pengeluaran')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana')
            ->with('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->get();

        $kode_pengeluarans = [];
        $listAllowIdSubKodePengeluaran = [];
        foreach ($kodes_pengeluaran as $keyKode => $kode_pengeluaran) {
            $kode_pengeluarans['kode'][$keyKode]['no_kode'] = $kode_pengeluaran->no_kode;
            $kode_pengeluarans['kode'][$keyKode]['nama_kode'] = $kode_pengeluaran->nama_kode;

            foreach ($kode_pengeluaran->kodeToSubKode as $keySubKode => $sub_kode) {
                $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['id'] = $sub_kode->id;
                $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['no_sub_kode'] = $sub_kode->no_sub_kode;
                $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['nama_sub_kode'] = $sub_kode->nama_sub_kode;
                foreach ($sub_kode->subKodeToSubSubKode as $keySubSubKode => $sub_sub_kode) {
                    $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['no_sub_sub_kode'] = $sub_sub_kode->no_sub_sub_kode;
                    $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['nama_sub_sub_kode'] = $sub_sub_kode->nama_sub_sub_kode;
                    foreach ($sub_sub_kode->subSubKodeToDana as $keyDana => $dana) {
                        $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['tanggal'] = $dana->tanggal;
                        $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['keterangan'] = $dana->keterangan;
                        $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['transaksi'] = $dana->transaksi;
                        $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['nominal'] = $dana->nominal;
                        $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['id_sub_kode'] = $dana->id_sub_kode;
                        $listAllowIdSubKodePengeluaran[] = $kode_pengeluarans['kode'][$keyKode]['sub_kode'][$keySubKode]['sub_sub_kode'][$keySubSubKode]['dana'][$keyDana]['id_sub_kode'];
                    }
                }
            }
        }

        return view('pages.cetak_laporan', compact('title', 'kode_penerimaans', 'listAllowIdSubKodePenerimaan', 'listAllowIdSubKodePengeluaran', 'kode_pengeluarans', 'tanggalAwal', 'tanggalAkhir', 'saldo_akhir', 'saldo_tunai', 'saldo_banks'));
    }

    public function export(Request $request)
    {
        $tanggalAwal = $request->tanggalAwal;
        $tanggalAkhir = $request->tanggalAkhir;
        return Excel::download(new LaporanExport($tanggalAwal, $tanggalAkhir), 'laporan-' . date('d-m-Y') . '.xlsx');
    }
}
