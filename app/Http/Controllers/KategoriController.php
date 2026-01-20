<?php

namespace App\Http\Controllers;

use App\Models\kategori;
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
            'nama' => 'required|unique:kategoris,nama|max:255',
            'deskripsi' => 'required|max:255',
        ]);
        try {
            $kategori = new kategori;
            $kategori->id_user = Auth::user()->id;
            $kategori->nama = $request->nama;
            $kategori->deskripsi = $request->deskripsi;
            $kategori->save();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Produk berhasil disimpan',
            //     'redirect_to' => '/produk'
            // ], 201);
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
            $kategori = kategori::findOrFail($id);
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
