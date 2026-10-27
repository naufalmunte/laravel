<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Login Google gagal. Silakan coba kembali.',
                ]);
        }

        if (!$googleUser->getEmail()) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google tidak memberikan alamat email.',
                ]);
        }

        if (($googleUser->user['email_verified'] ?? false) !== true) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Email Google harus terverifikasi.',
                ]);
        }

        $email = Str::lower($googleUser->getEmail());
        $googleId = $googleUser->getId();

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        $isNewUser = false;

        if ($user) {
            // Akun admin tidak boleh login melalui jalur pelanggan
            if ($user->role !== 'pelanggan') {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun ini tidak memiliki akses pelanggan.',
                    ]);
            }

            // Hubungkan akun lama dengan Google
            if (!$user->google_id) {
                $user->google_id = $googleId;
                $user->email_verified_at ??= now();
                $user->save();
            }
        } else {
            // Buat akun pelanggan baru tanpa password lokal
            $user = DB::transaction(function () use ($googleUser, $email, $googleId) {
                return User::create([
                    'name' => $googleUser->getName() ?: 'Pelanggan',
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => null,
                    'role' => 'pelanggan',
                    'email_verified_at' => now(),
                ]);
            });

            $isNewUser = true;
        }

        Auth::login($user);
        request()->session()->regenerate();

        if ($isNewUser) {
            return redirect('/')
                ->with('success', 'Pendaftaran Google berhasil! Kamu sudah login dan bisa mulai berbelanja.');
        }

        return redirect('/')
            ->with('success', 'Berhasil login dengan Google. Selamat berbelanja!');
    }
}
