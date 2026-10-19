<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Portal' }} — Cross Rivers Ministries</title>
    <link rel="icon" type="image/png" href="{{ asset('images/site/crm-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800">
    <!-- Admin Header -->
    <header class="bg-brand-950 text-white border-b border-white/10 sticky top-0 z-30">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/site/crm-logo.png') }}" alt="CRM" class="h-8 w-auto">
                    <span class="font-display font-semibold text-lg text-white">CRM Admin</span>
                </a>
                <span class="text-white/20">|</span>
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-lime-400 hover:text-white transition-colors flex items-center gap-1">
                    <span>View Public Website</span>
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <span class="text-white/70">Logged in as <strong>{{ auth()->user()->name }}</strong></span>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 font-medium">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex flex-grow">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between shrink-0">
            <nav class="space-y-6 text-sm">
                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Overview</span>
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-[2px] font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        Dashboard
                    </a>
                </div>

                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Content & Site</span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.settings.index', 'general') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/general*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Site Settings</a>
                        <a href="{{ route('admin.settings.index', 'home') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/home*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Homepage Content</a>
                        <a href="{{ route('admin.settings.index', 'about') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/about*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">About Us</a>
                        <a href="{{ route('admin.settings.index', 'advice_line') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/advice_line*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Advice Line</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Ministry Services</span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.coaching.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.coaching.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Coaching Services</a>
                        <a href="{{ route('admin.coaches.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.coaches.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Coaches & Staff</a>
                        <a href="{{ route('admin.consulting.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.consulting.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Consulting / Speaking</a>
                        <a href="{{ route('admin.testimonials.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.testimonials.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Testimonials</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Media & Publications</span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.posts.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.posts.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Blog Posts</a>
                        <a href="{{ route('admin.podcasts.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.podcasts.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Podcast Episodes</a>
                        <a href="{{ route('admin.videos.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.videos.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Video Library</a>
                        <a href="{{ route('admin.media.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.media.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Media Files</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Commerce & Inquiries</span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.products.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.products.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Store Products</a>
                        <a href="{{ route('admin.orders.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.orders.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Customer Orders</a>
                        <a href="{{ route('admin.inbox.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.inbox.*') && !request()->routeIs('admin.subscribers.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Contact Inquiries</a>
                        <a href="{{ route('admin.subscribers.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.subscribers.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Subscribers</a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Body Content -->
        <main class="flex-grow p-8 bg-slate-50 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-[2px] text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-800 rounded-[2px] text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
