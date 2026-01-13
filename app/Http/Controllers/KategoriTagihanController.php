<?php

namespace App\Http\Controllers;

use App\Models\kategori_tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('message', $e->getMessage());
        }
    }
}
