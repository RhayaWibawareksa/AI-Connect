<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([ 'email' => 'required|email' ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Alamat email tidak ditemukan.'])->withInput();
        }

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($email));

        // Kirim email sederhana
        try {
            Mail::send('emails.password_reset', ['resetUrl' => $resetUrl, 'user' => $user], function ($m) use ($email) {
                $m->to($email)->subject('Reset Password — AI-Connect');
            });
        } catch (\Exception $e) {
            // Log error but continue to show reset URL in local for testing
            logger()->error('Mail send failed: ' . $e->getMessage());
        }

        // Jika sedang di environment lokal, tampilkan link langsung di UI untuk pengujian
        if (app()->environment('local')) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.')->with('reset_url', $resetUrl);
        }

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->query('email');
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->input('email'))
            ->where('token', $request->input('token'))
            ->first();

        if (! $record) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau telah kadaluarsa.']);
        }

        // Optionally check expiration (e.g., 60 minutes)
        if (strtotime($record->created_at) < strtotime('-60 minutes')) {
            return back()->withErrors(['email' => 'Token reset telah kadaluarsa. Silakan minta link baru.']);
        }

        $user = User::where('email', $request->input('email'))->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Remove reset token
        DB::table('password_resets')->where('email', $request->input('email'))->delete();

        return redirect('/login')->with('success', 'Password berhasil diubah. Silakan masuk dengan password baru.');
    }
}
