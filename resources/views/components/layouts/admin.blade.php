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
<body class="h-screen flex flex-col font-sans text-slate-800 bg-slate-100 overflow-hidden">
    <!-- Admin Header -->
    <header class="bg-brand-950 text-white border-b border-white/10 shrink-0 z-30">
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
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 text-white/80 hover:text-white transition-colors group">
                    <div class="size-7 rounded-full bg-brand-500 text-white font-semibold text-xs flex items-center justify-center overflow-hidden border border-white/20 group-hover:border-lime-400 transition-colors">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="size-full object-cover">
                        @else
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <span>{{ auth()->user()->name }}</span>
                    <span class="text-[0.625rem] bg-brand-800 text-lime-400 px-1.5 py-0.5 rounded-[2px] uppercase font-bold tracking-wider">Admin</span>
                </a>

                <span class="text-white/20">|</span>

                <a href="{{ route('admin.profile.edit') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-1">
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span>Profile</span>
                </a>

                <span class="text-white/20">|</span>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 font-medium">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex flex-1 min-h-0 overflow-hidden">
        <!-- Sidebar Navigation (Fixed in view with independent vertical scroll) -->
        <aside id="admin-sidebar" class="w-64 bg-white border-r border-slate-200 p-6 pb-20 flex flex-col shrink-0 overflow-y-auto overscroll-contain admin-scrollbar">
            <nav class="space-y-6 text-sm">
                <div>
                    <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Overview</span>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-1.5 rounded-[2px] font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.profile.edit') }}" class="block px-3 py-1.5 rounded-[2px] font-medium {{ request()->routeIs('admin.profile.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            Admin Profile
                        </a>
                        @if(auth()->user()->hasPermission('manage_team'))
                            <a href="{{ route('admin.team.index') }}" class="block px-3 py-1.5 rounded-[2px] font-medium {{ request()->routeIs('admin.team.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                                Team & Permissions
                            </a>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->hasPermission('manage_settings'))
                    <div>
                        <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Content & Site</span>
                        <div class="space-y-1">
                            <a href="{{ route('admin.settings.index', 'general') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/general*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Site Settings</a>
                            <a href="{{ route('admin.settings.index', 'home') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/home*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Homepage Content</a>
                            <a href="{{ route('admin.settings.index', 'about') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/about*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">About Us</a>
                            <a href="{{ route('admin.settings.index', 'advice_line') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->is('admin/settings/advice_line*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Advice Line</a>
                        </div>
                    </div>
                @endif

                @if(auth()->user()->hasPermission('manage_coaching') || auth()->user()->hasPermission('manage_consulting'))
                    <div>
                        <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Ministry Services</span>
                        <div class="space-y-1">
                            @if(auth()->user()->hasPermission('manage_coaching'))
                                <a href="{{ route('admin.coaching.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.coaching.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Coaching Services</a>
                                <a href="{{ route('admin.coaches.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.coaches.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Coaches & Staff</a>
                            @endif
                            @if(auth()->user()->hasPermission('manage_consulting'))
                                <a href="{{ route('admin.consulting.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.consulting.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Consulting / Speaking</a>
                            @endif
                            @if(auth()->user()->hasPermission('manage_media') || auth()->user()->hasPermission('manage_coaching'))
                                <a href="{{ route('admin.testimonials.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.testimonials.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Testimonials</a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(auth()->user()->hasPermission('manage_media'))
                    <div>
                        <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Media & Publications</span>
                        <div class="space-y-1">
                            <a href="{{ route('admin.posts.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.posts.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Blog Posts</a>
                            <a href="{{ route('admin.podcasts.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.podcasts.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Podcast Episodes</a>
                            <a href="{{ route('admin.videos.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.videos.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Video Library</a>
                            <a href="{{ route('admin.media.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.media.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Media Files</a>
                        </div>
                    </div>
                @endif

                @if(auth()->user()->hasPermission('manage_store') || auth()->user()->hasPermission('manage_inbox'))
                    <div>
                        <span class="block text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400 mb-2">Commerce & Inquiries</span>
                        <div class="space-y-1">
                            @if(auth()->user()->hasPermission('manage_store'))
                                <a href="{{ route('admin.products.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.products.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Store Products</a>
                                <a href="{{ route('admin.orders.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.orders.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Customer Orders</a>
                            @endif
                            @if(auth()->user()->hasPermission('manage_inbox'))
                                <a href="{{ route('admin.inbox.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.inbox.*') && !request()->routeIs('admin.subscribers.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Contact Inquiries</a>
                                <a href="{{ route('admin.subscribers.index') }}" class="block px-3 py-1.5 rounded-[2px] {{ request()->routeIs('admin.subscribers.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Subscribers</a>
                            @endif
                        </div>
                    </div>
                @endif
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

    <script>
        // Ensure active sidebar link is smoothly scrolled into view inside the sidebar
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const activeLink = sidebar ? sidebar.querySelector('a.bg-brand-50') : null;
            if (activeLink && sidebar) {
                const sidebarRect = sidebar.getBoundingClientRect();
                const linkRect = activeLink.getBoundingClientRect();
                if (linkRect.bottom > sidebarRect.bottom || linkRect.top < sidebarRect.top) {
                    activeLink.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
            }
        });
    </script>
</body>
</html>
