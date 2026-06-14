<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FcmTokenController extends Controller
{
    /**
     * Simpan atau update FCM device token untuk user yang sedang login.
     * Dipanggil oleh Flutter saat app pertama kali dibuka atau token berubah.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Upsert: update jika sudah ada, insert jika belum
            $fcmToken = FcmToken::updateOrCreate(
                [
                    'id_user' => $user->id,
                    'token' => $request->token,
                ],
                [
                    'device_name' => $request->device_name,
                ]
            );

            return $this->sendResponse($fcmToken, 'FCM token berhasil disimpan.');
        } catch (Exception $e) {
            Log::error('Gagal menyimpan FCM token: '.$e->getMessage());

            return $this->sendError('Gagal menyimpan FCM token.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus FCM device token saat user logout dari aplikasi Flutter.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();

            FcmToken::where('id_user', $user->id)
                ->where('token', $request->token)
                ->delete();

            return $this->sendResponse([], 'FCM token berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus FCM token: '.$e->getMessage());

            return $this->sendError('Gagal menghapus FCM token.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send a success response.
     */
    protected function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'statuscode' => $code,
            'msg' => $message,
            'data' => $result,
        ], $code);
    }

    /**
     * Send an error response.
     */
    protected function sendError($error, $errorMessages = [], $code = 401)
    {
        return response()->json([
            'statuscode' => $code,
            'msg' => $error,
            'data' => $errorMessages,
        ], $code);
    }
}
