<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-6 bg-brand-950 font-sans">
    <div class="w-full max-w-md bg-white rounded-[2px] shadow-lift p-8 space-y-6">
        <div class="text-center space-y-2">
            <img src="{{ asset('images/site/crm-logo.png') }}" alt="Cross Rivers Ministries" class="h-16 w-auto mx-auto">
            <h1 class="font-display text-2xl font-semibold text-ink">Reset Password</h1>
            <p class="text-xs text-slate">Enter your administrator email address and we'll send you a password reset link.</p>
        </div>

        @if(session('status'))
            <div class="p-3.5 bg-green-50 border border-green-200 text-green-800 text-xs rounded-[2px] flex items-start gap-2">
                <svg class="size-4 shrink-0 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-[2px] flex items-start gap-2">
                <svg class="size-4 shrink-0 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('debugResetUrl'))
            <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-900 text-xs rounded-[2px] space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-amber-800">Development Reset Link:</span>
                    <span class="text-[0.625rem] uppercase font-semibold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">Mail Log Active</span>
                </div>
                <div class="break-all font-mono text-[11px] bg-white/70 p-2 rounded border border-amber-200 text-slate">
                    <a href="{{ session('debugResetUrl') }}" class="text-brand-600 hover:text-brand-700 underline font-semibold">
                        Click here to open the reset form
                    </a>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="form-label" for="email">Administrator Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    autofocus 
                    class="form-input" 
                    placeholder="admin@crossriversministries.com" 
                    value="{{ old('email', request('email', 'admin@crossriversministries.com')) }}">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn btn-primary w-full py-3">
                <span>Send Reset Link</span>
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate hover:text-brand-600 transition-colors">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Back to Sign In</span>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
