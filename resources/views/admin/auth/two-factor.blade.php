<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two-Factor Authentication — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 bg-brand-950 font-sans">
    <div class="w-full max-w-md bg-white rounded-[2px] shadow-lift p-6 sm:p-8 space-y-6">
        <!-- Brand Header -->
        <div class="text-center space-y-3">
            <img src="{{ asset('images/site/crm-logo.png') }}" alt="Cross Rivers Ministries" class="h-16 w-auto mx-auto">
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 border border-brand-200 text-brand-600 text-xs font-semibold">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span>Two-Factor Authentication</span>
            </div>

            <h1 class="font-display text-2xl font-semibold text-ink">Enter Verification Code</h1>
            
            <p class="text-xs text-slate max-w-sm mx-auto leading-relaxed">
                A 6-digit verification code has been dispatched to <strong class="text-ink font-semibold">{{ $user->email }}</strong>.
            </p>
        </div>

        @if(session('info'))
            <div class="p-3 bg-brand-50 border border-brand-200 text-brand-700 text-xs rounded-[2px] flex items-center gap-2">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-xs rounded-[2px]">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-[2px]">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($debugCode) && $debugCode)
            <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-[2px] flex items-center justify-between">
                <div>
                    <span class="font-bold">Development Code:</span>
                    <span class="font-mono tracking-widest font-semibold ml-1.5">{{ $debugCode }}</span>
                </div>
                <span class="text-[0.625rem] uppercase text-amber-600 font-semibold">(Mail Log Active)</span>
            </div>
        @endif

        <!-- Code Verification Form -->
        <form action="{{ route('admin.two-factor.verify') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="form-label text-center block" for="2fa_code">6-Digit Security Code</label>
                <div class="mt-2">
                    <input 
                        type="text" 
                        id="2fa_code"
                        name="code" 
                        required 
                        autofocus
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="8"
                        autocomplete="one-time-code"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;"
                        class="form-input text-center font-mono text-2xl font-bold tracking-[0.35em] py-3.5"
                    >
                </div>
                @error('code')
                    <p class="form-error text-center mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[0.6875rem] text-slate-400 text-center mt-2">Code expires in 10 minutes</p>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3.5 font-semibold text-sm">
                <span>Verify & Sign In</span>
            </button>
        </form>

        <!-- Actions: Resend & Cancel -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <form action="{{ route('admin.two-factor.resend') }}" method="POST">
                @csrf
                <button type="submit" class="font-semibold text-brand-500 hover:text-brand-700 hover:underline">
                    Resend Code
                </button>
            </form>

            <form action="{{ route('admin.two-factor.cancel') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-ink hover:underline">
                    Cancel & Sign Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
