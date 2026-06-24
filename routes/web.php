<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardCtrl;
use App\Http\Controllers\HomepageCtrl;
use App\Http\Controllers\KategoriCtrl;
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
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'form_login'])->name('login');
Route::post('/login/proses', [AuthController::class, 'login'])->name('login.proses');
Route::get('/register', [AuthController::class, 'form_register'])->name('register');
Route::post('/register/save', [AuthController::class, 'register'])->name('register.save');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardCtrl::class, 'Dashboard']);
        Route::get('/user/data_user', [UserCtrl::class, 'data_user']);
        Route::post('/user/data_user', [UserCtrl::class, 'store']);
        Route::post('/user/data_user/{id}/update', [UserCtrl::class, 'update']);
        Route::get('/user/data_user/{id}/delete', [UserCtrl::class, 'destroy']);

        Route::get('/ruang/data_ruang', [RuanganCtrl::class, 'data_ruangan']);
        Route::post('/ruang/data_ruang', [RuanganCtrl::class, 'store']);
        Route::post('/ruang/data_ruang/{id}/update', [RuanganCtrl::class, 'update']);
        Route::post('/ruang/data_ruang/{id}/delete', [RuanganCtrl::class, 'destroy']);
        Route::get('/ruang/data_ruang/cari', [RuanganCtrl::class, 'data_ruangan']);

        Route::get('/kategori_perangkat/data_kategori', [KategoriCtrl::class, 'data_kategori']);
        Route::post('/kategori_perangkat/data_kategori', [KategoriCtrl::class, 'store']);
        Route::post('/kategori_perangkat/data_kategori/{id}/update', [KategoriCtrl::class, 'update']);
        Route::post('/kategori_perangkat/data_kategori/{id}/delete', [KategoriCtrl::class, 'destroy']);

        Route::get('/pengaduan/laporan_pengaduan', [PengaduanCtrl::class, 'laporan_pengaduan']);
        Route::get('/pengaduan/cetak_pdf', [PengaduanCtrl::class, 'cetak_pdf']);
        Route::get('/pengaduan/riwayat_pengaduan', [PengaduanCtrl::class, 'riwayat_pengaduan']);
        Route::get('/pengaduan/export_excel', [PengaduanCtrl::class, 'export_excel']);

        
        Route::get('/perangkat/data_perangkat', [PerangkatCtrl::class, 'data_perangkat']);
        Route::post('/perangkat/data_perangkat', [PerangkatCtrl::class, 'store']);
        Route::post('/perangkat/data_perangkat/{id}/update', [PerangkatCtrl::class, 'update']);
        Route::post('/perangkat/data_perangkat/{id}/delete', [PerangkatCtrl::class, 'destroy']);
        Route::get('/perangkat/data_perangkat/cari', [PerangkatCtrl::class, 'data_perangkat']);
    });

    Route::middleware(['role:pengadu'])->group(function () {

        Route::get('/pengaduan/form_pengaduan/{id}', [PengaduanCtrl::class, 'form_pengaduan']);
        Route::post('/pengaduan/simpan', [PengaduanCtrl::class, 'simpan_pengaduan']);
        Route::get('/api/perangkat/kode/{kode}', function (Request $request, $kode) {

        $perangkat = DB::table('perangkat')
            ->join('ruangan', 'perangkat.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('kategori_perangkat', 'kategori_perangkat.id_kategori', '=', 'perangkat.id_kategori')
            ->where('perangkat.kode_inventaris', $kode)
            ->select(
                'perangkat.id_perangkat as id',
                'perangkat.kode_inventaris',
                'perangkat.merek',
                'perangkat.alamat_ip',
                'perangkat.id_ruangan',
                'kategori_perangkat.nama_kategori as kategori_perangkat',
                'ruangan.nama_ruangan',
                'ruangan.lokasi'
            )
            ->first();

        if (!$perangkat) {
            return response()->json(['error' => 'not_found'], 404);
        }

        if ($request->filled('id_ruangan')) {
            if ((int) $request->id_ruangan !== (int) $perangkat->id_ruangan) {
                return response()->json(['error' => 'wrong_room'], 403);
            }
        }

        return response()->json($perangkat);

        })->where('kode', '.*');

        Route::get('/test-wa', function () {

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target'  => env('WA_GROUP'),
                'message' => 'TEST NOTIFIKASI WA GROUP 🔥'
            ]);

            dd([
                'group' => env('WA_GROUP'),
                'token' => env('FONNTE_TOKEN'),
                'response' => $response->json()
            ]);
        });

        Route::get('/group-list', function () {

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->get('https://api.fonnte.com/fetch-group');

            dd($response->json());
        });

        Route::get('/tindakan/tindakan_pengaduan', [TindakanCtrl::class, 'tindakan_pengaduan'])->name('tindakan.tindakan_pengaduan');

        Route::get('/api/perangkat/ruangan/{id}', function ($id) {
            return DB::table('perangkat')
                ->where('id_ruangan', $id)
                ->select('id', 'kode_perangkat', 'kategori_perangkat', 'merek')
                ->get();
        });

    });

    Route::get('/pengaduan/data_pengaduan', [PengaduanCtrl::class, 'data_pengaduan']);
    Route::post('/pengaduan/data_pengaduan/{id}/update', [PengaduanCtrl::class, 'update']);
    Route::post('/pengaduan/data_pengaduan/{id}/delete', [PengaduanCtrl::class, 'destroy']);

    Route::get('/perangkat/qr_png/{id}', [PerangkatCtrl::class, 'qr_png']);

    Route::get('/tindakan/data_tindakan', [TindakanCtrl::class, 'data_tindakan']);
});