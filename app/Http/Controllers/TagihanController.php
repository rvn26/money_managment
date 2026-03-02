<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Tagihan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TagihanController extends Controller
{
    public function show()
    {
        return view('tagihan');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_kategori'   => 'required|exists:kategori_tagihans,id',
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric|min:1',
            'jatuh_tempo'   => 'required|date',
            'status'        => 'required|in:belum_dibayar,lunas,terlambat',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'pengulangan'   => 'required|in:sekali_bayar,bulanan,tahunan',
            'catatan'       => 'required|string|max:500',
        ]);


        try {
            $tagihan = new Tagihan;
            $tagihan->id_user = Auth::user()->id;
            $tagihan->kategori = $request->id_kategori;
            $tagihan->nama = $request->nama;
            $tagihan->nominal = $request->nominal;
            $tagihan->jatuh_tempo = $request->jatuh_tempo;
            // dd(Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast());
            if (Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast() && $request->status == 'belum_dibayar') {
                $tagihan->status = 'terlambat';
            } else {
                $tagihan->status = $request->status; // atau status default lainnya
            }
            $tagihan->metode_pembayaran = $request->metode_pembayaran;
            $tagihan->pengulangan = $request->pengulangan;
            $tagihan->catatan = $request->catatan;
            $tagihan->save();
            if ($request->status == 'lunas') {
                $pengeluaran = new Pengeluaran;
                $pengeluaran->id_user = Auth::user()->id;
                $pengeluaran->id_kategori = null;
                $pengeluaran->total = $request->nominal;
                $pengeluaran->tanggal_pengeluaran = Carbon::now(timezone: 'Asia/Jakarta')->format('Y-m-d');
                $pengeluaran->description = $request->catatan;
                $pengeluaran->tujuan = $request->nama;
                $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
                $pengeluaran->status = 'paid';
                $pengeluaran->save();
            }
            return redirect()->back()->with('message', 'Tagihan berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Tagihan gagal ditambahkan');
        }
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'id_kategori'   => 'required|exists:kategori_tagihans,id',
            'nama'          => 'required|min:3',
            'nominal'       => 'required|numeric|min:1',
            'jatuh_tempo'   => 'required|date',
            'status'        => 'required|in:belum_dibayar,lunas,terlambat',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'pengulangan'   => 'required|in:sekali_bayar,bulanan,tahunan',
            'catatan'       => 'required|string|max:500',
        ]);


        try {
            $tagihan = Tagihan::findOrFail($id);
            $tagihan->id_user = Auth::user()->id;
            $tagihan->kategori = $request->id_kategori;
            $tagihan->nama = $request->nama;
            $tagihan->nominal = $request->nominal;
            $tagihan->jatuh_tempo = $request->jatuh_tempo;
            // dd(Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast());
            if (Carbon::parse($request->jatuh_tempo)->timezone('Asia/Jakarta')->endOfDay()->isPast() && $request->status == 'belum_dibayar') {
                $tagihan->status = 'terlambat';
            } else {
                $tagihan->status = $request->status; // atau status default lainnya
            }
            $tagihan->metode_pembayaran = $request->metode_pembayaran;
            $tagihan->pengulangan = $request->pengulangan;
            $tagihan->catatan = $request->catatan;
            $tagihan->save();

            if ($request->status == 'lunas') {
                if ($tagihan->getOriginal('status') !== 'lunas') {
                    $pengeluaran = new Pengeluaran;
                    $pengeluaran->id_user = Auth::user()->id;
                    $pengeluaran->id_kategori = null;
                    $pengeluaran->total = $request->nominal;
                    $pengeluaran->tanggal_pengeluaran = Carbon::now(timezone: 'Asia/Jakarta')->format('Y-m-d');
                    $pengeluaran->description = $request->catatan;
                    $pengeluaran->tujuan = $request->nama;
                    $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
                    $pengeluaran->status = 'paid';
                    $pengeluaran->save();
                }
            }

            return redirect()->back()->with('message', 'Tagihan berhasil diedit');
        } catch (Exception $e) {
            Log::error('Gagal edit Tagihan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Tagihan gagal diedit');
        }
    }

    public function hapus($id)
    {
        try {
            $tagihan = Tagihan::findOrFail($id);
            $tagihan->delete();
            return redirect()->back()->with('message', 'Tagihan berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal simpan Tagihan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal Menghapus Tagihan, silakan coba lagi');
        }
    }
}
