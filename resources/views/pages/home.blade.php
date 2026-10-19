<x-layouts.app>
    <!-- 1. Hero Section -->
    <section class="relative min-h-[85vh] flex items-center bg-brand-950 overflow-hidden text-white pt-12 pb-20">
        <!-- Background Imagery & Gradient Overlays -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset(setting('home.hero_image', 'images/site/hero.jpg')) }}" 
                alt="Crossing life's rivers" 
                class="w-full h-full object-cover object-center opacity-35 filter brightness-95"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/80 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-transparent to-brand-950/40"></div>
        </div>

        <div class="container-x relative z-10 w-full py-12">
            <div class="max-w-3xl space-y-8 hero-enter">
                <!-- Eyebrow -->
                <div class="inline-flex items-center gap-3 text-xs md:text-sm font-semibold uppercase tracking-[0.22em] text-lime-400">
                    <span class="block h-px w-8 bg-lime-400"></span>
                    <span>{{ setting('home.hero_eyebrow', 'Your personal spiritual coach for a better life') }}</span>
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-light tracking-tight leading-[1.05] text-white">
                    {{ setting('home.hero_title') ? accent(setting('home.hero_title')) : 'Every life has rivers. You don’t have to cross them alone.' }}
                </h1>

                <!-- Supporting Statement -->
                <p class="text-lg md:text-xl text-white/80 leading-relaxed max-w-2xl font-light">
                    {{ setting('home.hero_text', 'Cross Rivers Ministries is a Christ-centered coaching ministry. We walk alongside individuals, families and leaders toward a life of purpose, fulfillment and lasting change.') }}
                </p>

                <!-- CTAs -->
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('coaching.index') }}" class="btn btn-green">
                        <span>Start Your Journey</span>
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-ghost-light">
                        <span>Learn About Us</span>
                    </a>
                </div>

                <!-- Hero Scripture Badge -->
                <div class="pt-6 border-t border-white/15 max-w-lg">
                    <p class="text-xs uppercase tracking-[0.14em] text-white/60">
                        "{{ setting('home.hero_scripture', 'When you pass through the rivers, they will not overwhelm you.') }}"
                        <span class="text-lime-400 font-semibold">— {{ setting('home.hero_scripture_ref', 'Isaiah 43:2') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Introduce Cross Rivers Ministries (Who We Are) -->
    <section class="section bg-paper relative overflow-hidden">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left Editorial Image Composition -->
                <div class="lg:col-span-5 relative reveal">
                    <div class="relative z-10 overflow-hidden rounded-[2px] shadow-lift">
                        <img 
                            src="{{ asset(setting('home.intro_image', 'images/site/who-we-are.jpg')) }}" 
                            alt="Jane Audrey, Cross Rivers Ministries Coach" 
                            class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700"
                        >
                    </div>
                    <!-- Decorative Green Offset Border -->
                    <div class="absolute -bottom-4 -right-4 size-full border-2 border-green-500/30 rounded-[2px] -z-0 hidden sm:block"></div>
                    <div class="absolute -top-6 -left-6 size-24 bg-lime-400/20 rounded-full blur-2xl pointer-events-none"></div>
                </div>

                <!-- Right Editorial Text -->
                <div class="lg:col-span-7 space-y-6 reveal">
                    <span class="eyebrow">Who We Are</span>
                    <h2 class="display-lg">
                        {{ setting('home.intro_title') ? accent(setting('home.intro_title')) : 'A ministry built on faith, guided by wisdom, devoted to you.' }}
                    </h2>
                    <p class="lead">
                        {{ setting('home.intro_text', 'We are a Christ-centered ministry based in the USA. We are committed to helping you experience the life of purpose and fulfillment that is your divine right as a child of God. That fulfilling life is possible for you in the here and now.') }}
                    </p>
                    <p class="text-slate leading-relaxed">
                        {{ setting('home.intro_text_2', 'Thank you for your interest in Cross Rivers Ministries. We pray you will see the amazing, life-changing work God is doing here — and that you will find a place where you can be heard, encouraged and equipped.') }}
                    </p>

                    <div class="pt-4 flex items-center gap-6">
                        <a href="{{ route('about') }}" class="btn btn-outline">
                            <span>Read Our Full Story</span>
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <div class="border-l-2 border-green-500 pl-4 py-1">
                            <span class="block text-xs uppercase tracking-wider text-muted font-semibold">Led By</span>
                            <span class="font-display text-base font-semibold text-brand-500">Jane Audrey</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. What We Do (5 Core Coaching Disciplines) -->
    <section class="section bg-white border-t border-line">
        <div class="container-x">
            <div class="max-w-2xl mb-16 space-y-4">
                <span class="eyebrow">What We Do</span>
                <h2 class="display-lg">
                    Five coaching disciplines. <span class="accent-italic">One journey to restoration.</span>
                </h2>
                <p class="text-slate text-lg">
                    The areas of coaching at our ministry are spiritual, family, managerial, vocational, and educational — with the emphasis on personal improvement and lasting divine change.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-reveal-stagger="100">
                @foreach($services as $service)
                    <div class="card card-hover flex flex-col justify-between p-8 group reveal">
                        <div class="space-y-6">
                            <!-- Service Icon / Number -->
                            <div class="flex items-center justify-between border-b border-line pb-6">
                                <span class="font-display text-3xl font-light text-brand-500/40 group-hover:text-green-500 transition-colors">
                                    0{{ $loop->iteration }}
                                </span>
                                <div class="size-11 rounded-[2px] bg-brand-50 flex items-center justify-center text-brand-500 group-hover:bg-green-500 group-hover:text-white transition-colors duration-300">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </div>
                            </div>

                            <h3 class="font-display text-2xl font-normal text-ink group-hover:text-brand-500 transition-colors">
                                {{ $service->title }}
                            </h3>

                            <p class="text-sm leading-relaxed text-slate line-clamp-3">
                                {{ $service->excerpt }}
                            </p>
                        </div>

                        <div class="pt-8 border-t border-line/60 mt-6">
                            <a href="{{ route('coaching.show', $service) }}" class="link-arrow">
                                <span>Learn More</span>
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach

                <!-- Highlight Card for Coaching Advice Line -->
                <div class="card bg-brand-500 text-white p-8 flex flex-col justify-between relative overflow-hidden reveal">
                    <div class="space-y-4 relative z-10">
                        <span class="text-xs uppercase tracking-[0.2em] font-semibold text-lime-400">Immediate Support</span>
                        <h3 class="font-display text-2xl font-light text-white">
                            Coaching Advice Line
                        </h3>
                        <p class="text-sm text-white/80 leading-relaxed">
                            Need immediate counsel or prayer? Our certified coaches eagerly await your call for a direct telephonic consultation.
                        </p>
                    </div>
                    <div class="pt-8 relative z-10">
                        <a href="{{ route('advice-line') }}" class="btn btn-white w-full">
                            <span>Call Advice Line</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Purpose / Vision (Split-Section Layout) -->
    <section class="section bg-brand-900 text-white relative overflow-hidden">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 reveal">
                    <span class="eyebrow eyebrow-light">Vision & Promise</span>
                    <h2 class="display-lg text-white">
                        Restoration is our <span class="text-lime-400 italic">ultimate outcome.</span>
                    </h2>
                    <p class="text-lg text-white/80 leading-relaxed">
                        For us, restoration defines the journey you take from being purposeless to being purposeful. First, we concentrate on your relationship with God with a goal of strengthening or re-establishing that relationship.
                    </p>
                    <p class="text-white/70 leading-relaxed">
                        We focus on rebuilding your identity based on the Word of God, which enables lifelong growth and continuous transformation.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                        <div class="p-6 bg-white/5 border-l-2 border-lime-400">
                            <span class="block text-xs uppercase tracking-wider text-lime-400 font-semibold mb-2">Our Vision</span>
                            <p class="text-sm text-white/90">{{ setting('home.vision', 'To envision a world in which every individual has the tools they need to live a fruitful life.') }}</p>
                        </div>
                        <div class="p-6 bg-white/5 border-l-2 border-green-400">
                            <span class="block text-xs uppercase tracking-wider text-green-400 font-semibold mb-2">Our Promise</span>
                            <p class="text-sm text-white/90">{{ setting('home.promise', 'We want to partner with you, as you navigate life, to help you cross life’s rivers. This will allow you to live God’s best life.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative reveal">
                    <div class="relative rounded-[2px] overflow-hidden shadow-lift">
                        <img 
                            src="{{ asset('images/site/about-hero.jpg') }}" 
                            alt="Fellowship and prayer at Cross Rivers Ministries" 
                            class="w-full h-auto object-cover"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. "Cross Life's Rivers" (The Core Organization Concept) -->
    <section class="section bg-surface relative overflow-hidden">
        <div class="container-x">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal">
                <span class="eyebrow mx-auto justify-center">The Journey</span>
                <h2 class="display-lg">
                    {{ setting('home.rivers_title') ? accent(setting('home.rivers_title')) : 'Cross life’s rivers.' }}
                </h2>
                <p class="text-lg text-slate">
                    {{ setting('home.rivers_text', 'As we traverse through life, each of us will face rivers that must be crossed. Some are wide, some are deep, and some arrive without warning. You were never meant to stand on the bank alone.') }}
                </p>
            </div>

            <!-- Rivers Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-reveal-stagger="80">
                @php
                    $rivers = setting_lines('home.rivers_list');
                @endphp
                @foreach($rivers as $river)
                    <div class="card p-7 bg-white border border-line flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                        <div class="space-y-3">
                            <div class="size-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center font-bold text-xs">
                                0{{ $loop->iteration }}
                            </div>
                            <h3 class="font-display text-xl font-normal text-ink">{{ $river['title'] }}</h3>
                            <p class="text-sm text-slate leading-relaxed">{{ $river['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Wide Banner Graphic -->
            <div class="mt-14 relative rounded-[2px] overflow-hidden shadow-lift reveal">
                <img 
                    src="{{ asset(setting('home.rivers_image', 'images/site/rivers-wide.jpg')) }}" 
                    alt="Cross life's rivers panorama" 
                    class="w-full h-64 md:h-80 object-cover"
                >
                <div class="absolute inset-0 bg-brand-950/40 flex items-center p-8 md:p-14">
                    <div class="max-w-xl text-white space-y-3">
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-lime-400">Jeremiah 29:11</span>
                        <h3 class="font-display text-2xl md:text-3xl font-light text-white">
                            "Plans to prosper you and not to harm you, plans to give you hope and a future."
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Coaching Process (4 Steps) -->
    <section class="section bg-white border-t border-line">
        <div class="container-x">
            <div class="max-w-2xl mb-16 space-y-4">
                <span class="eyebrow">Our Methodology</span>
                <h2 class="display-lg">
                    The Coaching <span class="accent-italic">Process.</span>
                </h2>
                <p class="text-slate text-lg">
                    A simple, biblically grounded progression designed to move you from uncertainty to divine purpose.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-reveal-stagger="90">
                @foreach(config('crm.process', []) as $step)
                    <div class="relative p-6 border-l-2 border-green-500 bg-paper reveal">
                        <span class="font-display text-4xl font-light text-brand-500/30 block mb-2">0{{ $loop->iteration }}</span>
                        <h3 class="font-display text-xl font-normal text-ink mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-slate leading-relaxed">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. Testimonials Carousel / Slider -->
    @if($testimonials->count())
    <section class="section bg-brand-50 relative overflow-hidden" x-data="slider({ count: {{ $testimonials->count() }}, interval: 8000 })">
        <div class="container-x">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
                <div class="space-y-3">
                    <span class="eyebrow">Client Breakthroughs</span>
                    <h2 class="display-lg">Stories of <span class="accent-italic">Victory.</span></h2>
                    <p class="text-slate text-sm">Real stories from individuals and families who crossed life’s rivers with our coaches.</p>
                </div>
                <!-- Navigation Controls -->
                <div class="flex items-center gap-3">
                    <button @click="prev" class="size-11 rounded-[2px] border border-line bg-white flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors" aria-label="Previous testimonial">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <button @click="next" class="size-11 rounded-[2px] border border-line bg-white flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors" aria-label="Next testimonial">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
            </div>

            <!-- Testimonial Slides -->
            <div class="relative min-h-[300px]">
                @foreach($testimonials as $i => $item)
                    <div 
                        x-show="isActive({{ $i }})" 
                        x-cloak 
                        x-transition:enter="transition ease-out duration-500" 
                        x-transition:enter-start="opacity-0 translate-y-4" 
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white p-8 md:p-12 border border-line shadow-soft max-w-4xl"
                    >
                        <div class="space-y-6">
                            @if($item->category)
                                <span class="inline-block text-xs font-semibold uppercase tracking-wider text-green-600 bg-green-50 px-3 py-1 rounded-[2px]">
                                    {{ $item->category }}
                                </span>
                            @endif
                            <blockquote class="font-display text-xl md:text-2xl font-light text-ink leading-relaxed">
                                "{{ $item->content }}"
                            </blockquote>
                            <div class="pt-4 border-t border-line flex items-center justify-between">
                                <div>
                                    <span class="block font-semibold text-brand-500">{{ $item->name }}</span>
                                    @if($item->location)
                                        <span class="text-xs text-muted">{{ $item->location }}</span>
                                    @endif
                                </div>
                                <span class="text-xs font-mono text-muted">1 Corinthians 15:57</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- 8. Featured Media & Insights (Blog, Podcast, Video) -->
    <section class="section bg-white border-t border-line">
        <div class="container-x">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-16 gap-4">
                <div class="space-y-3">
                    <span class="eyebrow">Insights & Media</span>
                    <h2 class="display-lg">Resources for <span class="accent-italic">Your Walk.</span></h2>
                </div>
                <a href="{{ route('blog.index') }}" class="link-arrow">
                    <span>View All Articles</span>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Blog Feature -->
                @if($latestPosts->count())
                    @php $p = $latestPosts->first(); @endphp
                    <div class="card p-6 flex flex-col justify-between reveal">
                        <div class="space-y-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-brand-500">From the Blog</span>
                            <div class="aspect-video overflow-hidden rounded-[2px]">
                                <img src="{{ asset($p->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="font-display text-xl font-normal text-ink hover:text-brand-500 transition-colors">
                                <a href="{{ route('blog.show', $p) }}">{{ $p->title }}</a>
                            </h3>
                            <p class="text-xs text-slate line-clamp-2">{{ $p->summary() }}</p>
                        </div>
                        <div class="pt-4 border-t border-line mt-4">
                            <a href="{{ route('blog.show', $p) }}" class="link-arrow">Read Article</a>
                        </div>
                    </div>
                @endif

                <!-- Podcast Feature -->
                @if($featuredEpisodes->count())
                    @php $ep = $featuredEpisodes->first(); @endphp
                    <div class="card p-6 flex flex-col justify-between bg-paper reveal">
                        <div class="space-y-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-green-600">Featured Podcast</span>
                            <div class="aspect-square w-32 mx-auto overflow-hidden rounded-[2px] shadow-sm">
                                <img src="{{ asset($ep->artworkUrl() ?? 'images/site/podcast.jpg') }}" alt="{{ $ep->title }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="font-display text-xl font-normal text-ink text-center hover:text-brand-500 transition-colors">
                                <a href="{{ route('podcasts.show', $ep) }}">{{ $ep->title }}</a>
                            </h3>
                            <p class="text-xs text-slate text-center line-clamp-2">{{ $ep->summary() }}</p>
                        </div>
                        <div class="pt-4 border-t border-line mt-4 text-center">
                            <a href="{{ route('podcasts.show', $ep) }}" class="link-arrow justify-center">Listen Now ({{ $ep->duration }})</a>
                        </div>
                    </div>
                @endif

                <!-- Video Feature -->
                @if($featuredVideo)
                    <div class="card p-6 flex flex-col justify-between reveal">
                        <div class="space-y-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-lime-600">Video Message</span>
                            <div class="aspect-video overflow-hidden rounded-[2px] relative group">
                                <img src="{{ asset($featuredVideo->thumbnailUrl() ?? 'images/site/video-cover.jpg') }}" alt="{{ $featuredVideo->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-brand-950/30 flex items-center justify-center group-hover:bg-brand-950/20 transition-colors">
                                    <div class="size-12 rounded-full bg-white text-brand-500 flex items-center justify-center shadow-lg">
                                        <svg class="size-5 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-display text-xl font-normal text-ink hover:text-brand-500 transition-colors">
                                <a href="{{ route('videos.show', $featuredVideo) }}">{{ $featuredVideo->title }}</a>
                            </h3>
                            <p class="text-xs text-slate line-clamp-2">{{ $featuredVideo->summary() }}</p>
                        </div>
                        <div class="pt-4 border-t border-line mt-4">
                            <a href="{{ route('videos.show', $featuredVideo) }}" class="link-arrow">Watch Video</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 9. Final Call to Action -->
    <section class="section bg-brand-950 text-white text-center relative overflow-hidden">
        <div class="container-narrow relative z-10 space-y-8 reveal">
            <span class="eyebrow eyebrow-light mx-auto justify-center">Start Today</span>
            <h2 class="display-lg text-white">
                {{ setting('home.cta_title') ? accent(setting('home.cta_title'), 'text-lime-400 italic') : 'Your crossing starts with a conversation.' }}
            </h2>
            <p class="text-lg text-white/80 leading-relaxed max-w-xl mx-auto">
                {{ setting('home.cta_text', 'Our coaches eagerly await your call. Whatever river you are facing, take the first step today — we will walk with you every step of the way.') }}
            </p>
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="btn btn-green">
                    <span>Connect With Us</span>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="btn btn-ghost-light">
                    <span>Call Advice Line</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
