<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Visual Media Library</span>
            <h1 class="display-lg text-white">Video <span class="text-lime-400 italic">Messages.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Watch encouraging sermons, coaching reflections, and practical guidance from Jane Audrey and our ministry leaders.
            </p>
        </div>
    </section>

    <!-- Search & Categories -->
    <section class="py-8 bg-paper border-b border-line">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('videos.index') }}" method="GET" class="w-full md:w-96 flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search videos by title..." class="form-input rounded-r-none border-r-0 text-sm">
                <button type="submit" class="btn btn-primary rounded-l-none px-4 py-2">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </form>

            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <a href="{{ route('videos.index') }}" class="tag {{ !request('category') ? 'tag-active' : '' }}">All Videos</a>
                @foreach($categories as $c)
                    <a href="{{ route('videos.index', ['category' => $c->slug]) }}" class="tag {{ request('category') === $c->slug ? 'tag-active' : '' }}">
                        {{ $c->name }} ({{ $c->videos_count }})
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Video Grid -->
    <section class="section bg-white">
        <div class="container-x">
            @if($videos->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($videos as $video)
                        <div class="card p-6 flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                            <div class="space-y-4">
                                <div class="aspect-video overflow-hidden rounded-[2px] relative group">
                                    <img src="{{ asset($video->thumbnailUrl() ?? 'images/site/video-cover.jpg') }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-brand-950/30 flex items-center justify-center group-hover:bg-brand-950/20 transition-colors">
                                        <div class="size-11 rounded-full bg-white text-brand-500 flex items-center justify-center shadow">
                                            <svg class="size-5 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                    @if($video->duration)
                                        <div class="absolute bottom-2 right-2 bg-brand-950/80 text-white text-[0.6875rem] font-mono px-2 py-0.5 rounded-[2px]">
                                            {{ $video->duration }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    @if($video->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-green-600">{{ $video->category->name }}</span>
                                    @endif
                                    <h3 class="font-display text-xl font-normal text-ink mt-1 hover:text-brand-500 transition-colors">
                                        <a href="{{ route('videos.show', $video) }}">{{ $video->title }}</a>
                                    </h3>
                                </div>
                                <p class="text-xs text-slate line-clamp-3 leading-relaxed">{{ $video->summary() }}</p>
                            </div>

                            <div class="pt-4 border-t border-line mt-4">
                                <a href="{{ route('videos.show', $video) }}" class="link-arrow">
                                    <span>Watch Video</span>
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $videos->links() }}
                </div>
            @else
                <div class="text-center py-20 space-y-4">
                    <p class="text-slate text-lg">No video messages found.</p>
                    <a href="{{ route('videos.index') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
