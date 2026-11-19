<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /** Simpan / pindahkan token FCM HP ke petugas yang sedang login. */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string|max:255',
            'platform' => 'nullable|string|max:20',
        ]);

        // updateOrCreate by token: kalau HP dipakai ganti akun, token pindah ke akun baru.
        FcmToken::updateOrCreate(
            ['token' => $request->token],
            ['user_id' => $request->user()->id, 'platform' => $request->platform ?? 'android']
        );

        return response()->json(['success' => true]);
    }

    /** Hapus token (dipanggil app saat logout). */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        FcmToken::where('token', $request->token)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['success' => true]);
    }
}
