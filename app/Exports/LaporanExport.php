<?php

namespace App\Exports;

use App\Models\Dana;
use App\Models\Kode;
use App\Models\SubKode;
use App\Models\DetailBank;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
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
                $event->sheet->getDelegate()->getStyle('A3')
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
        $table .= '<table><thead><tr><th><b>A.</b></th><th><b>PENERIMAAN</b></th><th></th><th></th></tr>';
        $table .= '<tr><th><b>Kode Anggaran</b></th><th><b>URAIAN</b></th><th><b>KETERANGAN</b></th><th><b>JUMLAH</b></th></tr>';
        $table .= '<tr><th><b>4</b></th><th><b>PENDAPATAN</b></th><th></th><th></th></tr>';

        $jumlah = 0;
        $jumlahPenerimaan = 0;

        if (count($kodes) > 0) {
            foreach ($kodes as $keyKode => $kode) {
                if ($kode->jenis_kode == 'Penerimaan') {
                    $no_kode_kode = "4." . $kode->no_kode;
                    $kode->nama_kode = htmlentities($kode->nama_kode);
                    $table .= '<tr><th><b>' . $no_kode_kode . '</b></th><th><b>' . $kode->nama_kode . '</b></th></tr>';
                    if (count($kode->kodeToSubKode) > 0) {
                        foreach ($kode->kodeToSubKode as $sub_kode) {
                            if ($sub_kode->subKodeToKode->no_kode == $kode->no_kode) {
                                $no_kode_sub_kode = $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? "4." . $sub_kode->subKodeToKode->no_kode . "." . $sub_kode->no_sub_kode : "5." . $sub_kode->subKodeToKode->no_kode . "." . $sub_kode->no_sub_kode;
                                $table .= '<tr><th><b>' . $no_kode_sub_kode . '</b></th><th><b>' . $sub_kode->nama_sub_kode . '</b></th><th></th><th></th></tr>';
                            }
                        }
                        if ($kode->id == $kodes->where('jenis_kode', 'Penerimaan')->last()->id) {
                            $table .= '</thead><tbody>';
                        }
                        foreach ($sub_kode->subKodeToSubSubKode as $sub_sub_kode) {
                            foreach ($sub_sub_kode->subSubKodeToDana as $key => $dana) {
                                $jumlah += $dana->nominal;
                                $jumlahPenerimaan += $dana->nominal;
        
                                $table .= '<tr>';
                                if ($dana == $sub_sub_kode->subSubKodeToDana[0]) {
                                    $no_kode_sub_sub_kode = $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? "4." . $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . "." . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode . "." . $sub_sub_kode->no_sub_sub_kode : "5." . $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . "." . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode . "." . $sub_sub_kode->no_sub_sub_kode;
                                    $table .= '<td>' . $no_kode_sub_sub_kode . '</td>';
                                } else {
                                    $table .= '<td></td>';
                                }
                                $table .= '<td>' . $sub_sub_kode->nama_sub_sub_kode . '</td>';
                                $table .= '<td>' . $dana->keterangan . '</td>';
                                $table .= '<td>Rp. ' . number_format($dana->nominal, 0, ',', '.') . '</td>';
                                $table .= '</tr>';
                            }
                        }
                        $table .= '<tr><td></td><td></td><td><b>JUMLAH</b></td><td>Rp. ' . number_format($jumlah, 0, ',', '.') . '</td></tr>';
                        $table .= '<tr></tr>';
                        $jumlah = 0;
                        if ($kode->id == $kodes->where('jenis_kode', 'Penerimaan')->last()->id) {
                            $table .= '</thead></tbody></table>';
                        }
                    } else {
                        $table .= '</thead></table>';
                    }
                } else {
                    $table .= '</thead></table>';
                }
            }
        } else {
            $table .= '</thead></table>';
        }

        $table2 = '<table><thead><tr><th><b>B.</b></th><th><b>BELANJA</b></th><th></th><th></th></tr>';
        $table2 .= '<tr><th><b>Kode Anggaran</b></th><th colspan="2"><b>URAIAN</b></th><th><b>JUMLAH</b></th></tr>';
        $table2 .= '<tr><th><b>5</b></th><th><b>BELANJA</b></th><th></th><th></th></tr>';

        $jumlah = 0;
        $jumlahPengeluaran = 0;

        if (count($kodes) > 0) {
            foreach ($kodes as $keyKode => $kode) {
                if ($kode->jenis_kode == 'Pengeluaran') {
                    $no_kode_kode = "5." . $kode->no_kode;
                    $kode->nama_kode = htmlentities($kode->nama_kode);
                    $table2 .= '<tr><th><b>' . $no_kode_kode . '</b></th><th><b>' . $kode->nama_kode . '</b></th><th></th><th></th></tr>';
                    foreach ($kode->kodeToSubKode as $sub_kode) {
                        if ($sub_kode->subKodeToKode->no_kode == $kode->no_kode) {
                            $no_kode_sub_kode = $sub_kode->subKodeToKode->jenis_kode == 'Penerimaan' ? "4." . $sub_kode->subKodeToKode->no_kode . "." . $sub_kode->no_sub_kode : "5." . $sub_kode->subKodeToKode->no_kode . "." . $sub_kode->no_sub_kode;
                            $table2 .= '<tr><th><b>' . $no_kode_sub_kode . '</b></th><th><b>' . $sub_kode->nama_sub_kode . '</b></th><th></th><th></th></tr>';
                        }
                    }
                    if ($kode->id == $kodes->where('jenis_kode', 'Pengeluaran')->last()->id) {
                        $table2 .= '</thead><tbody>';
                    }
                    foreach ($sub_kode->subKodeToSubSubKode as $sub_sub_kode) {
                        foreach ($sub_sub_kode->subSubKodeToDana as $key => $dana) {
                            $jumlah += $dana->nominal;
                            $jumlahPengeluaran += $dana->nominal;
    
                            $table2 .= '<tr>';
                            if ($dana == $sub_sub_kode->subSubKodeToDana[0]) {
                                $no_kode_sub_sub_kode = $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->jenis_kode == 'Penerimaan' ? "4." . $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . "." . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode . "." . $sub_sub_kode->no_sub_sub_kode : "5." . $sub_sub_kode->subSubKodeToSubKode->subKodeToKode->no_kode . "." . $sub_sub_kode->subSubKodeToSubKode->no_sub_kode . "." . $sub_sub_kode->no_sub_sub_kode;
                                $table2 .= '<td>' . $no_kode_sub_sub_kode . '</td>';
                            } else {
                                $table2 .= '<td></td>';
                            }
                            if ($dana == $sub_sub_kode->subSubKodeToDana[0]) {
                                $table2 .= '<td>' . $sub_sub_kode->nama_sub_sub_kode . '</td>';
                            }
                            $table2 .= '<td>' . $dana->keterangan . '</td>';
                            $table2 .= '<td>Rp. ' . number_format($dana->nominal, 0, ',', '.') . '</td>';
                            $table2 .= '</tr>';
                        }
                    }
                    $table2 .= '<tr>';
                    $table2 .= '<td></td><td></td><td><b>JUMLAH</b></td>';
                    if (isset($key)) {
                        if ($key == count($sub_sub_kode->subSubKodeToDana) - 1) {
                            $table2 .= '<td>Rp. ' . number_format($jumlah, 0, ',', '.') . '</td>';
                        } else {
                            $table2 .= '<tr><td><b>Rp. ' . number_format($jumlah, 0, ',', '.') . '</b></td></tr>';
                        }
                    }
                    $table2 .= '</tr>';
                    $jumlah = 0;
                    $table2 .= '<tr><td colspan="3"><b>JUMLAH PENGELUARAN</b></td><td><b>Rp. ' . number_format($jumlahPengeluaran, 0, ',', '.') . '</b></td></tr>';
                    $table2 .= '<tr></tr>';
    
                    if ($kode->id == $kodes->where('jenis_kode', 'Pengeluaran')->last()->id) {
                        $table2 .= '</tbody></table>';
                    }
                } else {
                    $table2 .= '</thead></table>';
                }
            }
        } else {
            $table2 .= '</thead></table>';
        }

        $table3 = '<table><thead>';
        $table3 .= '<tr><th colspan="3"><b>Keterangan :</b></th><th></th><th></th><th></th></tr>';
        $table3 .= '</thead><tbody>';
        $table3 .= '<tr><td colspan="3"><b>Saldo terakhir tanggal, ' . date('d F Y', strtotime($tanggalAwal)) . '</b></td><td><b>Rp. ' . number_format($saldo_akhir, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Penerimaan, ' . date('d F Y', strtotime($tanggalAwal)) . ' - ' . date('d F Y', strtotime($tanggalAkhir)) . '</b></td><td><b>Rp. ' . number_format($jumlahPenerimaan, 0, ',', '.') . '</b></td></tr>';
        $jumlahSaldoPenerimaan = $jumlahPenerimaan + $saldo_akhir;
        $jumlahSaldoPengeluaran = $jumlahSaldoPenerimaan - $jumlahPengeluaran;
        $table3 .= '<tr><td colspan="3"></td><td><b>Rp. ' . number_format($jumlahSaldoPenerimaan, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Pengeluaran, ' . date('d F Y', strtotime($tanggalAwal)) . ' - ' . date('d F Y', strtotime($tanggalAkhir)) . '</b></td><td><b>Rp. ' . number_format($jumlahPengeluaran, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Saldo terakhir tanggal, ' . date('d F Y', strtotime($tanggalAkhir)) . '</b></td><td><b>Rp. ' . number_format($jumlahSaldoPengeluaran, 0, ',', '.') . '</b></td></tr>';
        $table3 .= '<tr><td></td><td></td><td></td><td></td></tr>';
        $table3 .= '<tr><td><b>Tempat Penyimpanan :</b></td></tr>';
        $table3 .= '<tr><td colspan="3"><b>Kas Tunai</b></td><td><b>Rp. ' . number_format($saldo_tunai, 0, ',', '.') . '</b></td></tr>';
        foreach ($saldo_banks as $saldo_bank) {
            $table3 .= '<tr><td colspan="3"><b>' . $saldo_bank['nama_bank'] . '</b></td><td><b>Rp. ' . number_format($saldo_bank['nominalDana'], 0, ',', '.') . '</b></td></tr>';
        }
        $table3 .= '</tbody></table>';

        $table = str_replace('</thead></table></thead></table>', '</thead></table>', $table);
        $table2 = str_replace('</thead></table></thead></table>', '</thead></table>', $table2);

        // $html = $table;
        // $html = $table2;
        // $html .= $table3;

        // die($html);

        return view('exports.cetak_laporan', compact('table', 'table2', 'table3'));
    }
}
