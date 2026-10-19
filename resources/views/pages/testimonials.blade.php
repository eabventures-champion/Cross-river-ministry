<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">True Stories of Transformation</span>
            <h1 class="display-lg text-white">Client <span class="text-lime-400 italic">Testimonials.</span></h1>
            <p class="text-lg text-white/80 font-light">
                "Thanks be to God! He gives us victory through our Lord Jesus Christ." — 1 Corinthians 15:57
            </p>
        </div>
    </section>

    <!-- Featured Story -->
    @if($featured)
        <section class="py-14 bg-brand-50 border-b border-line">
            <div class="container-x max-w-4xl">
                <div class="bg-white p-8 md:p-14 border border-line shadow-soft space-y-6">
                    <span class="text-xs font-semibold uppercase tracking-wider text-green-600 bg-green-50 px-3 py-1 rounded-[2px]">Featured Testimony</span>
                    <h2 class="font-display text-2xl md:text-3xl font-light text-ink leading-snug">
                        "{{ $featured->headline ?? 'A Life-Changing Breakthrough' }}"
                    </h2>
                    <blockquote class="text-slate leading-relaxed font-light text-base md:text-lg">
                        {{ $featured->content }}
                    </blockquote>
                    <div class="pt-4 border-t border-line flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-brand-500 block">{{ $featured->name }}</span>
                            <span class="text-xs text-muted">{{ $featured->location }}</span>
                        </div>
                        <span class="text-xs font-mono text-muted">{{ $featured->category }}</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonials Editorial Wall -->
    <section class="section bg-white">
        <div class="container-x">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($testimonials as $t)
                    <div class="card p-8 bg-paper flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                        <div class="space-y-4">
                            @if($t->category)
                                <span class="text-xs font-semibold uppercase tracking-wider text-green-600">{{ $t->category }}</span>
                            @endif
                            @if($t->headline)
                                <h3 class="font-display text-xl font-normal text-ink">{{ $t->headline }}</h3>
                            @endif
                            <p class="text-sm text-slate leading-relaxed font-light">{{ $t->content }}</p>
                        </div>
                        <div class="pt-4 border-t border-line mt-6 flex items-center justify-between text-xs">
                            <span class="font-bold text-brand-500">{{ $t->name }}</span>
                            <span class="text-muted">{{ $t->location }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Share Your Testimony CTA -->
            <div class="mt-20 p-12 bg-surface border border-line text-center max-w-2xl mx-auto space-y-4 rounded-[2px]">
                <h3 class="font-display text-2xl font-normal text-ink">Has Cross Rivers Ministries impacted your life?</h3>
                <p class="text-xs text-slate">We invite you to share your story or testimony to encourage others stepping into their own crossing.</p>
                <a href="{{ route('contact', ['subject' => 'My Testimony for Cross Rivers Ministries']) }}" class="btn btn-outline btn-sm">
                    <span>Share Your Testimony</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
