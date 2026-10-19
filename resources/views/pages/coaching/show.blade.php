<x-layouts.app>
    <section class="bg-brand-950 text-white py-20">
        <div class="container-x max-w-4xl space-y-6">
            <a href="{{ route('coaching.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-lime-400 hover:text-white transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Coaching Disciplines</span>
            </a>
            <h1 class="display-lg text-white">{{ $service->title }}</h1>
            <p class="text-xl text-white/80 font-light leading-relaxed">{{ $service->tagline }}</p>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Main Body -->
                <div class="lg:col-span-8 space-y-10">
                    <div class="aspect-video overflow-hidden rounded-[2px] shadow-lift">
                        <img src="{{ asset($service->imageUrl() ?? 'images/site/spiritual.jpg') }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>

                    <div class="prose-crm">
                        {!! $service->description !!}
                    </div>

                    @if(!empty($service->benefits))
                        <div class="p-8 bg-paper border border-line rounded-[2px] space-y-4">
                            <h3 class="font-display text-2xl font-normal text-ink">What You Will Gain</h3>
                            <ul class="space-y-3">
                                @foreach($service->benefits as $b)
                                    <li class="flex items-start gap-3 text-sm text-slate">
                                        <svg class="size-5 text-green-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span>{{ $b }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($service->audience))
                        <div class="p-8 bg-brand-50 border border-brand-100 rounded-[2px] space-y-4">
                            <h3 class="font-display text-2xl font-normal text-brand-900">Who This Coaching Is For</h3>
                            <ul class="space-y-3">
                                @foreach($service->audience as $a)
                                    <li class="flex items-start gap-3 text-sm text-slate">
                                        <span class="size-2 rounded-full bg-brand-500 shrink-0 mt-1.5"></span>
                                        <span>{{ $a }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Action -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="card p-8 bg-paper border border-line space-y-6 sticky top-24">
                        <span class="eyebrow">Get Started</span>
                        <h3 class="font-display text-2xl font-normal text-ink">Ready to begin this coaching track?</h3>
                        <p class="text-xs text-slate leading-relaxed">
                            Connect with Ms. Jane Audrey and our certified coaching team to start your personalized journey.
                        </p>
                        <div class="space-y-3 pt-2">
                            <a href="{{ route('contact', ['subject' => 'Inquiry for ' . $service->title]) }}" class="btn btn-primary w-full">
                                <span>Schedule a Consultation</span>
                            </a>
                            <a href="{{ setting('general.advice_line_url', 'http://webcall.paypercall.com/00003825') }}" target="_blank" rel="noopener" class="btn btn-green w-full">
                                <span>Call Advice Line Now</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
