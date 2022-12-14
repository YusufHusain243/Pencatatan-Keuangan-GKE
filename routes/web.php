<?php

use App\Http\Controllers\AkunBankController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\KodeController;
use App\Http\Controllers\SubKodeController;
use App\Http\Controllers\SubSubKodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');;
Route::post('/login', [LoginController::class, 'authenticate']);

Route::middleware(['auth', 'isLogin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/user', [UserController::class, 'index'])->middleware('isAdmin');
    Route::post('/user', [UserController::class, 'store'])->middleware('isAdmin');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware('isAdmin');
    Route::get('edit/user/{id}', [UserController::class, 'edit'])->middleware('isAdmin');
    Route::patch('user/{id}', [UserController::class, 'update'])->middleware('isAdmin');

    Route::get('/kode/{param}', [KodeController::class, 'index']);
    Route::post('/kode', [KodeController::class, 'store']);
    Route::delete('/kode/{id}', [KodeController::class, 'destroy']);
    Route::get('edit/kode/{id}', [KodeController::class, 'edit']);
    Route::patch('/kode/{id}', [KodeController::class, 'update']);

    Route::get('/sub-kode/{param}', [SubKodeController::class, 'index']);
    Route::post('/sub-kode', [SubKodeController::class, 'store']);
    Route::delete('/sub-kode/{id}', [SubKodeController::class, 'destroy']);
    Route::get('edit/sub-kode/{id}', [SubKodeController::class, 'edit']);
    Route::patch('/sub-kode/{id}', [SubKodeController::class, 'update']);

    Route::get('/sub-sub-kode/{param}', [SubSubKodeController::class, 'index']);
    Route::post('/sub-sub-kode', [SubSubKodeController::class, 'store']);
    Route::delete('/sub-sub-kode/{id}', [SubSubKodeController::class, 'destroy']);
    Route::get('edit/sub-sub-kode/{id}', [SubSubKodeController::class, 'edit']);
    Route::patch('/sub-sub-kode/{id}', [SubSubKodeController::class, 'update']);

    Route::get('/akun-bank', [AkunBankController::class, 'index']);
    Route::post('/akun-bank', [AkunBankController::class, 'store']);
    Route::delete('/akun-bank/{id}', [AkunBankController::class, 'destroy']);
    Route::get('/edit/akun-bank/{id}', [AkunBankController::class, 'edit']);
    Route::patch('/akun-bank/{id}', [AkunBankController::class, 'update']);

    Route::get('/penerimaan', [PenerimaanController::class, 'index']);
    Route::post('/penerimaan', [PenerimaanController::class, 'store']);
    Route::delete('/penerimaan/{id}', [PenerimaanController::class, 'destroy']);
    Route::get('/edit/penerimaan/{id}', [PenerimaanController::class, 'edit']);
    Route::put('/penerimaan/{id}', [PenerimaanController::class, 'update']);

    Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
    Route::post('/pengeluaran', [PengeluaranController::class, 'store']);
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);
    Route::get('/edit/pengeluaran/{id}', [PengeluaranController::class, 'edit']);
    Route::patch('/pengeluaran/{id}', [PengeluaranController::class, 'update']);

    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::post('/lihat_laporan', [LaporanController::class, 'show'])->name('lihat_laporan');
    Route::post('/export_laporan', [LaporanController::class, 'export']);

    Route::get('/data-forecasting', [ForecastingController::class, 'index']);
    Route::post('/data-forecasting', [ForecastingController::class, 'store']);
    Route::delete('/data-forecasting/{id}', [ForecastingController::class, 'destroy']);
    Route::get('/edit/data-forecasting/{id}', [ForecastingController::class, 'edit']);
    Route::patch('/data-forecasting/{id}', [ForecastingController::class, 'update']);

    Route::get('/forecasting-penerimaan', [ForecastingController::class, 'forecastingPenerimaan']);
    Route::post('/forecasting-penerimaan/prediksi', [ForecastingController::class, 'hitungForecasting']);
    Route::get('/forecasting-pengeluaran', [ForecastingController::class, 'forecastingPengeluaran']);
    Route::post('/forecasting-pengeluaran/prediksi', [ForecastingController::class, 'hitungForecasting']);

    Route::post('/dropdowns/kode-anggaran', [DropdownController::class, 'get_kode_anggaran']);
    Route::post('/dropdowns/sub-kode-anggaran', [DropdownController::class, 'get_sub_kode_anggaran']);
    Route::post('/dropdowns/sub-sub-kode-anggaran', [DropdownController::class, 'get_sub_sub_kode_anggaran']);
    Route::post('/dropdowns/no-sub-kode-anggaran', [DropdownController::class, 'get_no_sub_kode_anggaran']);

    Route::get('/logout', [LoginController::class, 'logout']);
});
