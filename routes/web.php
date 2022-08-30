<?php

use App\Http\Controllers\AkunBankController;
use App\Http\Controllers\DanaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KodeController;
use App\Http\Controllers\SubKodeController;
use App\Http\Controllers\SubSubKodeController;
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

Route::get('/sub-kode', [SubKodeController::class, 'index']);
Route::post('/sub-kode', [SubKodeController::class, 'store']);
Route::delete('/sub-kode/{id}', [SubKodeController::class, 'destroy']);
Route::get('edit/sub-kode/{id}', [SubKodeController::class, 'edit']);
Route::patch('/sub-kode/{id}', [SubKodeController::class, 'update']);

Route::get('/sub-sub-kode', [SubSubKodeController::class, 'index']);
Route::post('/sub-sub-kode', [SubSubKodeController::class, 'store']);
Route::delete('/sub-sub-kode/{id}', [SubSubKodeController::class, 'destroy']);
Route::get('edit/sub-sub-kode/{id}', [SubSubKodeController::class, 'edit']);
Route::patch('/sub-sub-kode/{id}', [SubSubKodeController::class, 'update']);

Route::get('/akun-bank', [AkunBankController::class, 'index']);
Route::post('/akun-bank', [AkunBankController::class, 'store']);
Route::delete('/akun-bank/{id}', [AkunBankController::class, 'destroy']);
Route::get('/edit/akun-bank/{id}', [AkunBankController::class, 'edit']);
Route::patch('/akun-bank/{id}', [AkunBankController::class, 'update']);
