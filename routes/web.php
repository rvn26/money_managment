<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriTagihanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TagihanController;
use App\Models\pengeluaran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (){
    Route::get('/kategori',[KategoriController::class,'show'])->name('kategori');
    Route::get('/kategori/tagihan',[KategoriTagihanController::class,'show'])->name('kategori.tagihan');
    Route::post('/kategori/tambah',[KategoriController::class,'simpan'])->name('simpan.kategori');
    Route::post('/kategori/tagihan/tambah',[KategoriTagihanController::class,'simpan'])->name('simpan.kategori.tagihan');
    Route::get('/tagihan',[TagihanController::class,'show'])->name('tagihan');
    Route::post('/tagihan/simpan',[TagihanController::class, 'simpan'])->name('simpan.tagihan');
    Route::put('/tagihan/{id}',[TagihanController::class, 'edit'])->name('edit.tagihan');

    Route::get('/pengeluaran',[PengeluaranController::class,'show'])->name('pengeluaran');
    Route::post('/pengeluaran/simpan',[PengeluaranController::class,'simpan'])->name('simpan.pengeluaran');
    Route::put('/pengeluaran/{id}',[PengeluaranController::class,'edit'])->name('pengeluaran.edit');

    Route::get('/pemasukan',[PemasukanController::class,'show'])->name('pemasukan');
    Route::post('/pemasukan/simpan',[PemasukanController::class,'simpan'])->name('simpan.pemasukan');
    Route::put('/pemasukan/{id}',[PemasukanController::class,'edit'])->name('edit.pemasukan');
});
require __DIR__.'/settings.php';
