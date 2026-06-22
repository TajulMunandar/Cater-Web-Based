<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::with('petugas')->where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.',
            ], 401);
        }

        if (!$user->petugas) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak terdaftar sebagai petugas.',
            ], 403);
        }

        $token = Str::random(60);
        $user->forceFill(['api_token' => $token])->save();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                ],
                'petugas' => [
                    'id' => $user->petugas->id,
                    'nama' => $user->petugas->nama,
                    'nip' => $user->petugas->nip,
                    'no_hp1' => $user->petugas->no_hp1,
                    'photo' => $user->petugas->photo
                        ? asset('storage/' . $user->petugas->photo)
                        : null,
                ],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->forceFill(['api_token' => null])->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('petugas');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                ],
                'petugas' => [
                    'id' => $user->petugas->id,
                    'nama' => $user->petugas->nama,
                    'nip' => $user->petugas->nip,
                    'no_hp1' => $user->petugas->no_hp1,
                    'photo' => $user->petugas->photo
                        ? asset('storage/' . $user->petugas->photo)
                        : null,
                ],
            ],
        ]);
    }
}
