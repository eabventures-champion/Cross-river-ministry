<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (! $user->isAdmin()) {
            return back()->withErrors([
                'email' => 'Access denied. You do not have administrator privileges or your account is inactive.',
            ]);
        }

        // Two-Factor Authentication Check
        if ($user->two_factor_enabled) {
            $code = $user->generateTwoFactorCode();

            $request->session()->put('admin_2fa', [
                'user_id' => $user->id,
                'remember' => $request->boolean('remember'),
                'intended' => $request->session()->get('url.intended', route('admin.dashboard')),
            ]);

            try {
                Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code));
            } catch (\Throwable $e) {
                Log::error('Failed to send 2FA verification email: ' . $e->getMessage());
            }

            return redirect()->route('admin.two-factor.show')->with('info', "A 6-digit verification code has been sent to {$user->email}.");
        }

        // Direct Login when 2FA is disabled
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function showTwoFactor(Request $request): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $sessionData = $request->session()->get('admin_2fa');
        if (! $sessionData || ! isset($sessionData['user_id'])) {
            return redirect()->route('admin.login')->with('error', 'Authentication session expired. Please sign in again.');
        }

        $user = User::find($sessionData['user_id']);
        if (! $user) {
            $request->session()->forget('admin_2fa');
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor', [
            'user' => $user,
            'debugCode' => (config('app.debug') || config('mail.default') === 'log') ? $user->two_factor_code : null,
        ]);
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $sessionData = $request->session()->get('admin_2fa');
        if (! $sessionData || ! isset($sessionData['user_id'])) {
            return redirect()->route('admin.login')->with('error', 'Your verification session has expired. Please log in again.');
        }

        $user = User::find($sessionData['user_id']);
        if (! $user) {
            $request->session()->forget('admin_2fa');
            return redirect()->route('admin.login');
        }

        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $cleanCode = str_replace([' ', '-'], '', (string) $request->code);

        if (! $user->isValidTwoFactorCode($cleanCode)) {
            return back()->withErrors([
                'code' => 'The verification code is invalid or has expired. Please check your email or click "Resend Code".',
            ]);
        }

        // Code verified: finalize login
        $user->resetTwoFactorCode();
        $user->update(['last_login_at' => now()]);

        Auth::login($user, $sessionData['remember'] ?? false);
        $request->session()->regenerate();

        $intended = $sessionData['intended'] ?? route('admin.dashboard');
        $request->session()->forget('admin_2fa');

        return redirect()->to($intended)->with('success', 'Two-Factor Authentication verified. Welcome to Cross Rivers Ministries Admin!');
    }

    public function resendTwoFactor(Request $request): RedirectResponse
    {
        $sessionData = $request->session()->get('admin_2fa');
        if (! $sessionData || ! isset($sessionData['user_id'])) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::find($sessionData['user_id']);
        if (! $user) {
            return redirect()->route('admin.login');
        }

        $code = $user->generateTwoFactorCode();

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code));
        } catch (\Throwable $e) {
            Log::error('Failed to resend 2FA verification email: ' . $e->getMessage());
        }

        return back()->with('success', 'A new 6-digit verification code has been dispatched to ' . $user->email);
    }

    public function cancelTwoFactor(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_2fa');
        return redirect()->route('admin.login')->with('info', 'Login cancelled.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'You have been logged out.');
    }
}
