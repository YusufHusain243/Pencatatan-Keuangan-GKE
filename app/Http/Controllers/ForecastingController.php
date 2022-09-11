<?php

namespace App\Http\Controllers;

use App\Models\Forecasting;
use Illuminate\Http\Request;

class ForecastingController extends Controller
{
    public function index()
    {
        $forecastings = Forecasting::all();
        return view('pages/forecasting', [
            "title" => "forecasting",
            "forecastings" => $forecastings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'tahun' => 'required|unique:forecastings,tahun|min:4',
                'penerimaan' => 'required',
                'pengeluaran' => 'required',
            ],
            [
                'tahun.unique' => 'Tahun sudah ada',
                'tahun.min' => 'Tahun harus memiliki minimal 4 karakter',
                'tahun.required' => 'Tahun tidak boleh kosong',
                'penerimaan.required' => 'Penerimaan tidak boleh kosong',
                'pengeluaran.required' => 'Pengeluaran tidak boleh kosong',
            ]
        );

        if ($validated) {
            $result = Forecasting::create([
                'tahun' => $request->tahun,
                'penerimaan' => $request->penerimaan,
                'pengeluaran' => $request->pengeluaran,
            ]);
            if ($result) {
                return redirect('/forecasting')->with('ForecastingSuccess', 'Tambah Data Berhasil');
            }
            return redirect('/forecasting')->with('ForecastingError', 'Tambah Data Gagal');
        }
    }

    public function edit($id)
    {
        $forecasting = Forecasting::findOrFail($id);
        if ($forecasting) {
            return view('pages/edit_forecasting', [
                "title" => "forecasting",
                "forecasting" => $forecasting
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'tahun' => 'required|unique:forecastings,tahun|min:4',
                'penerimaan' => 'required',
                'pengeluaran' => 'required',
            ],
            [
                'tahun.unique' => 'Tahun sudah ada',
                'tahun.min' => 'Tahun harus memiliki minimal 4 karakter',
                'tahun.required' => 'Tahun tidak boleh kosong',
                'penerimaan.required' => 'Penerimaan tidak boleh kosong',
                'pengeluaran.required' => 'Pengeluaran tidak boleh kosong',
            ]
        );

        if ($validated) {
            $result = Forecasting::findOrFail($id)->update([
                'tahun' => $request->tahun,
                'penerimaan' => $request->penerimaan,
                'pengeluaran' => $request->pengeluaran,
            ]);
            if ($result) {
                return redirect('/forecasting')->with('ForecastingSuccess', 'Tambah Data Berhasil');
            }
            return redirect('/forecasting')->with('ForecastingError', 'Tambah Data Gagal');
        }
    }

    public function destroy($id)
    {
        $data = Forecasting::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/forecasting')->with('ForecastingSuccess', 'Hapus Data Berhasil');
            }
            return redirect('/forecasting')->with('ForecastingError', 'Hapus Data Gagal');
        }
    }
}
