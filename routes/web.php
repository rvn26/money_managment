<?php

use App\Http\Controllers\BatasHarianController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KategoriTagihanController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TagihanController;
use App\Livewire\Pengeluaran\HasilScan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('livewire.auth.login');
})->name('home');

Route::get('/check-https', fn() => request()->isSecure() ? 'HTTPS' : 'HTTP');
Route::get('/debug-headers', function () {
    return response()->json([
        'is_secure' => request()->isSecure(),
        'scheme' => request()->getScheme(),
        'x_forwarded_proto' => request()->header('x-forwarded-proto'),
        'x_forwarded_ssl' => request()->header('x-forwarded-ssl'),
        'server_https' => $_SERVER['HTTPS'] ?? null,
        'server_port' => $_SERVER['SERVER_PORT'] ?? null,
    ]);
});
Route::get('/test-scan', [ReceiptController::class, 'index']);
Route::post('/scan-receipt', [ReceiptController::class, 'scanReceipt'])->name('scan.receipt');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kategori', [KategoriController::class, 'show'])->name('kategori');
    Route::get('/kategori/tagihan', [KategoriTagihanController::class, 'show'])->name('kategori.tagihan');
    Route::post('/kategori/tambah', [KategoriController::class, 'simpan'])->name('simpan.kategori');
    Route::post('/kategori/tagihan/tambah', [KategoriTagihanController::class, 'simpan'])->name('simpan.kategori.tagihan');
    Route::delete('/kategori/{id}', [KategoriController::class, 'hapus'])->name('hapus.kategori');
    Route::delete('/kategori/tagihan/{id}', [KategoriTagihanController::class, 'hapus'])->name('hapus.kategori.tagihan');

    Route::get('/tagihan', [TagihanController::class, 'show'])->name('tagihan');
    Route::post('/tagihan/simpan', [TagihanController::class, 'simpan'])->name('simpan.tagihan');
    Route::put('/tagihan/{id}', [TagihanController::class, 'edit'])->name('edit.tagihan');
    Route::delete('/tagihan/hapus/{id}', [TagihanController::class, 'hapus'])->name('hapus.tagihan');

    Route::get('/pengeluaran', [PengeluaranController::class, 'show'])->name('pengeluaran');
    Route::get('/pengeluaran/hasil-scan', HasilScan::class)->name('pengeluaran.hasil-scan');
    Route::post('/pengeluaran/hasil-scan/simpan', [PengeluaranController::class, 'simpanHasilScan'])->name('pengeluaran.hasil-scan.simpan');
    Route::post('/pengeluaran/simpan', [PengeluaranController::class, 'simpan'])->name('simpan.pengeluaran');
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::delete('/pengeluaran/hapus/{id}', [PengeluaranController::class, 'hapus'])->name('pengeluaran.hapus');

    Route::get('/pemasukan', [PemasukanController::class, 'show'])->name('pemasukan');
    Route::post('/pemasukan/simpan', [PemasukanController::class, 'simpan'])->name('simpan.pemasukan');
    Route::put('/pemasukan/{id}', [PemasukanController::class, 'edit'])->name('edit.pemasukan');
    Route::delete('/pemasukan/hapus/{id}', [PemasukanController::class, 'hapus'])->name('hapus.pemasukan');

    Route::post('/batas/simpan', [BatasHarianController::class, 'simpan'])->name('simpan.batas');
    Route::put('/batas/{id}', [BatasHarianController::class, 'edit'])->name('edit.batas');
});
require __DIR__ . '/settings.php';
