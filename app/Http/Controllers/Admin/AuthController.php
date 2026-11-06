<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminResetPasswordMail;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
            'debugCode' => (config('mail.default') === 'log') ? $user->two_factor_code : null,
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

    public function showForgotPassword(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // If user is not found or not an active admin, respond gracefully without leaking account existence
        if (! $user || ! $user->isAdmin()) {
            return back()->with('status', 'If an administrator account exists for that email, a password reset link has been dispatched.');
        }

        // Generate standard token using Laravel's password broker
        $token = Password::broker()->createToken($user);
        $resetUrl = route('admin.password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            Mail::to($user->email)->send(new AdminResetPasswordMail($user, $resetUrl));
        } catch (\Throwable $e) {
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->with('error', 'Unable to send password reset email. Please try again or contact system support.');
        }

        if (config('mail.default') === 'log') {
            session()->flash('debugResetUrl', $resetUrl);
        }

        return back()->with('status', 'A password reset link has been dispatched to your email address.');
    }

    public function showResetPassword(Request $request, string $token): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('success', 'Your password has been successfully reset! You can now log in with your new credentials.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
