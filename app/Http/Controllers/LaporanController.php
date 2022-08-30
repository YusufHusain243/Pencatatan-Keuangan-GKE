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
        $danas = Dana::all();
        $kodes = Kode::all();
        $sub_kodes = SubKode::all();
        $sub_sub_kodes = SubSubKode::all();
        $title = "laporan";

        return view('pages.laporan', compact('title', 'danas', 'kodes', 'sub_kodes', 'sub_sub_kodes'));
    }

    public function export()
    {
        return Excel::download(new LaporanExport, 'laporan-' . date('d-m-Y') . '.xlsx');
    }
}