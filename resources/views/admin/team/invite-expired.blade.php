<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invitation Expired — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-6 bg-brand-950 font-sans">
    <div class="w-full max-w-md bg-white rounded-[2px] shadow-lift p-8 text-center space-y-5">
        <div class="size-14 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="font-display text-2xl font-semibold text-ink">Invitation Expired</h1>
        <p class="text-xs text-slate leading-relaxed">
            This invitation link was issued to <strong>{{ $invitation->email }}</strong> and has expired for security reasons. Please ask the ministry administrator to reissue your invitation.
        </p>
        <div class="pt-2">
            <a href="{{ route('admin.login') }}" class="btn btn-secondary w-full py-2.5">
                <span>Go to Admin Login</span>
            </a>
        </div>
    </div>
</body>
</html>
