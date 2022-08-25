<?php

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

Route::get('/', function () {
    return view('pages/dashboard');
});
Route::get('/user', function () {
    return view('pages/user');
});
Route::get('/akun', function () {
    return view('pages/akun');
});
Route::get('/sub-akun', function () {
    return view('pages/sub_akun');
});
Route::get('/sub sub-akun', function () {
    return view('pages/sub_sub_akun');
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
