<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KodeController;
use App\Http\Controllers\UserController;
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

Route::get('/', [DashboardController::class, 'index']);

Route::get('/user', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'store']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::get('edit/user/{id}', [UserController::class, 'edit']);
Route::patch('user/{id}', [UserController::class, 'update']);

Route::get('/kode', [KodeController::class, 'index']);
Route::post('/kode', [KodeController::class, 'store']);
Route::delete('/kode/{id}', [KodeController::class, 'destroy']);
Route::get('edit/kode/{id}', [KodeController::class, 'edit']);
Route::patch('/kode/{id}', [KodeController::class, 'update']);


// Route::get('/kode', function () {
//     return view('pages/kode');
// });
Route::get('/sub-kode', function () {
    return view('pages/sub_kode');
});
Route::get('/sub sub-kode', function () {
    return view('pages/sub_sub_kode');
});
Route::get('/penerimaan', function () {
    return view('pages/penerimaan');
});
Route::get('/pengeluaran', function () {
    return view('pages/pengeluaran');
});
Route::get('/akun-bank', function () {
    return view('pages/akun_bank');
});
