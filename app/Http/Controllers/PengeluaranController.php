<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengeluaran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    public function show()
    {
        // $kategori = Kategori::all();
        return view('pengeluaran');
    }

    public function simpanHasilScan(Request $request)
    {
        $items = json_decode($request->input('items', '[]'), true);

        $validator = Validator::make([
            'items' => $items,
        ], [
            'items' => 'required|array|min:1',
            'items.*.id_kategori' => 'required|integer',
            'items.*.tanggal_pengeluaran' => 'required|date',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string|max:500',
            'items.*.tujuan' => 'required|string|max:255',
            'items.*.metode_pembayaran' => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'items.*.status' => 'required|in:draft,approved,paid',
        ]);

        $validator->validate();

        $allowedKategoriIds = Kategori::where('id_user', Auth::user()->id)
            ->pluck('id')
            ->toArray();

        foreach ($items as $item) {
            if (! in_array((int) $item['id_kategori'], $allowedKategoriIds, true)) {
                return redirect()->back()->with('error', 'Kategori tidak valid.');
            }
        }

        try {
            DB::transaction(function () use ($items) {
                foreach ($items as $item) {
                    $pengeluaran = new Pengeluaran;
                    $pengeluaran->id_user = Auth::user()->id;
                    $pengeluaran->id_kategori = $item['id_kategori'];
                    $pengeluaran->total = $item['total'];
                    $pengeluaran->tanggal_pengeluaran = $item['tanggal_pengeluaran'];
                    $pengeluaran->description = $item['description'] ?? null;
                    $pengeluaran->tujuan = $item['tujuan'];
                    $pengeluaran->metode_pembayaran = $item['metode_pembayaran'];
                    $pengeluaran->status = $item['status'];
                    $pengeluaran->save();
                }
            });

            $request->session()->forget('scan_items');

            return redirect()->route('pengeluaran')->with('message', 'Pengeluaran berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran hasil scan: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan hasil scan, silakan coba lagi');
        }
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);
        try {
            $pengeluaran = new Pengeluaran;
            $pengeluaran->id_user = Auth::user()->id;
            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->total = $request->total;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();
            return redirect()->back()->with('message', 'pengeluaran berhasil ditambahkan');
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan kategori, silakan coba lagi');
        }
    }
    public function edit(Request $request, $id)
    {
        $request->validate([
            'id_kategori'         => 'required|exists:kategoris,id',
            'tanggal_pengeluaran' => 'required|date',
            'total'               => 'required|numeric|min:0',
            'description'         => 'nullable|string|max:500',
            'tujuan'              => 'required|string|max:255',
            'metode_pembayaran'   => 'required|in:Qris,Bank,Dana,Gopay,Cash',
            'status'              => 'required|in:draft,approved,paid',
        ]);
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->id_user = Auth::user()->id;
            $pengeluaran->id_kategori = $request->id_kategori;
            $pengeluaran->total = $request->total;
            $pengeluaran->tanggal_pengeluaran = $request->tanggal_pengeluaran;
            $pengeluaran->description = $request->description;
            $pengeluaran->tujuan = $request->tujuan;
            $pengeluaran->metode_pembayaran = $request->metode_pembayaran;
            $pengeluaran->status = $request->status;
            $pengeluaran->save();
            return redirect()->back()->with('message', 'pengeluaran berhasil diedit');
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal mengedit Pengeluaran, silakan coba lagi');
        }
    }

    public function hapus($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();
            return redirect()->back()->with('message', 'pengeluaran berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal simpan pengeluaran: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal Menghapus Pengeluaran, silakan coba lagi');
        }
    }
}
