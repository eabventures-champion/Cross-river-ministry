<x-layouts.app>
    <section class="bg-brand-950 text-white py-14">
        <div class="container-x max-w-4xl space-y-4">
            <a href="{{ route('videos.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-lime-400 hover:text-white transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Video Library</span>
            </a>
            <h1 class="display-md text-white">{{ $video->title }}</h1>
            <p class="text-xs text-white/60">Published on {{ $video->published_at?->format('F d, Y') }}</p>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x max-w-4xl space-y-10">
            <!-- Responsive Embedded Player -->
            <div class="aspect-video w-full rounded-[2px] overflow-hidden shadow-lift bg-black">
                @if($video->isFile())
                    <video controls class="w-full h-full" poster="{{ asset($video->thumbnailUrl()) }}">
                        <source src="{{ $video->video_url }}" type="video/mp4">
                        Your browser does not support HTML video.
                    </video>
                @else
                    <iframe 
                        src="{{ $video->embedUrl() }}" 
                        title="{{ $video->title }}" 
                        class="w-full h-full border-0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                    ></iframe>
                @endif
            </div>

            <!-- Description -->
            <div class="prose-crm">
                {!! $video->description !!}
            </div>

            <!-- Related Videos -->
            @if($relatedVideos->count())
                <div class="pt-12 border-t border-line space-y-6">
                    <h3 class="font-display text-2xl font-normal text-ink">Related Messages</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedVideos as $rel)
                            <div class="card p-4 bg-paper space-y-2">
                                <div class="aspect-video overflow-hidden rounded-[2px]">
                                    <img src="{{ asset($rel->thumbnailUrl() ?? 'images/site/video-cover.jpg') }}" alt="{{ $rel->title }}" class="w-full h-full object-cover">
                                </div>
                                <h4 class="font-display text-sm font-normal text-ink">
                                    <a href="{{ route('videos.show', $rel) }}" class="hover:text-brand-500">{{ $rel->title }}</a>
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
