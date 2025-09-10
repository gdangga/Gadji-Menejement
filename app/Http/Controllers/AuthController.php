<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail; 

class AuthController extends Controller {
    public function showRegistrationForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // 2. Cek apakah user ada dan password benar
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        // 3. Generate dan simpan OTP
        $otp = rand(100000, 999999); // Generate 6 digit OTP
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5); // OTP berlaku 5 menit
        $user->save();

        // 4. Kirim OTP ke email user
        Mail::to($user->email)->send(new OtpMail($otp));

        // 5. Simpan ID user di session dan arahkan ke halaman OTP
        session(['user_id_for_otp_verification' => $user->id]);
        return redirect()->route('otp.form');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        // 1. Validasi input OTP
        $request->validate(['otp' => 'required|numeric|digits:6']);

        // 2. Ambil user dari session
        $userId = session('user_id_for_otp_verification');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi Anda telah berakhir, silakan login kembali.']);
        }
        $user = User::find($userId);

        // 3. Cek apakah OTP benar dan belum kedaluwarsa
        if ($user->otp_code !== $request->otp || now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        // 4. Jika berhasil, loginkan user dan bersihkan OTP
        Auth::login($user);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();
        
        session()->forget('user_id_for_otp_verification'); // Hapus session
        $request->session()->regenerate(); // Regenerate session utama

        return redirect()->intended('dashboard');
    }


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}