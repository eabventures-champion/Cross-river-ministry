<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accept Staff Invitation — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-6 bg-brand-950 font-sans">
    <div class="w-full max-w-lg bg-white rounded-[2px] shadow-lift p-8 space-y-6">
        <div class="text-center space-y-2">
            <img src="{{ asset('images/site/crm-logo.png') }}" alt="Cross Rivers Ministries" class="h-16 w-auto mx-auto">
            <span class="inline-block text-[0.6875rem] px-2.5 py-0.5 rounded-[2px] font-semibold border {{ $roleDetails['badge'] ?? 'bg-slate-100' }}">
                {{ $roleDetails['label'] ?? 'Staff Member' }}
            </span>
            <h1 class="font-display text-2xl font-semibold text-ink">Welcome to the CRM Team</h1>
            <p class="text-xs text-slate">You have been invited to collaborate on the Cross Rivers Ministries administration dashboard.</p>
        </div>

        <div class="p-4 bg-brand-50 border border-brand-200 rounded-[2px] text-xs text-brand-900 flex items-center justify-between">
            <div>
                <span class="text-slate-500 block">Invited Email:</span>
                <strong class="font-semibold text-ink">{{ $invitation->email }}</strong>
            </div>
            <div class="text-right">
                <span class="text-slate-500 block">Invited by:</span>
                <span class="font-medium text-slate-700">{{ $invitation->inviter->name ?? 'Leadership' }}</span>
            </div>
        </div>

        <form action="{{ route('admin.invitations.process', $invitation->token) }}" method="POST" class="space-y-4" x-data="{ show: false }">
            @csrf

            <div>
                <label class="form-label" for="name">Your Full Name</label>
                <input type="text" id="name" name="name" required class="form-input" value="{{ old('name', $invitation->name) }}" placeholder="e.g. Pastor David Cole" autofocus>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="password">Choose a Secure Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'"
                           type="password"
                           id="password"
                           name="password"
                           required
                           class="form-input pr-12"
                           placeholder="At least 8 characters">
                    <button type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate/70 hover:text-ink focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input :type="show ? 'text' : 'password'"
                       type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       class="form-input"
                       placeholder="Re-type password">
            </div>

            <button type="submit" class="btn btn-primary w-full py-3.5 mt-2">
                <span>Create Account & Join Dashboard</span>
            </button>
        </form>
    </div>
</body>
</html>
