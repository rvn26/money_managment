<?php

namespace App\Http\Controllers;

use App\Models\KategoriTagihan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KategoriTagihanController extends Controller
{
    public function show()
    {
        return view('kategori-tagihan');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategori_tagihans,nama|max:255',
            'emoji' => 'nullable|string|max:10',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'required|max:255',
        ]);
        try {
            $kategoriTagihan = new KategoriTagihan;
            $kategoriTagihan->id_user = Auth::user()->id;
            $kategoriTagihan->nama = $request->nama;
            $kategoriTagihan->emoji = $request->emoji;
            $kategoriTagihan->warna = $request->warna;
            $kategoriTagihan->deskripsi = $request->deskripsi;
            $kategoriTagihan->save();

            return redirect()->back()->with('message', 'Kategori tagihan berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Gagal simpan Kategori: '.$e->getMessage());

            return redirect()
                ->back()
                ->with('message', $e->getMessage());
        }
    }

    public function hapus($id)
    {
        try {
            $kategoritagihan = KategoriTagihan::findOrFail($id);
            $kategoritagihan->delete();

            return redirect()->back()->with('message', 'Kategori berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal hapus Kategori: '.$e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal Menghapus Kategori, silakan coba lagi');
        }
    }
}
