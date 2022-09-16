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

    public function forecastingPenerimaan()
    {
        $data_forecastings = Forecasting::all();
        return view('pages/forecasting_penerimaan', [
            "title" => "forecasting-penerimaan",
            "data_forecastings" => $data_forecastings
        ]);
    }

    public function forecastingPengeluaran()
    {
        $data_forecastings = Forecasting::all();
        return view('pages/forecasting_pengeluaran', [
            "title" => "forecasting-pengeluaran",
            "data_forecastings" => $data_forecastings
        ]);
    }

    public function hitungForecasting(Request $request)
    {
        $data_forecastings = Forecasting::all();

        $jenis = $request->jenis;
        $x = $request->x;
        $y = $request->y;
        $xx = $request->xx;
        $xy = $request->xy;
        $n = $request->n;
        $tahun = $request->tahun_prediksi;

        if ($n <= 1) {
            if ($jenis == 'penerimaan') {
                echo "<script>alert('Data Peramalan harus lebih dari 1');</script>";
                echo "<script>window.location.href = '/forecasting-penerimaan';</script>";
            } else {
                echo "<script>alert('Data Peramalan harus lebih dari 1');</script>";
                echo "<script>window.location.href = '/forecasting-pengeluaran';</script>";
            }
        } else {
            $b = ((($n * $xy) - ($x * $y)) / (($n * $xx) - ($x * $x)));
            $a = ($y - ($b * $x)) / $n;
            $result = ($a + ($b * $tahun));

            if ($jenis == 'penerimaan') {
                return view('pages/forecasting_penerimaan', [
                    "title" => "forecasting-penerimaan",
                    "data_forecastings" => $data_forecastings,
                    "result" => $result,
                    "tahun" => $tahun,
                    "a" => $a,
                    "b" => $b,
                ]);
            } else {
                return view('pages/forecasting_pengeluaran', [
                    "title" => "forecasting-pengeluaran",
                    "data_forecastings" => $data_forecastings,
                    "result" => $result,
                    "tahun" => $tahun,
                    "a" => $a,
                    "b" => $b,
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'tahun' => 'required',
                'penerimaan' => 'required',
                'pengeluaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'penerimaan.required' => 'Penerimaan tidak boleh kosong',
                'pengeluaran.required' => 'Pengeluaran tidak boleh kosong',
            ]
        );

        if ($validated) {
            $cek = Forecasting::where('tahun', $request->tahun)->get();
            if (count($cek) <= 0) {
                $result = Forecasting::create([
                    'tahun' => $request->tahun,
                    'penerimaan' => $request->penerimaan,
                    'pengeluaran' => $request->pengeluaran,
                ]);
                if ($result) {
                    return redirect('/data-forecasting')->with('ForecastingSuccess', 'Tambah Data Berhasil');
                }
                return redirect('/data-forecasting')->with('ForecastingError', 'Tambah Data Gagal');
            } else {
                return redirect('/data-forecasting')->with('ForecastingError', 'Tambah Data Gagal, Data Tahun Sudah Ada');
            }
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
                'tahun' => 'required',
                'penerimaan' => 'required',
                'pengeluaran' => 'required',
            ],
            [
                'tahun.required' => 'Tahun tidak boleh kosong',
                'penerimaan.required' => 'Penerimaan tidak boleh kosong',
                'pengeluaran.required' => 'Pengeluaran tidak boleh kosong',
            ]
        );

        if ($validated) {
            $cek = Forecasting::where('tahun', $request->tahun)->get();
            if (count($cek) <= 0) {
                $result = Forecasting::findOrFail($id)->update([
                    'tahun' => $request->tahun,
                    'penerimaan' => $request->penerimaan,
                    'pengeluaran' => $request->pengeluaran,
                ]);
                if ($result) {
                    return redirect('/data-forecasting')->with('ForecastingSuccess', 'Edit Data Berhasil');
                }
                return redirect('/data-forecasting')->with('ForecastingError', 'Edit Data Gagal');
            } else {
                if ($cek[0]->id == $id) {
                    $result = Forecasting::findOrFail($id)->update([
                        'tahun' => $request->tahun,
                        'penerimaan' => $request->penerimaan,
                        'pengeluaran' => $request->pengeluaran,
                    ]);
                    if ($result) {
                        return redirect('/data-forecasting')->with('ForecastingSuccess', 'Edit Data Berhasil');
                    }
                    return redirect('/data-forecasting')->with('ForecastingError', 'Edit Data Gagal');
                } else {
                    return redirect('/data-forecasting')->with('ForecastingError', 'Edit Data Gagal, Tahun Sudah Ada');
                }
            }
        }
    }

    public function destroy($id)
    {
        $data = Forecasting::findOrFail($id);
        if ($data) {
            $result = $data->delete();
            if ($result) {
                return redirect('/data-forecasting')->with('ForecastingSuccess', 'Hapus Data Berhasil');
            }
            return redirect('/data-forecasting')->with('ForecastingError', 'Hapus Data Gagal');
        }
    }
}
