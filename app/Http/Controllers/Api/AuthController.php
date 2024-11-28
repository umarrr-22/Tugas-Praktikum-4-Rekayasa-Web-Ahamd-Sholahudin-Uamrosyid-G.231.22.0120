<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        // Jika validasi gagal, return error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Membuat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan response dengan data user dan token
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        // Melakukan autentikasi berdasarkan email dan password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Mendapatkan user berdasarkan email
        $user = User::where('email', $request->email)->firstOrFail();

        // Membuat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan response dengan pesan sukses dan token
        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        // Menghapus semua token yang dimiliki user yang sedang login
        Auth::user()->tokens()->delete();

        // Mengembalikan response dengan pesan logout sukses
        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
