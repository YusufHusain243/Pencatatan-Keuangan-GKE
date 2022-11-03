<?php

namespace App\Exports;

use App\Models\Dana;
use App\Models\Kode;
use App\Models\DetailBank;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LaporanExport implements FromView, WithStyles, WithEvents, WithColumnFormatting, ShouldAutoSize
{
    protected $tanggalAwal, $tanggalAkhir;

    function __construct($tanggalAwal, $tanggalAkhir)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:D'    => [
                'font' =>
                [
                    'name' => 'Times New Roman',
                    'size'      =>  12
                ]
            ],
            'A1'    => [
                'font' =>
                [
                    'name' => 'Times New Roman',
                    'size'      =>  14
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A4:D4')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function view(): View
    {
        $tanggalAwal = $this->tanggalAwal;
        $tanggalAkhir = $this->tanggalAkhir;
        $jumlahPenerimaan = 0;
        $jumlahPengeluaran = 0;
        $table = null;
        $table2 = null;
        $table3 = null;

        function getIndonesianDate($dates)
        {
            return \Carbon\Carbon::createFromDate($dates)
                ->locale('id')
                ->settings(['formatFunction' => 'translatedFormat'])
                ->format('d F Y');
        }

        $id_kode_penerimaan_has_dana = Dana::query()
            ->with('danaToKode')
            ->whereHas('danaToKode', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('jenis_kode', 'Penerimaan')
                    ->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->pluck('id_kode');
        $kodes_penerimaan = Kode::query()
            ->where('jenis_kode', '=', 'Penerimaan')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana')
            ->with('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->whereIn('id', $id_kode_penerimaan_has_dana)
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

        $id_kode_pengeluaran_has_dana = Dana::query()
            ->with('danaToKode')
            ->whereHas('danaToKode', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('jenis_kode', 'Pengeluaran')
                    ->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->pluck('id_kode');
        $kodes_pengeluaran = Kode::query()
            ->where('jenis_kode', '=', 'Pengeluaran')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode')
            ->whereHas('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana')
            ->with('kodeToSubKode.subKodeToSubSubKode.subSubKodeToDana', function ($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->where('tanggal', '>=', $tanggalAwal)
                    ->where('tanggal', '<=', $tanggalAkhir);
            })
            ->whereIn('id', $id_kode_pengeluaran_has_dana)
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

        // get saldo akhir
        $saldo_akhir = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->sum('danas.nominal');

        $saldo_penerimaan_tunai = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->sum('danas.nominal');

        $saldo_pengeluaran_tunai = Dana::join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Tunai/Cash')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('kodes.jenis_kode', '=', 'Pengeluaran')
            ->sum('danas.nominal');

        $saldo_tunai = ($saldo_penerimaan_tunai - $saldo_pengeluaran_tunai);

        $saldo_penerimaan_bank = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get();

        $saldo_penerimaan_banks = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('kodes.jenis_kode', '=', 'Penerimaan')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get()
            ->toArray();

        $saldo_pengeluaran_banks = DetailBank::join('danas', 'detail_banks.id_dana', '=', 'danas.id')
            ->join('akun_banks', 'detail_banks.id_bank', '=', 'akun_banks.id')
            ->join('kodes', 'danas.id_kode', '=', 'kodes.id')
            ->where('danas.tanggal', '>=', $tanggalAwal)
            ->where('danas.tanggal', '<=', $tanggalAkhir)
            ->where('danas.transaksi', '=', 'Transfer Bank')
            ->where('kodes.jenis_kode', '=', 'Pengeluaran')
            ->groupBy('detail_banks.id_bank')
            ->selectRaw('akun_banks.nama_bank, sum(danas.nominal) as nominalDana')
            ->get()
            ->toArray();

        $saldo_banks = [];
        $count1 = count($saldo_penerimaan_banks);
        $count2 = count($saldo_pengeluaran_banks);
        $closeTable = null;

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

        $saldo_banks = collect($saldo_banks);

        $table = '<tr><td colspan="4"><b>PENCATATAN KAS GKE SINAR KASIH</b></td></tr>';
        $table .= '<tr></tr>';

        if (count($kode_penerimaans) > 0) {
            $table .= '<table><thead><tr><th><b>A.</b></th><th><b>PENERIMAAN</b></th><th></th><th></th></tr>';
            $table .= '<tr><th><b>Kode Anggaran</b></th><th><b>URAIAN</b></th><th><b>KETERANGAN</b></th><th><b>JUMLAH</b></th></tr>';
            $table .= '<tr><th><b>4</b></th><th><b>PENDAPATAN</b></th><th></th><th></th></tr>';
            $jumlah = 0;
            $jumlahPenerimaan = 0;
            $jumlahPenerimaanPerSubSubKode = 0;
            foreach ($kode_penerimaans['kode'] as $kode_penerimaan) {
                $no_kode_penerimaan = "4." . $kode_penerimaan['no_kode'];
                $kode_penerimaan['nama_kode'] = htmlentities($kode_penerimaan['nama_kode']);
                $table .= '<tr>';
                $table .= '<th><b>' . $no_kode_penerimaan . '</b></th>';
                $table .= '<th><b>' . $kode_penerimaan['nama_kode'] . '</b></th>';
                $table .= '</tr>';
                if (!array_key_exists('sub_kode', $kode_penerimaan)) continue;
                foreach ($kode_penerimaan['sub_kode'] as $sub_kode) {
                    if (in_array($sub_kode['id'], $listAllowIdSubKodePenerimaan)) {
                        $table .= '<tr>';
                        $table .= '<th class="py-0 px-2 text-center" style="vertical-align: middle"><b>' . "4." . $kode_penerimaan['no_kode'] . "." . $sub_kode['no_sub_kode'] . '</b></th>';
                        $table .= '<th class="py-0 px-2" style="vertical-align: middle"><b>' . $sub_kode['nama_sub_kode'] . '</b></th>';
                        $table .= '<th class="py-0 px-2" style="vertical-align: middle"></th>';
                        $table .= '<th class="py-0 px-2" style="vertical-align: middle"></th>';
                        $table .= '</tr>';
                    }
                    if (!array_key_exists('sub_sub_kode', $sub_kode)) continue;
                    foreach ($sub_kode['sub_sub_kode'] as $sub_sub_kode) {
                        if (!array_key_exists('dana', $sub_sub_kode)) continue;
                        foreach ($sub_sub_kode['dana'] as $key => $dana) {
                            $jumlah += $dana['nominal'];
                            $jumlahPenerimaan += $dana['nominal'];
                            $jumlahPenerimaanPerSubSubKode += $dana['nominal'];
                            $table .= '<tr>';
                            if ($key == 0) {
                                $table .= '<td class="text-center py-0 px-2">' . "4." . $kode_penerimaan['no_kode'] . "." . $sub_kode['no_sub_kode'] . "." . $sub_sub_kode['no_sub_sub_kode'] . '</td>';
                                $table .= '<td class="text-center py-0 px-2">' . $sub_sub_kode['nama_sub_sub_kode'] . '</td>';
                            } else {
                                $table .= '<td></td><td></td>';
                            }
                            $table .= '<td class="text-center py-0 px-2">' . $dana['keterangan'] . '</td>';
                            $table .= '<td class="py-0 px-2">Rp. ' . number_format($dana['nominal'], 0, ',', '.') . '</td>';
                            $table .= '</tr>';
                        }
                        $table .= '<tr>';
                        $table .= '<td></td><td></td>';
                        $table .= '<td><b>JUMLAH</b></td><td><b>Rp. ' . number_format($jumlahPenerimaanPerSubSubKode, 0, ',', '.') . '</b></td>';
                        $table .= '</tr>';
                        $jumlahPenerimaanPerSubSubKode = 0;
                    }
                    $jumlah = 0;
                }
            }
            $table .= '<tr>';
            $table .= '<td colspan="2"></td>';
            $table .= '<td><b>JUMLAH PENERIMAAN</b></td><td><b>Rp. ' . number_format($jumlahPenerimaan, 0, ',', '.') . '</b></td>';
            $table .= '</tr>';
        }

        if (count($kode_pengeluarans) > 0) {
            $table2 = '<tr></tr>';
            $table2 .= '<tr></tr>';
            $table2 .= '<table><thead><tr><th><b>B.</b></th><th><b>BELANJA</b></th><th></th><th></th></tr>';
            $table2 .= '<tr><th><b>Kode Anggaran</b></th><th colspan="2"><b>URAIAN</b></th><th><b>JUMLAH</b></th></tr>';
            $table2 .= '<tr><th><b>5</b></th><th><b>BELANJA</b></th><th></th><th></th></tr>';
            $jumlah = 0;
            $jumlahPengeluaran = 0;
            $jumlahPengeluaranPerSubSubKode = 0;
            foreach ($kode_pengeluarans['kode'] as $kode_pengeluaran) {
                $no_kode_pengeluaran = "5." . $kode_pengeluaran['no_kode'];
                $kode_pengeluaran['nama_kode'] = htmlentities($kode_pengeluaran['nama_kode']);
                $table2 .= '<tr>';
                $table2 .= '<th><b>' . $no_kode_pengeluaran . '</b></th><th><b>' . $kode_pengeluaran['nama_kode'] . '</b></th>';
                $table2 .= '</tr>';
                if (!array_key_exists('sub_kode', $kode_pengeluaran)) continue;
                foreach ($kode_pengeluaran['sub_kode'] as $sub_kode) {
                    if (in_array($sub_kode['id'], $listAllowIdSubKodePengeluaran)) {
                        $table2 .= '<tr>';
                        $table2 .= '<th><b>' . "5." . $kode_pengeluaran['no_kode'] . "." . $sub_kode['no_sub_kode'] . '</b></th>';
                        $table2 .= '<th><b>' . $sub_kode['nama_sub_kode'] . '</b></th>';
                        $table2 .= '<th></th><th></th>';
                        $table2 .= '</tr>';
                    }
                    if (!array_key_exists('sub_sub_kode', $sub_kode)) continue;
                    foreach ($sub_kode['sub_sub_kode'] as $sub_sub_kode) {
                        if (!array_key_exists('dana', $sub_sub_kode)) continue;
                        foreach ($sub_sub_kode['dana'] as $key => $dana) {
                            $jumlah += $dana['nominal'];
                            $jumlahPengeluaran += $dana['nominal'];
                            $jumlahPengeluaranPerSubSubKode += $dana['nominal'];
                            $table2 .= '<tr>';
                            if ($key == 0) {
                                $table2 .= '<td>' . "5." . $kode_pengeluaran['no_kode'] . "." . $sub_kode['no_sub_kode'] . "." . $sub_sub_kode['no_sub_sub_kode'] . '</td>';
                                $table2 .= '<td>' . $sub_sub_kode['nama_sub_sub_kode'] . '</td>';
                            } else {
                                $table2 .= '<td></td><td></td>';
                            }
                            $table2 .= '<td>' . $dana['keterangan'] . '</td>';
                            $table2 .= '<td>Rp. ' . number_format($dana['nominal'], 0, ',', '.') . '</td>';
                            $table2 .= '</tr>';
                        }
                        $table2 .= '<tr>';
                        $table2 .= '<td></td><td></td><td><b>JUMLAH</b></td>';
                        $table2 .= '<td><b>Rp. ' . number_format($jumlahPengeluaranPerSubSubKode, 0, ',', '.') . '</b></td>';
                        $table2 .= '</tr>';
                        $jumlahPengeluaranPerSubSubKode = 0;
                    }
                }
            }
            $table2 .= '<tr>';
            $table2 .= '<td colspan="2"></td>';
            $table2 .= '<td><b>JUMLAH PENGELUARAN</b></td><td><b>Rp. ' . number_format($jumlahPengeluaran, 0, ',', '.') . '</b></td>';
            $table2 .= '</tr>';
        }

        $table3 = '<tr></tr>';
        $table3 .= '<tr></tr>';
        $table3 .= '<table><thead>';
        $table3 .= '<tr><th colspan="3"><b>Keterangan :</b></th><th></th><th></th><th></th></tr>';
        $table3 .= '</thead><tbody>';
        $table3 .= '<tr><td colspan="3"><b>Saldo terakhir tanggal, ' . getIndonesianDate($tanggalAwal) . '</b></td><td><b>Rp. ' . number_format($saldo_akhir, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Penerimaan, ' . getIndonesianDate($tanggalAwal) . ' - ' . getIndonesianDate($tanggalAkhir) . '</b></td><td><b>Rp. ' . number_format($jumlahPenerimaan, 0, ',', '.') . '</b></td></tr>';
        $jumlahSaldoPenerimaan = $jumlahPenerimaan + $saldo_akhir;
        $jumlahSaldoPengeluaran = $jumlahSaldoPenerimaan - $jumlahPengeluaran;
        $table3 .= '<tr><td colspan="3"></td><td><b>Rp. ' . number_format($jumlahSaldoPenerimaan, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Pengeluaran, ' . getIndonesianDate($tanggalAwal) . ' - ' . getIndonesianDate($tanggalAkhir) . '</b></td><td><b>Rp. ' . number_format($jumlahPengeluaran, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Saldo terakhir tanggal, ' . getIndonesianDate($tanggalAkhir) . '</b></td><td><b>Rp. ' . number_format($jumlahSaldoPengeluaran, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td></td><td></td><td></td><td></td></tr>';
        $table3 .= '<tr><td><b>Tempat Penyimpanan :</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Kas Tunai</b></td><td><b>Rp. ' . number_format($saldo_tunai, 0, ',', '.') . '</b></td></tr>';
        $totalSaldoBank = 0;
        $totalKasGereja = 0;
        foreach ($saldo_banks as $saldo_bank) {
            $totalSaldoBank += $saldo_bank['nominalDana'];
            $table3 .= '<tr><td colspan="3"><b>' . $saldo_bank['nama_bank'] . '</b></td><td><b>Rp. ' . number_format($saldo_bank['nominalDana'], 0, ',', '.') . '</b></td></tr>';
        }
        $totalKasGereja = ($totalSaldoBank + $saldo_tunai);
        $table3 .= '<tr><td colspan="3"><b>TOTAL SALDO KAS GEREJA</b></td><td><b>Rp. ' . number_format($totalKasGereja, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '</tbody></table>';

        return view('exports.cetak_laporan', compact('table', 'table2', 'table3'));
    }
}
