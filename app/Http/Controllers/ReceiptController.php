<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ReceiptController extends Controller
{

    public function index()
    {
        return view('scan');
    }
    public function scanReceipt(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $apiKey = config('services.struk_scan.key');
        $apiUrl = config('services.struk_scan.url');
        $image = $request->file('receipt');
        $userId = Auth::id();
        $categories = Kategori::where('id_user', $userId)->pluck('nama')->toArray();
        if (empty($categories)) {
            throw ValidationException::withMessages([
                'receipt' => 'Kamu harus membuat minimal satu Kategori Pengeluaran terlebih dahulu sebelum melakukan scan.'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
            ])
                ->attach(
                    'receipt',
                    file_get_contents($image),
                    $image->getClientOriginalName()
                )
                ->post($apiUrl, [
                    'categories' => implode(', ', $categories),
                ]);

                
            if ($response->successful()) {
                $data = $response->json();

                if (($data['success'] ?? false) && empty($data['data'])) {
                    return response()->json([
                        'error' => 'Item di struk gagal di deteksi',
                    ], 422);
                }

                return response()->json($data);
            }

            return response()->json([
                'error' => 'Gagal scan struk',
                'details' => $response->body(),
            ], $response->status());
        } catch (\Exception $e) {
            Log::error("API Error: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem'], 500);
        }
    }
}
