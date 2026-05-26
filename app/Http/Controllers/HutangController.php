<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateHutangRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('hutang');  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'jumlah' => 'required|numeric',
            'tanggal_pinjaman' => 'required|date',
            'catatan' => 'nullable|max:255',
        ]);

        try {
            $hutang = new Hutang;
            $hutang->id_user = Auth::user()->id;
            $hutang->nama = $request->nama;
            $hutang->jumlah = $request->jumlah;
            $hutang->tanggal_pinjaman = $request->tanggal_pinjaman;
            $hutang->status = 'belum_lunas';
            $hutang->catatan = $request->catatan;
            $hutang->save();

            return redirect()->back()->with('message', 'Hutang berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error adding hutang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan hutang, silakan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hutang $hutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hutang $hutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHutangRequest $request, Hutang $hutang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hutang $hutang)
    {
        //
    }
}
