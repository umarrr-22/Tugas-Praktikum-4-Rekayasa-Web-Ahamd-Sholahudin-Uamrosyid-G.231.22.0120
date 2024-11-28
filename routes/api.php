<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|-------------------------------------------------------------
| API Routes
|-------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group. Enjoy building your 
| API!
|
*/

// Rute untuk Register
Route::post('/register', [AuthController::class, 'register']);

// Rute untuk Login
Route::post('/login', [AuthController::class, 'login']);

// Rute yang membutuhkan autentikasi menggunakan Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute untuk mendapatkan data pengguna yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
