<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-4xl space-y-4">
            <a href="{{ route('podcasts.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-lime-400 hover:text-white transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Podcast Library</span>
            </a>
            <span class="block text-xs uppercase tracking-wider text-green-400 font-semibold">
                {{ $episode->podcast->title }} · Episode 0{{ $episode->episode_number }}
            </span>
            <h1 class="display-md text-white">{{ $episode->title }}</h1>
            <p class="text-xs text-white/60">Published on {{ $episode->published_at?->format('F d, Y') }} · Duration: {{ $episode->duration }}</p>
        </div>
    </section>

    <section class="section bg-white">
        <div class="container-x max-w-4xl space-y-10">
            <!-- Audio Player Component -->
            <div class="p-8 bg-paper border border-line rounded-[2px] shadow-soft space-y-6" x-data="audioPlayer">
                <audio x-ref="audio" src="{{ $episode->audioSource() }}" preload="metadata"></audio>

                <div class="flex items-center gap-6">
                    <!-- Play / Pause Button -->
                    <button @click="toggle" type="button" class="size-16 rounded-full bg-brand-500 text-white flex items-center justify-center hover:bg-brand-700 transition-colors shadow">
                        <svg x-show="!playing" class="size-7 fill-current ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        <svg x-show="playing" x-cloak class="size-7 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    </button>

                    <div class="flex-grow space-y-2">
                        <!-- Progress Bar Track -->
                        <div @click="seek($event)" class="h-2 w-full bg-line rounded-full cursor-pointer relative overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full transition-all duration-100" :style="`width: ${progress}%`"></div>
                        </div>

                        <!-- Time display -->
                        <div class="flex items-center justify-between text-xs text-muted font-mono">
                            <span x-text="format(current)">0:00</span>
                            <span x-text="format(duration)">{{ $episode->duration }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Episode Description & Notes -->
            <div class="prose-crm">
                {!! $episode->description !!}
            </div>

            <!-- Related Episodes -->
            @if($relatedEpisodes->count())
                <div class="pt-12 border-t border-line space-y-6">
                    <h3 class="font-display text-2xl font-normal text-ink">More from this Series</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedEpisodes as $rel)
                            <div class="card p-5 bg-paper space-y-2">
                                <span class="text-[0.6875rem] font-semibold uppercase text-brand-500">Episode 0{{ $rel->episode_number }}</span>
                                <h4 class="font-display text-base font-normal text-ink">
                                    <a href="{{ route('podcasts.show', $rel) }}" class="hover:text-brand-500">{{ $rel->title }}</a>
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
