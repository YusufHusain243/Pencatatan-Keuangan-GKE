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

        $kodes = Kode::whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])->get();

        return view('pages.cetak_laporan', compact('title', 'kodes', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function export()
    {
        return Excel::download(new LaporanExport, 'laporan-' . date('d-m-Y') . '.xlsx');
    }
}
