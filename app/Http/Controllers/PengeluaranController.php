<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengeluaranController extends Controller
{
    public function show()
    {
        // $kategori = kategori::all();
        return view('pengeluaran');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);
        try {
            $pengeluaran = new pengeluaran;
            $pengeluaran->id_user = Auth::user()->id;
            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->total = $request->total;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();
            return redirect()->back()->with('message', 'pengeluaran berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('message', 'Gagal menyimpan kategori, silakan coba lagi');
        }
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);
        try {
            $pengeluaran = pengeluaran::findOrFail($id);
            $pengeluaran->id_user = Auth::user()->id;
            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->total = $request->total;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();
            return redirect()->back()->with('message', 'pengeluaran berhasil diedit');
        } catch (\Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('message', 'Gagal mengedit Pengeluaran, silakan coba lagi');
        }
    }
}
