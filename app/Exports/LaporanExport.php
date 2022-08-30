<?php

namespace App\Exports;

use App\Models\Dana;
use App\Models\Kode;
use App\Models\SubKode;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    public function view(): View
    {
        $danas = Dana::all();
        $kodes = Kode::all();
        $sub_kodes = SubKode::all();
        
        return view('exports.cetak_laporan', compact('danas', 'kodes', 'sub_kodes'));
    }
}
