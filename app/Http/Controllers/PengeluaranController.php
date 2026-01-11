<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function show(){
        // $kategori = kategori::all();
        return view('pengeluaran');
    }
}
