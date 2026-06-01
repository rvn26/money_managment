<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Pertemanan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
     * Halaman "Hutang Saya" — daftar hutang dimana saya yang berhutang ke teman.
     */
    public function hutangSaya()
    {
        return view('hutang-saya');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_teman' => 'nullable|exists:users,id',
            'nama' => 'nullable|max:255|required_without:id_teman',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_pinjaman' => 'required|date',
            'metode_pembayaran' => ['required', Rule::in(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])],
            'catatan' => 'nullable|max:255',
        ]);

        try {
            $userId = Auth::user()->id;

            // Jika id_teman dipilih, pastikan benar-benar teman aktif (status accepted).
            if ($request->filled('id_teman')) {
                $isTeman = Pertemanan::query()
                    ->where('status', 'accepted')
                    ->where(function ($q) use ($userId, $request) {
                        $q->where(function ($qq) use ($userId, $request) {
                            $qq->where('id_user', $userId)
                                ->where('id_teman', $request->id_teman);
                        })->orWhere(function ($qq) use ($userId, $request) {
                            $qq->where('id_user', $request->id_teman)
                                ->where('id_teman', $userId);
                        });
                    })
                    ->exists();

                if (! $isTeman) {
                    return redirect()->back()->with('error', 'Pengguna tersebut bukan teman kamu');
                }
            }
            
            // dd($teman);
            $hutang = new Hutang;
            $hutang->id_user = $userId;
            $hutang->id_teman = $request->id_teman;
            if ($request->id_teman != null) {
                $teman = User::find($request->id_teman)->first();
                $hutang->nama = $teman->name;
            } else {
                $hutang->nama = $request->nama ?: null;
            }
            $hutang->jumlah = $request->jumlah;
            $hutang->tanggal_pinjaman = $request->tanggal_pinjaman;
            $hutang->metode_pembayaran = $request->metode_pembayaran;
            $hutang->status = 'belum_lunas';
            $hutang->catatan = $request->catatan;
            $hutang->save();

            return redirect()->back()->with('message', 'Hutang berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Error adding hutang: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menambahkan hutang, silakan coba lagi');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_pinjaman' => 'required|date',
            'metode_pembayaran' => ['required', Rule::in(['Qris', 'Bank', 'Dana', 'Gopay', 'Cash'])],
            'status' => ['required', Rule::in(['belum_lunas', 'lunas', 'terlambat'])],
            'catatan' => 'nullable|max:255',
        ]);

        try {
            $hutang = Hutang::where('id', $id)
                ->where('id_user', Auth::user()->id)
                ->firstOrFail();

            $hutang->jumlah = $request->jumlah;
            $hutang->tanggal_pinjaman = $request->tanggal_pinjaman;
            $hutang->metode_pembayaran = $request->metode_pembayaran;
            $hutang->status = $request->status;
            $hutang->catatan = $request->catatan;
            $hutang->save();

            return redirect()->back()->with('message', 'Hutang berhasil diperbarui');
        } catch (Exception $e) {
            Log::error('Error updating hutang: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal memperbarui hutang, silakan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $hutang = Hutang::where('id', $id)
                ->where('id_user', Auth::user()->id)
                ->firstOrFail();

            $hutang->delete();

            return redirect()->back()->with('message', 'Hutang berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Error deleting hutang: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus hutang, silakan coba lagi');
        }
    }
}
