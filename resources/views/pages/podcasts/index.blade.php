<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Audio Messages & Discussions</span>
            <h1 class="display-lg text-white">Podcasts <span class="text-lime-400 italic">Library.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Listen to practical biblical insights, interviews, and coaching conversations to help you cross over life's deepest challenges.
            </p>
        </div>
    </section>

    <!-- Search & Filters -->
    <section class="py-8 bg-paper border-b border-line">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Search -->
            <form action="{{ route('podcasts.index') }}" method="GET" class="w-full md:w-96 flex">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Search episode topics, scriptures..." 
                    class="form-input rounded-r-none border-r-0 text-sm"
                >
                <button type="submit" class="btn btn-primary rounded-l-none px-4 py-2">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </form>

            <!-- Show Filter -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <a href="{{ route('podcasts.index') }}" class="tag {{ !request('podcast') ? 'tag-active' : '' }}">All Series</a>
                @foreach($podcasts as $p)
                    <a href="{{ route('podcasts.index', ['podcast' => $p->slug]) }}" class="tag {{ request('podcast') === $p->slug ? 'tag-active' : '' }}">
                        {{ $p->title }} ({{ $p->episodes_count }})
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Episodes Grid -->
    <section class="section bg-white">
        <div class="container-x">
            @if($episodes->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($episodes as $episode)
                        <div class="card p-6 flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                            <div class="space-y-4">
                                <div class="aspect-square overflow-hidden rounded-[2px] relative group">
                                    <img src="{{ asset($episode->artworkUrl() ?? 'images/site/podcast.jpg') }}" alt="{{ $episode->title }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-2 right-2 bg-brand-950/80 text-white text-[0.6875rem] font-mono px-2 py-0.5 rounded-[2px]">
                                        {{ $episode->duration }}
                                    </div>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-green-600">Episode 0{{ $episode->episode_number }}</span>
                                    <h3 class="font-display text-xl font-normal text-ink mt-1 hover:text-brand-500 transition-colors">
                                        <a href="{{ route('podcasts.show', $episode) }}">{{ $episode->title }}</a>
                                    </h3>
                                </div>
                                <p class="text-xs text-slate line-clamp-3 leading-relaxed">{{ $episode->summary() }}</p>
                            </div>

                            <div class="pt-4 border-t border-line mt-4 flex items-center justify-between">
                                <a href="{{ route('podcasts.show', $episode) }}" class="link-arrow">
                                    <span>Listen</span>
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                                <span class="text-[0.6875rem] text-muted">{{ $episode->published_at?->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $episodes->links() }}
                </div>
            @else
                <div class="text-center py-20 space-y-4">
                    <p class="text-slate text-lg">No podcast episodes found matching your criteria.</p>
                    <a href="{{ route('podcasts.index') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
