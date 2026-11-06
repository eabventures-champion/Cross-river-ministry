<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set New Password — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-6 bg-brand-950 font-sans">
    <div class="w-full max-w-md bg-white rounded-[2px] shadow-lift p-8 space-y-6">
        <div class="text-center space-y-2">
            <img src="{{ asset('images/site/crm-logo.png') }}" alt="Cross Rivers Ministries" class="h-16 w-auto mx-auto">
            <h1 class="font-display text-2xl font-semibold text-ink">Set New Password</h1>
            <p class="text-xs text-slate">Choose a secure password for your administrator account</p>
        </div>

        @if(session('error'))
            <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-[2px] flex items-start gap-2">
                <svg class="size-4 shrink-0 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="form-label" for="email">Administrator Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="form-input bg-slate-50" 
                    value="{{ old('email', $email ?? '') }}" 
                    autocomplete="username">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ show: false }">
                <label class="form-label" for="password">New Password</label>
                <div class="relative mt-1">
                    <input 
                        :type="show ? 'text' : 'password'"
                        type="password"
                        id="password"
                        name="password"
                        required
                        autofocus
                        class="form-input pr-12"
                        placeholder="At least 8 characters"
                        autocomplete="new-password">
                    <button 
                        type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate/70 hover:text-ink focus:outline-none transition-colors"
                        :title="show ? 'Hide password' : 'Show password'"
                        :aria-label="show ? 'Hide password' : 'Show password'">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div x-data="{ show: false }">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <div class="relative mt-1">
                    <input 
                        :type="show ? 'text' : 'password'"
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="form-input pr-12"
                        placeholder="Re-type new password"
                        autocomplete="new-password">
                    <button 
                        type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate/70 hover:text-ink focus:outline-none transition-colors"
                        :title="show ? 'Hide password' : 'Show password'"
                        :aria-label="show ? 'Hide password' : 'Show password'">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3">
                <span>Save New Password</span>
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
