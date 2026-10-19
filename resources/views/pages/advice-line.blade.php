<x-layouts.app>
    <!-- Hero -->
    <section class="relative min-h-[55vh] flex items-center bg-brand-950 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset(setting('advice_line.hero_image', 'images/site/sunrise-hands.jpg')) }}" alt="Coaching Advice Line" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/80 to-transparent"></div>
        </div>
        <div class="container-x relative z-10 max-w-3xl space-y-6">
            <span class="eyebrow eyebrow-light">Immediate Support Line</span>
            <h1 class="display-lg text-white">
                {{ setting('advice_line.hero_title') ? accent(setting('advice_line.hero_title'), 'text-lime-400 italic') : 'Someone is ready to listen.' }}
            </h1>
            <p class="text-xl text-white/80 font-light leading-relaxed">
                {{ setting('advice_line.hero_text', 'The Coaching Advice Line connects you directly by phone with an experienced Christian coach who will listen attentively to your situation and offer personalized advice tailored to your unique circumstances.') }}
            </p>
            <div class="pt-2">
                <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="btn btn-green">
                    <span>Call Advice Line Now</span>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- What It Is & Scripture Foundation -->
    <section class="section bg-white">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5 reveal">
                    <div class="aspect-4/5 overflow-hidden rounded-[2px] shadow-lift">
                        <img src="{{ asset('images/site/who-we-are.jpg') }}" alt="Listening coach" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="lg:col-span-7 space-y-6 reveal">
                    <span class="eyebrow">Biblical Foundation</span>
                    <h2 class="display-md">"As a man thinketh in his heart, so is he."</h2>
                    <span class="block text-xs font-mono text-green-600 font-bold uppercase tracking-wider">Proverbs 23:7 & John 8:36</span>
                    <div class="prose-crm">
                        {!! setting('advice_line.what_text') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who Can Use & What to Expect -->
    <section class="section bg-paper border-t border-line">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Who can use -->
                <div class="space-y-6">
                    <span class="eyebrow">Who It Is For</span>
                    <h3 class="display-sm">Anyone Facing a River</h3>
                    <ul class="space-y-3">
                        @foreach($whoCanUse as $item)
                            <li class="flex items-start gap-3 text-sm text-slate">
                                <span class="size-2 rounded-full bg-brand-500 shrink-0 mt-1.5"></span>
                                <span>{{ $item['title'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- What to expect -->
                <div class="space-y-6">
                    <span class="eyebrow">What to Expect</span>
                    <h3 class="display-sm">A Compassionate Conversation</h3>
                    <div class="space-y-4">
                        @foreach($expectations as $exp)
                            <div class="p-5 bg-white border border-line rounded-[2px] space-y-1">
                                <span class="font-semibold text-brand-500 text-sm block">{{ $exp['title'] }}</span>
                                <p class="text-xs text-slate">{{ $exp['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How the Consultation Works -->
    <section class="section bg-white border-t border-line">
        <div class="container-x">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <span class="eyebrow mx-auto justify-center">Simple Steps</span>
                <h2 class="display-md">How the Consultation Works</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($steps as $s)
                    <div class="p-6 bg-paper border-t-2 border-green-500 rounded-[2px] space-y-3">
                        <span class="font-display text-3xl font-light text-brand-500">0{{ $loop->iteration }}</span>
                        <h4 class="font-display text-lg font-semibold text-ink">{{ $s['title'] }}</h4>
                        <p class="text-xs text-slate leading-relaxed">{{ $s['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Big Call Banner -->
            <div class="mt-16 p-10 bg-brand-900 text-white text-center rounded-[2px] space-y-6">
                <h3 class="font-display text-3xl font-light text-white">Our coaches eagerly await your call.</h3>
                <p class="text-sm text-white/80 max-w-md mx-auto">Click below to start your direct telephonic consultation session.</p>
                <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="btn btn-green btn-sm px-8 py-4 text-base">
                    <span>Call Now (Webcall)</span>
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
