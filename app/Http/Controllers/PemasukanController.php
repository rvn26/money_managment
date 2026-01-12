<?php

namespace App\Http\Controllers;

use App\Models\pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $pemasukan = new pemasukan;
        $pemasukan->id_user = Auth::user()->id;
        $pemasukan->tanggal = $request->tanggal;
        $pemasukan->jenis = $request->jenis;
        $pemasukan->total = $request->total;
        $pemasukan->metode_pembayaran = $request->metode_pembayaran;
        $pemasukan->status = $request->status;
        $pemasukan->deskripsi = $request->deskripsi;
        $pemasukan->save();
        return redirect()->back()->with('message','Pemasukan Berhasil ditambah');
    }
}
