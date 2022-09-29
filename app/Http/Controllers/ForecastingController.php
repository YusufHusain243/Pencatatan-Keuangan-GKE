<?php

namespace App\Http\Controllers;

use App\Models\Forecasting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ForecastingController extends Controller
{
    public function index()
    {
        $forecastings = Forecasting::orderBy('tahun', 'ASC')->get();
        return view('pages/forecasting', [
            "title" => "forecasting",
            "forecastings" => $forecastings
        ]);
    }

    public function forecastingPenerimaan()
    {
        $data_forecastings = Forecasting::orderBy('tahun', 'ASC')->get();
        return view('pages/forecasting_penerimaan', [
            "title" => "forecasting-penerimaan",
            "data_forecastings" => $data_forecastings
        ]);
    }

    public function forecastingPengeluaran()
    {
        $data_forecastings = Forecasting::orderBy('tahun', 'ASC')->get();
        return view('pages/forecasting_pengeluaran', [
            "title" => "forecasting-pengeluaran",
            "data_forecastings" => $data_forecastings
        ]);
    }

    public function hitungForecasting(Request $request)
    {
        $data_forecastings = Forecasting::orderBy('tahun', 'ASC')->get();

        $year = decrypt($request->year);
        $collection = collect($year);
        $year = $collection->sortBy('tahun')->values();
        $jenis = decrypt($request->jenis);
        $x = decrypt($request->x);
        $y = decrypt($request->y);
        $xx = decrypt($request->xx);
        $xy = decrypt($request->xy);
        $avg_x = decrypt($request->avg_x);
        $avg_y = decrypt($request->avg_y);
        $n = decrypt($request->n);


        if ($n <= 1) {
            if ($jenis == 'penerimaan') {
                echo "<script>alert('Data Peramalan harus lebih dari 1');</script>";
                echo "<script>window.location.href = '/forecasting-penerimaan';</script>";
            } else {
                echo "<script>alert('Data Peramalan harus lebih dari 1');</script>";
                echo "<script>window.location.href = '/forecasting-pengeluaran';</script>";
            }
        } else {
            for ($i = 0; $i <= count($year) + 4; $i++) {
                //rumus hitung regresi linear
                $b = round(((($n * $xy) - ($x * $y)) / (($n * $xx) - ($x * $x))), 2);
                $a = round(($avg_y - ($b * $avg_x)), 2);
                $result = round(($a + ($b * ($i + 1))), 2);

                if ($i <= count($year) - 1) {
                    $temp_data_prediction['x'] = (int) $year[$i]->tahun;
                    $temp_data['x'] = (int) $year[$i]->tahun;

                    if ($jenis == 'penerimaan') {
                        $temp_data['y'] = number_format((int) $year[$i]->penerimaan, 2, ',', '.');
                    } else {
                        $temp_data['y'] = number_format((int) $year[$i]->pengeluaran, 2, ',', '.');
                    }
                } else {
                    $temp_data_prediction['x'] = (int) $year->first()->tahun + $i;
                    $temp_data['x'] = (int) $year->first()->tahun + $i;

                    if ($jenis == 'penerimaan') {
                        $temp_data['y'] = null;
                    } else {
                        $temp_data['y'] = null;
                    }
                }

                $temp_data_prediction['y'] = round($result, 2);
                $result_data_prediction[] = $temp_data_prediction;

                $result_data[] = $temp_data;
            }

            for ($j = count($year); $j <= count($year) + 4; $j++) {
                if ($jenis == 'penerimaan') {
                    if ($year->last()->penerimaan < $result_data_prediction[$j]['y']) {
                        $temp_persen['name'] = "NAIK";
                    } elseif ($year->last()->penerimaan > $result_data_prediction[$j]['y']) {
                        $temp_persen['name'] = "TURUN";
                    } else {
                        $temp_persen['name'] = "Tidak Ada Perubahan";
                    }
                    $temp_persen['tahun'] = $result_data_prediction[$j]['x'];
                    //hitung persen prediksi penerimaan
                    $persentase_kenaikan = ($result_data_prediction[$j]['y'] - $year->last()->penerimaan) / $year->last()->penerimaan * 100;
                    $temp_persen['persen'] = number_format($persentase_kenaikan, 2);
                    $persen_arr[] = $temp_persen;
                } else {
                    if ($year->last()->pengeluaran < $result_data_prediction[$j]['y']) {
                        $temp_persen['name'] = "NAIK";
                    } elseif ($year->last()->pengeluaran > $result_data_prediction[$j]['y']) {
                        $temp_persen['name'] = "TURUN";
                    } else {
                        $temp_persen['name'] = "Tidak Ada Perubahan";
                    }
                    $temp_persen['tahun'] = $result_data_prediction[$j]['x'];
                    //hitung persen prediksi pengeluaran
                    $persentase_kenaikan = ($result_data_prediction[$j]['y'] - $year->last()->pengeluaran) / $year->last()->pengeluaran * 100;

                    $temp_persen['persen'] = number_format($persentase_kenaikan, 2);
                    $persen_arr[] = $temp_persen;
                }
            }
            $result_data_prediction = collect($result_data_prediction);
            $result_data_prediction = $result_data_prediction->values()->toArray();

            $result_data = collect($result_data);
            $result_data = $result_data->values()->toArray();

            if ($jenis == 'penerimaan') {
                return view('pages/forecasting_penerimaan', [
                    "title" => "forecasting-penerimaan",
                    "data_forecastings" => $data_forecastings,
                    "result_data_prediction" => json_encode($result_data_prediction),
                    "result_data" => json_encode($result_data),
                    "a" => round($a, 2),
                    "b" => round($b, 2),
                    "persen_arr" => $persen_arr,
                ]);
            } else {
                return view('pages/forecasting_pengeluaran', [
                    "title" => "forecasting-pengeluaran",
                    "data_forecastings" => $data_forecastings,
                    "result_data_prediction" => json_encode($result_data_prediction),
                    "result_data" => json_encode($result_data),
                    "a" => round($a, 2),
                    "b" => round($b, 2),
                    "persen_arr" => $persen_arr,
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
            $request->penerimaan = (int) filter_var($request->penerimaan, FILTER_SANITIZE_NUMBER_INT);
            $request->pengeluaran = (int) filter_var($request->pengeluaran, FILTER_SANITIZE_NUMBER_INT);
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
        $id = Crypt::decrypt($id);
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
        $id = Crypt::decrypt($id);
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
            $request->penerimaan = (int) filter_var($request->penerimaan, FILTER_SANITIZE_NUMBER_INT);
            $request->pengeluaran = (int) filter_var($request->pengeluaran, FILTER_SANITIZE_NUMBER_INT);
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
        $id = Crypt::decrypt($id);
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
