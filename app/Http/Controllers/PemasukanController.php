<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{
    public function show()
    {
        return view('pemasukan');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'tanggal'           => 'required|date|before_or_equal:today',
            'jenis'             => 'required|in:gaji,bonus,penjualan,investasi,lain-lain',
            'total'             => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'            => 'required|in:pending,lunas',
            'deskripsi'         => 'required|string|max:500',
        ]);
        try {
            $pemasukan = new Pemasukan;
            $pemasukan->id_user = Auth::user()->id;
            $pemasukan->tanggal = $request->tanggal;
            $pemasukan->jenis = $request->jenis;
            $pemasukan->total = $request->total;
            $pemasukan->metode_pembayaran = $request->metode_pembayaran;
            $pemasukan->status = $request->status;
            $pemasukan->deskripsi = $request->deskripsi;
            $pemasukan->save();
            return redirect()->back()->with('message', 'Pemasukan Berhasil ditambah');
        } catch (Exception $e) {
            Log::error('Gagal tambah pemasukan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('message', 'Gagal Menambah pemasukan, silakan coba lagi');
        }
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'tanggal'           => 'required|date|before_or_equal:today',
            'jenis'             => 'required|in:gaji,bonus,penjualan,investasi,lain-lain',
            'total'             => 'required|numeric|min:0.01',
            'metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'            => 'required|in:pending,lunas',
            'deskripsi'         => 'required|string|max:500',
        ]);
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->id_user = Auth::user()->id;
            $pemasukan->tanggal = $request->tanggal;
            $pemasukan->jenis = $request->jenis;
            $pemasukan->total = $request->total;
            $pemasukan->metode_pembayaran = $request->metode_pembayaran;
            $pemasukan->status = $request->status;
            $pemasukan->deskripsi = $request->deskripsi;
            $pemasukan->save();
            return redirect()->back()->with('message', 'Pemasukan Berhasil Diedit');
        } catch (Exception $e) {
            Log::error('Gagal edit pemasukan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('message', 'Gagal Mengedit pemasukan, silakan coba lagi');
        }
    }
    public function hapus($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();
            return redirect()->back()->with('message', 'pemasukan berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal hapus pemasukan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('message', 'Gagal Menghapus pemasukan, silakan coba lagi');
        }
    }
}
