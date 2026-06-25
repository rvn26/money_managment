<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function show()
    {
        return view('ketegori');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'emoji' => 'nullable|string|max:10',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'required|max:255',
        ]);
        try {
            $kategori = new Kategori;
            $kategori->id_user = Auth::user()->id;
            $kategori->nama = $request->nama;
            $kategori->emoji = $request->emoji;
            $kategori->warna = $request->warna;
            $kategori->deskripsi = $request->deskripsi;
            $kategori->save();

            return redirect()->route('kategori')->with('message', 'kategori berhasil ditambahkan');
        } catch (Exception $e) {

            Log::error('Gagal simpan kategori: ' . $e->getMessage());

            return redirect()
                ->route('kategori')
                ->with('message', 'Gagal menyimpan kategori, silakan coba lagi');
        }
    }

    public function hapus($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return redirect()->back()->with('message', 'Kategori berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal simpan Kategori: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal Menghapus Kategori, silakan coba lagi');
        }
    }
}
