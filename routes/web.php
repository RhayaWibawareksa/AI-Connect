<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostVoteController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PasswordResetController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Dashboard (handled by PostViewController, which redirects unauthenticated users)
Route::get('/dashboard', [PostViewController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/posts/{id}/vote', [PostVoteController::class, 'vote'])->name('posts.vote');
    Route::post('/posts/{id}/bookmark', [PostVoteController::class, 'bookmark'])->name('posts.bookmark');
    Route::post('/posts/{id}/report', [PostViewController::class, 'report'])->name('posts.report');
    // Notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [NotificationsController::class, 'markRead'])->name('notifications.mark_read');
});

// Password reset (public)
Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// Admin secret route
Route::middleware('auth')->group(function () {
    Route::get('/admin-secret', [AdminDashboardController::class, 'index'])->name('admin.secret');
    Route::post('/admin/unblock/{id}', [AdminDashboardController::class, 'unblock'])->name('admin.unblock');
    // Report moderation actions
    Route::post('/admin/report/{id}/delete-post', [AdminDashboardController::class, 'deleteReportedPost'])->name('admin.report.delete_post');
    Route::post('/admin/report/{id}/ban-user', [AdminDashboardController::class, 'banReportedUser'])->name('admin.report.ban_user');
    Route::post('/admin/report/{id}/dismiss', [AdminDashboardController::class, 'dismissReport'])->name('admin.report.dismiss');
});

// Root route redirects to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Post CRUD Routes (Web)
Route::get('/posts/create', [PostViewController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostViewController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}', [PostViewController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comments', [PostViewController::class, 'storeComment'])->name('posts.comments.store');