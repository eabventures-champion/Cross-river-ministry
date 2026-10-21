@php
    $navItems = config('crm.nav', []);
    $isResourcesActive = request()->routeIs('coaching.*') || request()->routeIs('consulting*') || request()->routeIs('podcasts.*') || request()->routeIs('videos.*');
@endphp

<header 
    x-data="siteHeader" 
    :class="{ 'bg-white/95 backdrop-blur-md shadow-soft py-2.5 sm:py-3': scrolled, 'bg-white py-3 sm:py-5': !scrolled }"
    class="sticky top-0 z-40 w-full transition-all duration-300 border-b border-line"
>
    <div class="container-x px-3 sm:px-8 lg:px-12 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-4 group shrink-0">
            <img 
                src="{{ asset('images/site/crm-logo.png') }}" 
                alt="Cross Rivers Ministries" 
                class="h-14 min-[380px]:h-16 sm:h-16 md:h-20 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
            >
            <div class="block">
                <span class="block font-display text-[0.8125rem] min-[360px]:text-[0.875rem] min-[390px]:text-[0.9375rem] sm:text-lg md:text-xl font-medium leading-tight text-brand-500 uppercase tracking-tight sm:tracking-wide whitespace-nowrap">
                    Cross Rivers Ministries
                </span>
                <span class="block text-[0.5625rem] min-[360px]:text-[0.625rem] min-[390px]:text-[0.6875rem] sm:text-[0.6875rem] font-bold uppercase tracking-[0.12em] sm:tracking-[0.18em] text-green-600 mt-0.5 whitespace-nowrap">
                    Helping You Cross Over
                </span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden xl:flex items-center gap-7">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (str_contains($item['route'], '.') && request()->routeIs(explode('.', $item['route'])[0] . '.*'));
                @endphp
                <a 
                    href="{{ route($item['route']) }}" 
                    class="text-[0.875rem] font-medium transition-colors duration-200 hover:text-brand-500 {{ $isActive ? 'text-brand-500 font-semibold' : 'text-slate' }}"
                >
                    {{ $item['label'] }}
                </a>

                {{-- Insert comprehensive Resources dropdown right after About --}}
                @if($item['route'] === 'about')
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button 
                            type="button" 
                            class="text-[0.875rem] font-medium transition-colors duration-200 hover:text-brand-500 flex items-center gap-1 py-2 {{ $isResourcesActive ? 'text-brand-500 font-semibold' : 'text-slate' }}"
                            :aria-expanded="open"
                        >
                            <span>Resources</span>
                            <svg class="size-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute top-full left-0 w-60 bg-white border border-line rounded-[2px] shadow-lift py-2 z-50 divide-y divide-slate-100"
                        >
                            <div class="py-1">
                                <span class="block px-4 py-1 text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400">Programs & Guidance</span>
                                <a href="{{ route('coaching.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate hover:bg-brand-50 hover:text-brand-500 transition-colors {{ request()->routeIs('coaching.*') ? 'bg-brand-50 text-brand-500 font-semibold' : '' }}">
                                    <svg class="size-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                    </svg>
                                    <div>
                                        <span class="block font-medium">Coaching Services</span>
                                        <span class="text-[0.625rem] text-muted">Spiritual, Family & Vocational</span>
                                    </div>
                                </a>

                                <a href="{{ route('consulting') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate hover:bg-brand-50 hover:text-brand-500 transition-colors {{ request()->routeIs('consulting') ? 'bg-brand-50 text-brand-500 font-semibold' : '' }}">
                                    <svg class="size-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                                    </svg>
                                    <div>
                                        <span class="block font-medium">Consulting / Speaking</span>
                                        <span class="text-[0.625rem] text-muted">Workshops & Advisory</span>
                                    </div>
                                </a>
                            </div>

                            <div class="py-1">
                                <span class="block px-4 py-1 text-[0.6875rem] font-bold uppercase tracking-wider text-slate-400">Media Library</span>
                                <a href="{{ route('podcasts.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate hover:bg-brand-50 hover:text-brand-500 transition-colors {{ request()->routeIs('podcasts.*') ? 'bg-brand-50 text-brand-500 font-semibold' : '' }}">
                                    <svg class="size-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                                    </svg>
                                    <div>
                                        <span class="block font-medium">Podcasts</span>
                                        <span class="text-[0.625rem] text-muted">Audio teachings & insights</span>
                                    </div>
                                </a>

                                <a href="{{ route('videos.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate hover:bg-brand-50 hover:text-brand-500 transition-colors {{ request()->routeIs('videos.*') ? 'bg-brand-50 text-brand-500 font-semibold' : '' }}">
                                    <svg class="size-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                                    </svg>
                                    <div>
                                        <span class="block font-medium">Videos</span>
                                        <span class="text-[0.625rem] text-muted">Video sessions & stream</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </nav>

        <!-- Right Side: Cart Badge & Action Button -->
        <div class="hidden lg:flex items-center gap-5">
            <!-- Cart Icon -->
            <a 
                href="{{ route('store.cart') }}" 
                class="relative p-2 text-slate hover:text-brand-500 transition-colors"
                title="View Shopping Cart"
            >
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span 
                    x-data="{ count: {{ $cartCount ?? 0 }} }"
                    @cart-updated.window="count = $event.detail.count"
                    x-show="count > 0"
                    x-text="count"
                    class="absolute -top-1 -right-1 flex size-5 items-center justify-center rounded-full bg-brand-500 text-[0.6875rem] font-bold text-white shadow"
                ></span>
            </a>

            <!-- Primary CTA -->
            <a href="{{ route('advice-line') }}" class="btn btn-primary btn-sm">
                <span>Advice Line</span>
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <!-- Mobile Menu Trigger -->
        <div class="flex items-center gap-1.5 sm:gap-3 xl:hidden shrink-0">
            <a href="{{ route('store.cart') }}" class="relative p-1.5 sm:p-2 text-slate hover:text-brand-500 transition-colors">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span 
                    x-data="{ count: {{ $cartCount ?? 0 }} }"
                    @cart-updated.window="count = $event.detail.count"
                    x-show="count > 0"
                    x-text="count"
                    class="absolute -top-1 -right-1 flex size-4 items-center justify-center rounded-full bg-brand-500 text-[0.625rem] font-bold text-white shadow"
                ></span>
            </a>

            <button 
                @click="open = !open" 
                type="button" 
                class="p-1.5 sm:p-2 text-slate hover:text-brand-500 focus:outline-none"
                aria-label="Toggle navigation menu"
            >
                <svg x-show="!open" class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="open" x-cloak class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div 
        x-show="open" 
        x-cloak 
        x-collapse 
        class="xl:hidden border-t border-line bg-white px-6 py-6 shadow-xl"
    >
        <nav class="flex flex-col gap-4">
            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']) || (str_contains($item['route'], '.') && request()->routeIs(explode('.', $item['route'])[0] . '.*'));
                @endphp
                <a 
                    href="{{ route($item['route']) }}" 
                    @click="open = false"
                    class="py-2 text-base font-medium transition-colors border-b border-line/50 {{ $isActive ? 'text-brand-500 font-bold' : 'text-slate' }}"
                >
                    {{ $item['label'] }}
                </a>

                @if($item['route'] === 'about')
                    <div x-data="{ expanded: false }" class="border-b border-line/50 pb-2">
                        <button 
                            type="button" 
                            @click="expanded = !expanded" 
                            class="w-full py-2 flex items-center justify-between text-base font-medium text-slate hover:text-brand-500 transition-colors"
                        >
                            <span :class="{ 'text-brand-500 font-bold': {{ $isResourcesActive ? 'true' : 'false' }} }">Resources</span>
                            <svg class="size-4 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse class="pl-4 pt-1 space-y-2">
                            <a href="{{ route('coaching.index') }}" @click="open = false" class="block py-1.5 text-sm text-slate hover:text-brand-500 {{ request()->routeIs('coaching.*') ? 'text-brand-500 font-semibold' : '' }}">
                                Coaching Services
                            </a>
                            <a href="{{ route('consulting') }}" @click="open = false" class="block py-1.5 text-sm text-slate hover:text-brand-500 {{ request()->routeIs('consulting') ? 'text-brand-500 font-semibold' : '' }}">
                                Consulting / Speaking
                            </a>
                            <a href="{{ route('podcasts.index') }}" @click="open = false" class="block py-1.5 text-sm text-slate hover:text-brand-500 {{ request()->routeIs('podcasts.*') ? 'text-brand-500 font-semibold' : '' }}">
                                Podcasts
                            </a>
                            <a href="{{ route('videos.index') }}" @click="open = false" class="block py-1.5 text-sm text-slate hover:text-brand-500 {{ request()->routeIs('videos.*') ? 'text-brand-500 font-semibold' : '' }}">
                                Videos
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="pt-4 flex flex-col gap-3">
                <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="btn btn-green w-full">
                    <span>Call Advice Line</span>
                </a>
                <a href="{{ route('contact') }}" class="btn btn-primary w-full">
                    <span>Connect With Us</span>
                </a>
            </div>
        </nav>
    </div>
</header>
