<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nis_nip' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'nis_nip' => $request->nis_nip,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil.',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'nis_nip' => 'required|string',
                'password' => 'required'
            ]);

            $user = User::where('name', $validated['name'])
                ->where('nis_nip', $validated['nis_nip'])
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password salah'
                ], 401);
            }

            

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request) {
        try {
            $user = $request->user();

            return response()->json([
                'status' => true,
                'message' => 'Profile user berhasil diambil',
                'data' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request) {
        try {
            $user = $request->user();
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
