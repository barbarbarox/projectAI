<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ========== reCAPTCHA v3 Helper ==========

    protected function verifyRecaptcha(?string $token): bool
    {
        if (empty($token)) return false;
        $secret = env('RECAPTCHA_SECRET_KEY');
        if (empty($secret)) return true; // Skip jika belum dikonfigurasi

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
            ]);

            $data = $response->json();
            return ($data['success'] ?? false) && ($data['score'] ?? 0) >= 0.3;
        } catch (\Exception $e) {
            return false;
        }
    }

    // ========== LOGIN FORM ==========

    public function formMasuk()
    {
        return view('auth.masuk');
    }

    public function prosesMasuk(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA gagal. Muat ulang halaman.',
        ]);

        // Verifikasi reCAPTCHA v3
        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Anda terdeteksi sebagai bot.'])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            if (!Auth::user()->is_verified) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda sedang menunggu persetujuan Admin. Harap bersabar.'])->onlyInput('email');
            }

            $request->session()->regenerate();
            Auth::user()->update(['last_login' => now()]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'login_sukses',
                'description' => 'Pengguna berhasil login menggunakan form email/password.',
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    // ========== REGISTER FORM ==========

    public function formDaftar()
    {
        return view('auth.daftar');
    }

    public function prosesDaftar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'g-recaptcha-response' => 'required',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA gagal.',
        ]);

        // Verifikasi reCAPTCHA v3
        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        // Normalisasi nomor telepon: 08xxx → 628xxx
        $phone = $this->normalizePhone($request->phone);

        // Cek duplikasi nomor telepon
        if (User::where('phone', $phone)->exists()) {
            return back()->withErrors(['phone' => 'Nomor WhatsApp sudah terdaftar.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'last_login' => now(),
            'is_verified' => false, // Set false default
        ]);

        return redirect()->route('menunggu-verifikasi');
    }

    public function menungguVerifikasi()
    {
        return view('auth.menunggu-verifikasi');
    }

    // ========== OTP LOGIN VIA WHATSAPP ==========

    public function formOtp()
    {
        return view('auth.otp');
    }

    public function kirimOtp(Request $request, FonnteService $fonnte)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'g-recaptcha-response' => 'required',
        ], [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA gagal.',
        ]);

        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors(['phone' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
        }

        $phone = $this->normalizePhone($request->phone);
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Nomor WhatsApp tidak terdaftar.'])->withInput();
        }

        if (!$user->is_verified) {
            return back()->withErrors(['phone' => 'Akun Anda sedang menunggu persetujuan Admin. Harap bersabar.'])->withInput();
        }

        // Generate 6 digit OTP
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP via Fonnte WhatsApp
        $sent = $fonnte->sendOtp($phone, $otp);

        if (!$sent) {
            return back()->withErrors(['phone' => 'Gagal mengirim OTP. Pastikan nomor WhatsApp aktif.'])->withInput();
        }

        return redirect()->route('otp.verifikasi', ['phone' => $phone])
            ->with('success', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    public function formVerifikasiOtp(Request $request)
    {
        $phone = $request->query('phone');
        if (empty($phone)) return redirect()->route('otp');

        return view('auth.verifikasi-otp', compact('phone'));
    }

    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $phone = $this->normalizePhone($request->phone);
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Nomor WhatsApp tidak ditemukan.']);
        }

        if (!$user->is_verified) {
            return back()->withErrors(['otp' => 'Akun Anda sedang menunggu persetujuan Admin. Harap bersabar.']);
        }

        if (!$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        if (!Hash::check($request->otp, $user->otp_code)) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // OTP valid — login user
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'last_login' => now(),
        ]);

        Auth::login($user, true);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login_otp_whatsapp',
            'description' => 'Pengguna login via OTP WhatsApp.',
        ]);

        return redirect('/dashboard');
    }

    // ========== GOOGLE OAUTH ==========

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/masuk')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id jika belum ada
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // Buat user baru
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User',
                'nama_lengkap' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'is_verified' => true,
                'last_login' => now(),
            ]);
        }

        $user->update(['last_login' => now()]);
        Auth::login($user, true);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login_google',
            'description' => 'Pengguna login menggunakan akun Google.',
        ]);

        return redirect('/dashboard');
    }

    // ========== LOGOUT ==========

    public function keluar(Request $request)
    {
        $userId = Auth::id();

        if ($userId) {
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'logout',
                'description' => 'Pengguna logout dari sistem.',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ========== HELPER ==========

    /**
     * Normalisasi nomor telepon ke format 628xxx.
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }
}
