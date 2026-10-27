<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationOtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class PelangganAuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $email = Str::lower(trim($validated['email']));
        $noWhatsapp = trim($validated['no_whatsapp']);

        // Periksa email dan nomor WhatsApp yang sudah terdaftar.
        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah terdaftar. Silakan login.',
            ]);
        }

        if (User::where('no_whatsapp', $noWhatsapp)->exists()) {
            throw ValidationException::withMessages([
                'no_whatsapp' => 'Nomor WhatsApp sudah terdaftar.',
            ]);
        }

        $key = 'registration-otp:' . $email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak permintaan OTP. Coba lagi setelah 10 menit.',
            ]);
        }

        $code = (string) random_int(100000, 999999);

        // Simpan data pendaftaran dan OTP.
        $otp = EmailOtp::updateOrCreate(
            ['email' => $email],
            [
                'code_hash' => Hash::make($code),
                'registration_data' => [
                    'name' => $validated['name'],
                    'email' => $email,
                    'no_whatsapp' => $noWhatsapp,
                    'password' => Hash::make($validated['password']),
                ],
                'expires_at' => now()->addMinutes(5),
                'attempts' => 0,
            ]
        );

        try {
            Mail::to($email)->send(new RegistrationOtpMail($code));
        } catch (\Throwable $e) {
            report($e);

            $otp->delete();

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->withErrors([
                    'email' => 'Email OTP gagal dikirim. Silakan coba lagi.',
                ]);
        }

        RateLimiter::hit($key, 600);

        // Simpan email agar tetap tersedia pada halaman verifikasi.
        session(['otp_email' => $email]);

        return redirect()
            ->route('register.verify')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showVerify()
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Sesi pendaftaran tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        // Pastikan data OTP masih tersedia.
        if (!EmailOtp::where('email', $email)->exists()) {
            session()->forget('otp_email');

            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Data pendaftaran tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        return view('auth.verify-otp', [
            'email' => $email,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = session('otp_email');

        if (!$email) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Sesi pendaftaran tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        $email = Str::lower($email);
        $key = 'verify-otp:' . $email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'otp' => 'Terlalu banyak percobaan. Silakan coba lagi dalam satu menit.',
            ]);
        }

        $otp = EmailOtp::where('email', $email)->first();

        if (!$otp) {
            session()->forget('otp_email');

            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Data pendaftaran tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        if ($otp->expires_at->isPast()) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan daftar ulang untuk mendapat kode baru.',
            ]);
        }

        if ($otp->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp' => 'Batas percobaan OTP telah tercapai. Silakan kirim ulang kode.',
            ]);
        }

        if (!Hash::check($request->otp, $otp->code_hash)) {
            $otp->increment('attempts');
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'otp' => 'Kode OTP tidak sesuai.',
            ]);
        }

        try {
            $user = DB::transaction(function () use ($otp, $email) {
                // Ambil ulang data OTP di dalam transaksi.
                $otp = EmailOtp::whereKey($otp->id)
                    ->lockForUpdate()
                    ->first();

                if (!$otp || $otp->expires_at->isPast()) {
                    throw ValidationException::withMessages([
                        'otp' => 'Kode OTP sudah kedaluwarsa atau tidak ditemukan.',
                    ]);
                }

                $data = $otp->registration_data;

                logger()->info('Pemeriksaan data registrasi OTP', [
                    'otp_id' => $otp->id,
                    'email' => $email,
                    'registration_data_is_array' => is_array($data),
                    'registration_data_keys' => is_array($data)
                        ? array_keys($data)
                        : [],
                    'has_name' => is_array($data) && !empty($data['name']),
                    'has_email' => is_array($data) && !empty($data['email']),
                    'has_no_whatsapp' => is_array($data) && !empty($data['no_whatsapp']),
                    'has_password' => is_array($data) && !empty($data['password']),
                ]);

                if (
                    !is_array($data)
                    || empty($data['name'])
                    || empty($data['email'])
                    || empty($data['no_whatsapp'])
                    || empty($data['password'])
                ) {
                    throw ValidationException::withMessages([
                        'email' => 'Data pendaftaran tidak lengkap. Silakan daftar kembali.',
                    ]);
                }

                if (
                    User::where('email', $email)->exists()
                    || User::where('no_whatsapp', $data['no_whatsapp'])->exists()
                ) {
                    throw ValidationException::withMessages([
                        'email' => 'Email atau nomor WhatsApp sudah terdaftar. Silakan login.',
                    ]);
                }

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $email,
                    'no_whatsapp' => $data['no_whatsapp'],
                    'password' => $data['password'],
                    'role' => 'pelanggan',
                    'email_verified_at' => now(),
                ]);

                $otp->delete();

                return $user;
            });
        } catch (QueryException $e) {
            // Antisipasi jika email/nomor WhatsApp terdaftar bersamaan
            // dari proses lain.
            if (
                User::where('email', $email)->exists()
                || User::where('no_whatsapp', session('otp_no_whatsapp'))->exists()
            ) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Email atau nomor WhatsApp sudah terdaftar. Silakan login.',
                    ]);
            }

            report($e);

            return back()->withErrors([
                'otp' => 'Pendaftaran gagal disimpan. Silakan coba kembali.',
            ]);
        }

        RateLimiter::clear($key);
        RateLimiter::clear('registration-otp:' . $email);

        session()->forget('otp_email');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')
            ->with('success', 'Email berhasil diverifikasi. Selamat berbelanja di Khas Minang!');
    }

    public function resendOtp(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Sesi pendaftaran tidak ditemukan. Silakan daftar kembali.',
                ]);
        }

        $email = Str::lower($email);
        $key = 'resend-otp:' . $email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            throw ValidationException::withMessages([
                'otp' => 'Batas kirim ulang tercapai. Coba lagi setelah 10 menit.',
            ]);
        }

        $otp = EmailOtp::where('email', $email)->first();

        if (!$otp) {
            session()->forget('otp_email');

            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'Data pendaftaran tidak ditemukan. Silakan daftar ulang.',
                ]);
        }

        $code = (string) random_int(100000, 999999);

        $otp->update([
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        try {
            Mail::to($email)->send(new RegistrationOtpMail($code));
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'otp' => 'OTP gagal dikirim. Silakan coba lagi nanti.',
            ]);
        }

        RateLimiter::hit($key, 600);

        return back()->with('success', 'Kode OTP baru berhasil dikirim.');
    }

    public function login()
    {
        return view('auth.login');
    }


public function storeLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors([
                'email' => 'Email atau password yang dimasukkan salah.',
            ])
            ->onlyInput('email');
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Owner dan Admin diarahkan ke panel admin.
    if ($user->hasRole('owner', 'admin')) {
        return redirect()->route('admin.dashboard');
    }

    // Pelanggan diarahkan ke halaman toko.
    if ($user->isPelanggan()) {
        return redirect()->intended('/');
    }

    // Tolak akun dengan role yang tidak dikenal.
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')
        ->withErrors([
            'email' => 'Role akun tidak dikenali. Silakan hubungi administrator.',
        ]);
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Kamu berhasil logout.');
    }
}
