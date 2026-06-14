<?php

use App\Http\Controllers\Api\BatasHarianController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\HutangController;
use App\Http\Controllers\Api\KategoriPengeluaranController;
use App\Http\Controllers\Api\KategoriTagihanController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PemasukanController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\PertemananController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:api', 'verified']);
    Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth:api', 'verified']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'kategori/pengeluaran',
], function () {
    Route::get('/', [KategoriPengeluaranController::class, 'index']);
    Route::get('/{id}', [KategoriPengeluaranController::class, 'show']);
    Route::post('/', [KategoriPengeluaranController::class, 'store']);
    Route::put('/{id}', [KategoriPengeluaranController::class, 'update']);
    Route::delete('/{id}', [KategoriPengeluaranController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'kategori/tagihan',
], function () {
    Route::get('/', [KategoriTagihanController::class, 'index']);
    Route::get('/{id}', [KategoriTagihanController::class, 'show']);
    Route::post('/', [KategoriTagihanController::class, 'store']);
    Route::put('/{id}', [KategoriTagihanController::class, 'update']);
    Route::delete('/{id}', [KategoriTagihanController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'pemasukan',
], function () {
    Route::get('/', [PemasukanController::class, 'index']);
    Route::get('/{id}', [PemasukanController::class, 'show']);
    Route::post('/', [PemasukanController::class, 'store']);
    Route::put('/{id}', [PemasukanController::class, 'update']);
    Route::delete('/{id}', [PemasukanController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'pengeluaran',
], function () {
    Route::get('/', [PengeluaranController::class, 'index']);
    Route::get('/{id}', [PengeluaranController::class, 'show']);
    Route::post('/', [PengeluaranController::class, 'store']);
    Route::put('/{id}', [PengeluaranController::class, 'update']);
    Route::delete('/{id}', [PengeluaranController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'tagihan',
], function () {
    Route::get('/', [TagihanController::class, 'index']);
    Route::get('/{id}', [TagihanController::class, 'show']);
    Route::post('/', [TagihanController::class, 'store']);
    Route::put('/{id}', [TagihanController::class, 'update']);
    Route::delete('/{id}', [TagihanController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'dashboard',
], function () {
    Route::get('/', [DashboardController::class, 'index']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'batas-harian',
], function () {
    Route::get('/', [BatasHarianController::class, 'show']);
    Route::post('/', [BatasHarianController::class, 'store']);
    Route::delete('/', [BatasHarianController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'hutang',
], function () {
    Route::get('/', [HutangController::class, 'index']);
    Route::get('/hutang-saya', [HutangController::class, 'hutangSaya']);
    Route::get('/{id}', [HutangController::class, 'show']);
    Route::post('/', [HutangController::class, 'store']);
    Route::put('/{id}', [HutangController::class, 'update']);
    Route::delete('/{id}', [HutangController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'pertemanan',
], function () {
    Route::get('/', [PertemananController::class, 'index']);
    Route::get('/permintaan-masuk', [PertemananController::class, 'permintaanMasuk']);
    Route::get('/permintaan-terkirim', [PertemananController::class, 'permintaanTerkirim']);
    Route::post('/cari-user', [PertemananController::class, 'cariUser']);
    Route::post('/kirim', [PertemananController::class, 'kirim']);
    Route::put('/terima/{id}', [PertemananController::class, 'terima']);
    Route::delete('/{id}', [PertemananController::class, 'hapus']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'fcm-token',
], function () {
    Route::post('/', [FcmTokenController::class, 'store']);
    Route::delete('/', [FcmTokenController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'notifikasi',
], function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/belum-dibaca', [NotificationController::class, 'belumDibaca']);
    Route::put('/baca-semua', [NotificationController::class, 'bacaSemua']);
    Route::put('/{id}/baca', [NotificationController::class, 'baca']);
});
