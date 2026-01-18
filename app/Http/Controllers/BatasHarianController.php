<?php

namespace App\Http\Controllers;

use App\Models\batas_harian;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatasHarianController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'batas' => 'required|numeric|min:0.01',
        ]);
        try {
            $batasHarian = new batas_harian;
            $batasHarian->id_user = Auth::user()->id;
            $batasHarian->batas = $request->batas;
            $batasHarian->save();
            return redirect()->back()->with('message', 'Batas Harian Berhasil Diset');
        } catch (Exception $e) {
            return redirect()->back()->with('message', 'Batas Harian Gagal Diset');
        }
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'batas' => 'required|numeric|min:0.01',
        ]);
        try {
            $batasHarian = batas_harian::findOrFail($id);
            $batasHarian->id_user = Auth::user()->id;
            $batasHarian->batas = $request->batas;
            $batasHarian->save();
            return redirect()->back()->with('message', 'Batas Harian Berhasil Diset');
        } catch (Exception $e) {
            return redirect()->back()->with('message', 'Batas Harian Gagal Diset');
        }
    }
}
