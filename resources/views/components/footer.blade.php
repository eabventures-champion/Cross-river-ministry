<footer class="bg-brand-950 text-white/80 pt-20 pb-12 border-t border-white/10 relative overflow-hidden">
    <!-- Subtle background water reflection effect -->
    <div class="absolute inset-0 bg-radial from-brand-800/20 via-transparent to-transparent pointer-events-none"></div>

    <div class="container-x relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8 pb-16 border-b border-white/10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3.5">
                    <img src="{{ asset('images/site/crm-logo.png') }}" alt="Cross Rivers Ministries" class="h-14 w-auto brightness-110">
                    <div>
                        <span class="block font-display text-xl font-normal leading-tight text-white uppercase tracking-wide">
                            Cross Rivers Ministries
                        </span>
                        <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-lime-400">
                            Helping You Cross Over
                        </span>
                    </div>
                </a>
                <p class="text-sm leading-relaxed text-white/70 max-w-md">
                    {{ setting('general.footer_about', 'A Christ-centered ministry committed to helping you experience the life of purpose and fulfillment that is your divine right as a child of God — in the here and now.') }}
                </p>
                <div class="pt-2 flex items-center gap-3">
                    @if($fb = setting('general.facebook_url'))
                        <a href="{{ $fb }}" target="_blank" rel="noopener" class="size-9 rounded-[2px] bg-white/10 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors" title="Facebook">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if($ig = setting('general.instagram_url'))
                        <a href="{{ $ig }}" target="_blank" rel="noopener" class="size-9 rounded-[2px] bg-white/10 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors" title="Instagram">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($tw = setting('general.twitter_url'))
                        <a href="{{ $tw }}" target="_blank" rel="noopener" class="size-9 rounded-[2px] bg-white/10 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors" title="Twitter / X">
                            <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-lime-400">
                    Quick Links
                </span>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('coaching.index') }}" class="hover:text-white transition-colors">Coaching Services</a></li>
                    <li><a href="{{ route('consulting') }}" class="hover:text-white transition-colors">Consulting & Speaking</a></li>
                    <li><a href="{{ route('advice-line') }}" class="hover:text-white transition-colors">Coaching Advice Line</a></li>
                    <li><a href="{{ route('testimonials') }}" class="hover:text-white transition-colors">Testimonials</a></li>
                    <li><a href="{{ route('store.index') }}" class="hover:text-white transition-colors">Store</a></li>
                </ul>
            </div>

            <!-- Media & Resources -->
            <div class="space-y-4">
                <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-lime-400">
                    Media & Growth
                </span>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog & Articles</a></li>
                    <li><a href="{{ route('podcasts.index') }}" class="hover:text-white transition-colors">Podcasts Library</a></li>
                    <li><a href="{{ route('videos.index') }}" class="hover:text-white transition-colors">Video Messages</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact Support</a></li>
                    <li><a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">Sitemap</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="space-y-4">
                <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-lime-400">
                    Stay Connected
                </span>
                <p class="text-xs leading-relaxed text-white/70">
                    Subscribe to our Quarterly Newsletter for biblical encouragement and ministry updates.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="source" value="footer">
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        placeholder="Your email address" 
                        class="w-full bg-white/10 border border-white/20 rounded-[2px] px-3.5 py-2.5 text-xs text-white placeholder:text-white/40 focus:border-lime-400 focus:outline-none"
                    >
                    <button type="submit" class="w-full btn btn-green btn-sm">
                        <span>Subscribe</span>
                    </button>
                    @if(session('newsletter_success'))
                        <p class="text-[0.6875rem] text-lime-400 mt-1">{{ session('newsletter_success') }}</p>
                    @endif
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
            <p>&copy; {{ date('Y') }} Cross Rivers Ministries. All Rights Reserved.</p>
            <div class="flex items-center gap-6">
                <span>"When you pass through the rivers, they will not overwhelm you." — Isaiah 43:2</span>
                <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors">Admin Portal</a>
            </div>
        </div>
    </div>
</footer>
