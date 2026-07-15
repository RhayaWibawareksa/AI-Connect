<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class GoogleAuthController extends Controller
{
    /**
     * Redirect ke Google OAuth
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari atau buat user
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User sudah exist, update token dan foto
                $user->update([
                    'google_token' => $googleUser->token,
                    'profile_photo_url' => $googleUser->getAvatar(),
                    'last_login_at' => now(),
                ]);
            } else {
                // User baru, create
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'username' => strtolower(str_replace(' ', '_', $googleUser->getName())) . '_' . uniqid(),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'profile_photo_url' => $googleUser->getAvatar(),
                    'password' => bcrypt(uniqid()), // Password random karena login via OAuth
                    'role' => 'user',
                    'last_login_at' => now(),
                ]);
            }

            // Login user
            Auth::login($user);

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil dengan Google!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout berhasil');
    }
}
