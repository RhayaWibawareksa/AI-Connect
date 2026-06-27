<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

// Route untuk user (bawaan Laravel)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk data postingan (digunakan oleh front-end Aryan)
Route::get('/posts', [PostController::class, 'index']);      // List semua post
Route::get('/posts/{id}', [PostController::class, 'show']);  // Detail satu post