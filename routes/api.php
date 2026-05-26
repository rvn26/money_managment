<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\KategoriPengeluaranController;
use App\Http\Controllers\Api\KategoriTagihanController;
use App\Http\Controllers\Api\PemasukanController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(['auth:api', 'verified']);
    Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth:api', 'verified']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'kategori/pengeluaran'
], function () {
    Route::get('/', [KategoriPengeluaranController::class, 'index']);
    Route::get('/{id}', [KategoriPengeluaranController::class, 'show']);
    Route::post('/', [KategoriPengeluaranController::class, 'store']);
    Route::put('/{id}', [KategoriPengeluaranController::class, 'update']);
    Route::delete('/{id}', [KategoriPengeluaranController::class, 'destroy']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'kategori/tagihan'
], function () {
    Route::get('/', [KategoriTagihanController::class, 'index']);
    Route::get('/{id}', [KategoriTagihanController::class, 'show']);
    Route::post('/', [KategoriTagihanController::class, 'store']);
    Route::put('/{id}', [KategoriTagihanController::class, 'update']);
    Route::delete('/{id}', [KategoriTagihanController::class, 'destroy']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'pemasukan'
], function () {
    Route::get('/', [PemasukanController::class, 'index']);
    Route::get('/{id}', [PemasukanController::class, 'show']);
    Route::post('/', [PemasukanController::class, 'store']);
    Route::put('/{id}', [PemasukanController::class, 'update']);
    Route::delete('/{id}', [PemasukanController::class, 'destroy']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'pengeluaran'
], function () {
    Route::get('/', [PengeluaranController::class, 'index']);
    Route::get('/{id}', [PengeluaranController::class, 'show']);
    Route::post('/', [PengeluaranController::class, 'store']);
    Route::put('/{id}', [PengeluaranController::class, 'update']);
    Route::delete('/{id}', [PengeluaranController::class, 'destroy']);
});


Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'tagihan'
], function () {
    Route::get('/', [TagihanController::class, 'index']);
    Route::get('/{id}', [TagihanController::class, 'show']);
    Route::post('/', [TagihanController::class, 'store']);
    Route::put('/{id}', [TagihanController::class, 'update']);
    Route::delete('/{id}', [TagihanController::class, 'destroy']);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'hutang'
], function () {
    Route::get('/', [TagihanController::class, 'index']);
    Route::get('/{id}', [TagihanController::class, 'show']);
    Route::post('/', [TagihanController::class, 'store']);
    Route::put('/{id}', [TagihanController::class, 'update']);
    Route::delete('/{id}', [TagihanController::class, 'destroy']);
});