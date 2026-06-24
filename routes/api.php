<?php

use App\Http\Controllers\PengaduanCtrl;
use App\Http\Controllers\ApiCtrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/perangkat/cari',       [PengaduanCtrl::class, 'apiCariManual']);
Route::get('/perangkat/kode/{kode}', [PengaduanCtrl::class, 'apiCariByKode']);

Route::post('/update-tindakan', [ApiCtrl::class, 'updateTindakan']);
Route::post('/perangkat/store',   [ApiCtrl::class, 'storePerangkat']);
Route::post('/perangkat/update',  [ApiCtrl::class, 'updatePerangkat']);
Route::post('/perangkat/delete',  [ApiCtrl::class, 'destroyPerangkat']);
Route::post('/perangkat/move',    [ApiCtrl::class, 'movePerangkat']);