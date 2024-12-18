<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store user ID in session for OTP verification
            session(['auth_email' => $request->email]);

            // Generate and send OTP
            $user->generateOTP();

            return redirect()->route('login.otp');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
    }

    public function showOtpForm()
    {
        if (!session('auth_email')) {
            return redirect()->route('login');
        }
        return view('auth.login-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', session('auth_email'))->first();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->verifyOTP($request->otp)) {
            // Clear OTP session
            session()->forget('auth_email');

            // Log the user in
            Auth::login($user);

            // Determine redirect based on user role
            if ($user->role == 1) {
                return redirect()->route('dashboard.index');
            } else {
                return redirect()->route('transaction.index');
            }
        }

        return back()->withErrors([
            'otp' => 'The OTP code is invalid or expired.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        session()->forget('auth_email');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}