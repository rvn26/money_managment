<?php

namespace App\Http\Controllers;

use App\Models\Pertemanan;
use App\Models\User;
use App\Services\FcmService;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PertemananController extends Controller
{
    public function show()
    {
        return view('pertemanan');
    }

    /**
     * Kirim permintaan pertemanan ke pengguna lain berdasarkan email.
     */
    public function kirim(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $user = Auth::user();
            // $userId = Auth::user()->id;
            $teman = User::where('email', $request->email)->firstOrFail();

            if ($teman->id === $user->id) {
                return redirect()->back()->with('error', 'Tidak bisa berteman dengan diri sendiri');
            }

            $sudahAda = Pertemanan::query()
                ->where(function ($q) use ($user, $teman) {
                    $q->where('id_user', $user->id)->where('id_teman', $teman->id);
                })
                ->orWhere(function ($q) use ($user, $teman) {
                    $q->where('id_user', $teman->id)->where('id_teman', $user->id);
                })
                ->exists();

            if ($sudahAda) {
                return redirect()->back()->with('error', 'Permintaan pertemanan sudah ada atau kalian sudah berteman');
            }

            $pertemanan = new Pertemanan;
            $pertemanan->id_user = $user->id;
            $pertemanan->id_teman = $teman->id;
            $pertemanan->status = 'pending';
            $pertemanan->save();

            app(FcmService::class)->sendToUser(
                $teman,
                'Permintaan Pertemanan',
                "{$user->name} mengirim permintaan pertemanan.",
                'pertemanan',
                ['pertemanan_id' => (string) $pertemanan->id, 'aksi' => 'permintaan_masuk']
            );

            return redirect()->back()->with('message', 'Permintaan pertemanan berhasil dikirim');
        } catch (Exception $e) {
            Log::error('Gagal kirim permintaan pertemanan: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim permintaan pertemanan, silakan coba lagi');
        }
    }

    /**
     * Terima permintaan pertemanan yang masuk.
     */
    public function terima($id)
    {
        try {
            $user = Auth::user();
            $pertemanan = Pertemanan::where('id', $id)
                ->where('id_teman', $user->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $pertemanan->status = 'accepted';
            $pertemanan->save();

            $pengirim = User::find($pertemanan->id_user);
            if ($pengirim) {
                app(FcmService::class)->sendToUser(
                    $pengirim,
                    'Pertemanan Diterima',
                    "{$user->name} menerima permintaan pertemanan kamu.",
                    'pertemanan',
                    ['pertemanan_id' => (string) $pertemanan->id, 'aksi' => 'permintaan_diterima']
                );
            }

            return redirect()->back()->with('message', 'Permintaan pertemanan berhasil diterima');
        } catch (Exception $e) {
            Log::error('Gagal terima pertemanan: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menerima permintaan, silakan coba lagi');
        }
    }

    /**
     * Multifungsi: batalkan permintaan terkirim, tolak permintaan masuk,
     * atau hapus teman yang sudah accepted.
     */
    public function hapus($id)
    {
        try {
            $user = Auth::user();
            // $userId = Auth::user()->id;

            $pertemanan = Pertemanan::where('id', $id)
                ->where(function ($q) use ($user) {
                    $q->where('id_user', $user->id)->orWhere('id_teman', $user->id);
                })
                ->firstOrFail();

            $pertemanan->delete();

            return redirect()->back()->with('message', 'Pertemanan berhasil dihapus');
        } catch (Exception $e) {
            Log::error('Gagal hapus pertemanan: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus, silakan coba lagi');
        }
    }
}
