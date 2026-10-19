<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Biblical Wisdom & Reflections</span>
            <h1 class="display-lg text-white">The Ministry <span class="text-lime-400 italic">Blog.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Articles, practical advice, and spiritual teachings to strengthen your daily walk and help you cross life's rivers.
            </p>
        </div>
    </section>

    <!-- Search & Categories -->
    <section class="py-8 bg-paper border-b border-line">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('blog.index') }}" method="GET" class="w-full md:w-96 flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search articles..." class="form-input rounded-r-none border-r-0 text-sm">
                <button type="submit" class="btn btn-primary rounded-l-none px-4 py-2">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </form>

            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <a href="{{ route('blog.index') }}" class="tag {{ !request('category') ? 'tag-active' : '' }}">All Articles</a>
                @foreach($categories as $c)
                    <a href="{{ route('blog.index', ['category' => $c->slug]) }}" class="tag {{ request('category') === $c->slug ? 'tag-active' : '' }}">
                        {{ $c->name }} ({{ $c->posts_count }})
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="section bg-white">
        <div class="container-x">
            @if($posts->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="card p-6 flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                            <div class="space-y-4">
                                <div class="aspect-16/10 overflow-hidden rounded-[2px]">
                                    <img src="{{ asset($post->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[0.6875rem] font-semibold uppercase tracking-wider">
                                        <span class="text-green-600">{{ $post->category?->name ?? 'Article' }}</span>
                                        <span class="text-muted">{{ $post->readingTime() }} min read</span>
                                    </div>
                                    <h2 class="font-display text-2xl font-normal text-ink leading-snug hover:text-brand-500 transition-colors">
                                        <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                                    </h2>
                                </div>
                                <p class="text-xs text-slate line-clamp-3 leading-relaxed">{{ $post->summary() }}</p>
                            </div>

                            <div class="pt-4 border-t border-line mt-6 flex items-center justify-between text-xs text-muted">
                                <span>{{ $post->author?->name ?? 'Jane Audrey' }}</span>
                                <span>{{ $post->published_at?->format('M d, Y') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-20 space-y-4">
                    <p class="text-slate text-lg">No articles found matching your criteria.</p>
                    <a href="{{ route('blog.index') }}" class="btn btn-primary btn-sm">View All Articles</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
