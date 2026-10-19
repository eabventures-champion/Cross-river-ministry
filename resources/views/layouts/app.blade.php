<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white text-slate antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ seo()->fullTitle() }}</title>
    <meta name="description" content="{{ seo()->metaDescription() }}">
    <link rel="canonical" href="{{ seo()->canonicalUrl() }}">

    @if(seo()->isNoindex())
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ seo()->ogType() }}">
    <meta property="og:url" content="{{ seo()->canonicalUrl() }}">
    <meta property="og:title" content="{{ seo()->fullTitle() }}">
    <meta property="og:description" content="{{ seo()->metaDescription() }}">
    @if($ogImg = seo()->imageUrl())
        <meta property="og:image" content="{{ $ogImg }}">
    @endif
    <meta property="og:site_name" content="{{ seo()->siteName() }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ seo()->fullTitle() }}">
    <meta name="twitter:description" content="{{ seo()->metaDescription() }}">
    @if($ogImg)
        <meta name="twitter:image" content="{{ $ogImg }}">
    @endif

    <!-- Fonts: Asap (body) & Fraunces (editorial display) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
        {!! seo()->jsonLd() !!}
    </script>
</head>
<body class="min-h-full flex flex-col bg-white selection:bg-lime-500 selection:text-ink">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:bg-brand-500 focus:text-white focus:px-4 focus:py-2 focus:rounded-[2px]">
        Skip to main content
    </a>

    <!-- Top Announcement Bar -->
    <div class="bg-brand-900 text-white/90 text-xs py-2 px-4 border-b border-white/10 hidden sm:block">
        <div class="container-x flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-lime-400 animate-pulse"></span>
                <span>Coaching Advice Line Available: Speak directly with a certified Christian mentor</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="text-lime-400 hover:text-white font-medium flex items-center gap-1.5 transition-colors">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    Call Advice Line
                </a>
                <span class="text-white/30">|</span>
                <a href="mailto:{{ setting('general.contact_email', 'crossriversministriesinfo@gmail.com') }}" class="hover:text-white transition-colors">
                    {{ setting('general.contact_email', 'crossriversministriesinfo@gmail.com') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Header Navigation -->
    <x-navigation />

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-3 text-center text-sm font-medium shadow-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-600 text-white px-4 py-3 text-center text-sm font-medium shadow-md">
            {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-amber-500 text-white px-4 py-3 text-center text-sm font-medium shadow-md">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Main Content Slot -->
    <main id="main-content" class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer Component -->
    <x-footer />
</body>
</html>
