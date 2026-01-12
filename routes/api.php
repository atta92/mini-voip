<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
Route::apiResource('posts', PostController::class);
});


Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out']);
});

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'status' => true,
        'user' => $request->user()
    ]);
});