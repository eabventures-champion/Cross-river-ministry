<x-layouts.app>
    <!-- About Hero -->
    <section class="relative min-h-[50vh] flex items-center bg-brand-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset(setting('about.hero_image', 'images/site/about-hero.jpg')) }}" alt="About Cross Rivers Ministries" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/80 to-transparent"></div>
        </div>
        <div class="container-x relative z-10 max-w-3xl space-y-6">
            <span class="eyebrow eyebrow-light">About Cross Rivers Ministries</span>
            <h1 class="display-lg text-white">
                {{ setting('about.hero_title') ? accent(setting('about.hero_title'), 'text-lime-400 italic') : 'We exist to help you live God’s best life.' }}
            </h1>
            <p class="text-xl text-white/80 font-light leading-relaxed">
                {{ setting('about.hero_text', 'Cross Rivers Ministries is a Christ-centered ministry offering the spiritually, physically and emotionally broken a place where they can connect, be heard and be restored.') }}
            </p>
        </div>
    </section>

    <!-- Who We Are & The Need -->
    <section class="section bg-white">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <div class="lg:col-span-5 reveal">
                    <div class="relative rounded-[2px] overflow-hidden shadow-lift border border-line">
                        <img src="{{ asset(setting('about.story_image', 'images/site/spiritual.jpg')) }}" alt="Scripture and coaching reflection" class="w-full h-auto object-cover">
                    </div>
                </div>
                <div class="lg:col-span-7 space-y-6 reveal">
                    <span class="eyebrow">Our Calling</span>
                    <h2 class="display-md">
                        {{ setting('about.who_title', 'There is a need, now more than ever, for guidance rooted in truth.') }}
                    </h2>
                    <div class="prose-crm">
                        {!! setting('about.who_text') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story (Jane Audrey's Nursing & Ministry Background) -->
    <section class="section bg-paper border-t border-line">
        <div class="container-narrow space-y-8 reveal">
            <div class="text-center space-y-4">
                <span class="eyebrow mx-auto justify-center">Our Story</span>
                <h2 class="display-lg">
                    {{ setting('about.story_title', 'From a nurse’s calling to a ministry of restoration.') }}
                </h2>
            </div>
            <div class="prose-crm">
                {!! setting('about.story_text') !!}
            </div>
        </div>
    </section>

    <!-- Mission, Vision & Promise -->
    <section class="section bg-brand-900 text-white">
        <div class="container-x">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="eyebrow eyebrow-light mx-auto justify-center">Foundational Pillars</span>
                <h2 class="display-lg text-white">Anchored in <span class="text-lime-400 italic">Purpose.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-white/5 border border-white/10 rounded-[2px] space-y-4 reveal">
                    <span class="font-display text-2xl text-lime-400">Mission</span>
                    <p class="text-sm text-white/80 leading-relaxed">{{ setting('about.mission') }}</p>
                </div>
                <div class="p-8 bg-white/5 border border-white/10 rounded-[2px] space-y-4 reveal">
                    <span class="font-display text-2xl text-green-400">Vision</span>
                    <p class="text-sm text-white/80 leading-relaxed">{{ setting('about.vision') }}</p>
                </div>
                <div class="p-8 bg-white/5 border border-white/10 rounded-[2px] space-y-4 reveal">
                    <span class="font-display text-2xl text-white">Our Promise</span>
                    <p class="text-sm text-white/80 leading-relaxed">{{ setting('about.promise') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="section bg-white border-t border-line">
        <div class="container-x">
            <div class="max-w-2xl mb-14 space-y-3">
                <span class="eyebrow">Guiding Truths</span>
                <h2 class="display-lg">Our <span class="accent-italic">Values.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($values as $val)
                    <div class="p-7 bg-paper border-l-2 border-green-500 rounded-[2px] space-y-2 reveal">
                        <h3 class="font-display text-xl font-normal text-ink">{{ $val['title'] }}</h3>
                        <p class="text-sm text-slate leading-relaxed">{{ $val['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Leadership / Coaches -->
    <section class="section bg-surface border-t border-line">
        <div class="container-x">
            <div class="max-w-2xl mb-14 space-y-3">
                <span class="eyebrow">Our Team</span>
                <h2 class="display-lg">Meet Our <span class="accent-italic">Coaches.</span></h2>
                <p class="text-slate">Dedicated servants walking alongside you with empathy, prayer, and professional expertise.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($coaches as $coach)
                    <div class="card p-6 bg-white flex flex-col justify-between reveal">
                        <div class="space-y-4">
                            <div class="aspect-square overflow-hidden rounded-[2px]">
                                <img src="{{ asset($coach->imageUrl() ?? 'images/site/who-we-are.jpg') }}" alt="{{ $coach->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-semibold text-ink">{{ $coach->name }}</h3>
                                <span class="block text-xs uppercase tracking-wider text-green-600 font-semibold">{{ $coach->role }}</span>
                            </div>
                            <p class="text-xs text-slate leading-relaxed">{{ $coach->bio }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section class="section bg-brand-950 text-white text-center">
        <div class="container-narrow space-y-6">
            <h2 class="display-md text-white">Experience God's restoration today.</h2>
            <p class="text-white/80 max-w-md mx-auto">We look forward to connecting with you and learning how we can partner on your journey.</p>
            <div class="pt-4 flex justify-center gap-4">
                <a href="{{ route('contact') }}" class="btn btn-green">Connect With Us</a>
                <a href="{{ route('coaching.index') }}" class="btn btn-ghost-light">Explore Coaching</a>
            </div>
        </div>
    </section>
</x-layouts.app>
