<?php

namespace App\Http\Controllers;

use App\Models\kategori_tagihan;
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
            'deskripsi' => 'required|max:255',
        ]);
        try {
            $kategoriTagihan = new kategori_tagihan;
            $kategoriTagihan->id_user = Auth::user()->id;
            $kategoriTagihan->nama = $request->nama;
            $kategoriTagihan->deskripsi = $request->deskripsi;
            $kategoriTagihan->save();
            return redirect()->back()->with('message', 'Kategori tagihan berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Gagal simpan Kategori: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('message', $e->getMessage());
        }
    }
    public function hapus($id)
    {
        try {
            $kategoritagihan = kategori_tagihan::findOrFail($id);
            $kategoritagihan->delete();
            return redirect()->back()->with('message', 'Kategori berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal hapus Kategori: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal Menghapus Kategori, silakan coba lagi');
        }
    }
}
