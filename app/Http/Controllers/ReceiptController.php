<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            'categories' => 'required|array'
        ]);

        $apiKey = config('services.struk_scan.key');
        $apiUrl = config('services.struk_scan.url');
        $image = $request->file('receipt');
        $categories = $request->get('categories');

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
                // dd($response->json());
                $data = $response->json();
                dd($data);
                return $data;
            }

            return response()->json([
                'error' => 'Gagal scan struk',
                'details' => $response->body()
            ], $response->status());
        } catch (\Exception $e) {
            Log::error("API Error: " . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem'], 500);
        }
    }
}
