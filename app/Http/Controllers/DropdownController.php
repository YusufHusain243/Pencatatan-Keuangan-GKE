<?php

namespace App\Http\Controllers;

use App\Models\Kode;
use App\Models\SubKode;
use App\Models\SubSubKode;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function get_kode_anggaran(Request $request)
    {
        $kodes = Kode::query()
            ->when($request->jenis_kode, function ($query) use ($request) {
                return $query->where('jenis_kode', $request->jenis_kode);
            })
            ->get();

        return view('pages.dropdown.kode_anggaran', compact('kodes'));
    }

    public function get_sub_kode_anggaran(Request $request)
    {
        $sub_kodes = SubKode::query()
            ->when($request->jenis_kode, function ($query) use ($request) {
                return $query->whereRelation('subKodeToKode', 'jenis_kode', $request->jenis_kode);
            })
            ->when($request->kode, function ($query) use ($request) {
                return $query->where('id_kode', $request->kode);
            })
            ->get();

        return view('pages.dropdown.sub_kode_anggaran', compact('sub_kodes'));
    }

    public function get_sub_sub_kode_anggaran(Request $request)
    {
        $sub_sub_kodes = SubSubKode::query()
            ->when($request->sub_kode, function ($query) use ($request) {
                return $query->where('id_sub_kode', $request->sub_kode);
            })
            ->get();

        return view('pages.dropdown.sub_sub_kode_anggaran', compact('sub_sub_kodes'));
    }

    // Kode

    public function get_no_sub_kode_anggaran(Request $request)
    {
        $sub_kodes = SubKode::query()
            ->when($request->jenis_kode, function ($query) use ($request) {
                return $query->whereRelation('subKodeToKode', 'jenis_kode', $request->jenis_kode);
            })
            ->get();

        return view('pages.dropdown.kode.sub_kode_anggaran', compact('sub_kodes'));
    }
}
