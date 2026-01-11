<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (){
    Route::get('/kategori',[KategoriController::class,'show'])->name('kategori');
    Route::get('/pengeluaran',[PengeluaranController::class,'show'])->name('pengeluaran');
});
require __DIR__.'/settings.php';
