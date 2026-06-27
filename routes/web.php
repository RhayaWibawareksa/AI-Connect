<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostViewController;

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/admin-secret', function () {
    return view('admin');
});

// Root route: show the welcome page to avoid 404 on /
Route::get('/', function () {
    return view('dashboard');
});

// Post CRUD Routes (Web)
Route::get('/posts/create', [PostViewController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostViewController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}', [PostViewController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comments', [PostViewController::class, 'storeComment'])->name('posts.comments.store');