<x-layouts.app>
    <section class="bg-brand-950 text-white py-20">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Core Disciplines</span>
            <h1 class="display-lg text-white">Life Coaching <span class="text-lime-400 italic">Services.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Discover our five specialized coaching disciplines designed to help individuals, families, and professionals experience purpose, restoration, and fulfillment in the here and now.
            </p>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="section bg-white">
        <div class="container-x space-y-20">
            @foreach($services as $service)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center {{ $loop->even ? 'lg:flex-row-reverse' : '' }}">
                    <div class="lg:col-span-5 {{ $loop->even ? 'lg:order-2' : '' }} reveal">
                        <div class="aspect-4/3 overflow-hidden rounded-[2px] shadow-lift">
                            <img src="{{ asset($service->imageUrl() ?? 'images/site/spiritual.jpg') }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="lg:col-span-7 {{ $loop->even ? 'lg:order-1' : '' }} space-y-6 reveal">
                        <span class="eyebrow">Discipline 0{{ $loop->iteration }}</span>
                        <h2 class="display-md text-ink">{{ $service->title }}</h2>
                        <p class="font-medium text-brand-500 text-sm uppercase tracking-wide">{{ $service->tagline }}</p>
                        <p class="text-slate leading-relaxed">{{ $service->excerpt }}</p>

                        @if(!empty($service->benefits))
                            <div class="space-y-2 pt-2">
                                <span class="block text-xs font-semibold uppercase tracking-wider text-ink">Key Transformation Outcomes:</span>
                                <ul class="space-y-1.5 text-xs text-slate">
                                    @foreach(array_slice($service->benefits, 0, 3) as $benefit)
                                        <li class="flex items-center gap-2">
                                            <span class="size-1.5 rounded-full bg-green-500"></span>
                                            <span>{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="pt-4 flex items-center gap-4">
                            <a href="{{ route('coaching.show', $service) }}" class="btn btn-primary">
                                <span>Learn More & Enroll</span>
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                            <a href="{{ route('advice-line') }}" class="btn btn-outline">
                                <span>Discuss with a Coach</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Advice Line CTA Section -->
    <section class="section bg-paper border-t border-line text-center">
        <div class="container-narrow space-y-6">
            <span class="eyebrow mx-auto justify-center">Find Your Direction</span>
            <h2 class="display-md">Not sure which coaching area fits your current situation?</h2>
            <p class="text-slate">Our coaches are available via our dedicated Coaching Advice Line to listen, pray, and help you determine your optimal next step.</p>
            <div class="pt-4 flex justify-center gap-4">
                <a href="{{ route('advice-line') }}" class="btn btn-green">Go to Advice Line</a>
                <a href="{{ route('contact') }}" class="btn btn-outline">Send an Inquiry</a>
            </div>
        </div>
    </section>
</x-layouts.app>
