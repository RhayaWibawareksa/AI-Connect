<?php

use Illuminate\Support\Facades\Route;

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