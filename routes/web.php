<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardCtrl;
use App\Http\Controllers\HomepageCtrl;
use App\Http\Controllers\PengaduanCtrl;
use App\Http\Controllers\PerangkatCtrl;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganCtrl;
use App\Http\Controllers\TindakanCtrl;
use App\Http\Controllers\UserCtrl;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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

Route::get('/',[AuthController::class,'form_login'])->name('login');
Route::post('/login/proses', [AuthController::class,'login'])->name('login.proses');
Route::get('/register', [AuthController::class, 'form_register'])->name('register');
Route::post('/register/save', [AuthController::class, 'register'])->name('register.save');
Route::get('/logout',[AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard',[DashboardCtrl::class,'Dashboard']);
Route::get('/homepage',[HomepageCtrl::class,'Homepage']);

Route::get('/user/data_user',[UserCtrl::class,'data_user']);
Route::post('/user/data_user', [UserCtrl::class, 'store']);
Route::post('/user/data_user/{id}/update', [UserCtrl::class, 'update']);
Route::get('/user/data_user/{id}/delete', [UserCtrl::class, 'destroy']);

Route::get('/ruang/data_ruang',[RuanganCtrl::class,'data_ruangan']);
Route::post('/ruang/data_ruang', [RuanganCtrl::class, 'store']);
Route::post('/ruang/data_ruang/{id}/update', [RuanganCtrl::class, 'update']);
Route::post('/ruang/data_ruang/{id}/delete', [RuanganCtrl::class, 'destroy']);
Route::get('/ruang/data_ruang/cari',[RuanganCtrl::class,'data_ruangan']);

Route::get('/perangkat/data_perangkat',[PerangkatCtrl::class,'data_perangkat']);
Route::post('/perangkat/data_perangkat', [PerangkatCtrl::class, 'store']);
Route::post('/perangkat/data_perangkat/{id}/update', [PerangkatCtrl::class, 'update']);
Route::post('/perangkat/data_perangkat/{id}/delete', [PerangkatCtrl::class, 'destroy']);
Route::get('/perangkat/data_perangkat/cari',[PerangkatCtrl::class,'data_perangkat']);

Route::get('/perangkat/qr_png/{id}', [PerangkatCtrl::class, 'qr_png']);

Route::get('/pengaduan/data_pengaduan', [PengaduanCtrl::class, 'data_pengaduan']);
Route::post('/pengaduan/data_pengaduan/{id}/update', [PengaduanCtrl::class, 'update']);
Route::post('/pengaduan/data_pengaduan/{id}/delete', [PengaduanCtrl::class, 'destroy']);
Route::get('/pengaduan/form_pengaduan/{id}', [PengaduanCtrl::class, 'form_pengaduan'])->middleware('auth');
Route::get('/pengaduan/laporan_pengaduan', [PengaduanCtrl::class, 'laporan_pengaduan']);
Route::get('/pengaduan/cetak_pdf', [PengaduanCtrl::class, 'cetak_pdf']);
Route::post('/pengaduan/simpan', [PengaduanCtrl::class, 'simpan_pengaduan'])->middleware('auth');
Route::get('/api/perangkat/ruangan/{id}', function ($id) {
    return DB::table('perangkat')
        ->where('id_ruangan', $id)
        ->select('id', 'kode_perangkat', 'kategori_perangkat', 'merek')
        ->get();
});

Route::get('/wa-redirect', function (Request $request) {
    return view('pengaduan.wa_redirect', [
        'pesan'   => $request->pesan,
        'admin'   => $request->admin,
        'teknisi' => $request->teknisi
    ]);
})->middleware('auth')->name('wa.redirect');

Route::get('/api/perangkat/kode/{kode}', function ($kode) {

    $perangkat = DB::table('perangkat')
        ->join('ruangan','perangkat.id_ruangan','=','ruangan.id')
        ->where('perangkat.kode_perangkat',$kode)
        ->select(
            'perangkat.id',
            'perangkat.kode_perangkat',
            'perangkat.kategori_perangkat',
            'perangkat.merek',
            'ruangan.nama_ruangan as ruangan',
            'ruangan.lokasi'
        )
        ->first();

    return response()->json($perangkat);

})->where('kode', '.*');

Route::get('/tindakan/data_tindakan', [TindakanCtrl::class, 'data_tindakan']);
Route::post('/tindakan/data_tindakan/simpan',   [TindakanCtrl::class, 'store']);
Route::get('/tindakan/tindakan_pengaduan', [TindakanCtrl::class, 'tindakan_pengadu'])->name('tindakan.tindakan_pengaduan');
Route::get('/tindakan/riwayat_tindakan', [TindakanCtrl::class, 'riwayat_tindakan']);