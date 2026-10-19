<x-layouts.app>
    <article class="py-16 bg-white">
        <!-- Article Header -->
        <header class="container-narrow space-y-6 text-center mb-12">
            <div class="flex items-center justify-center gap-3 text-xs font-semibold uppercase tracking-wider text-green-600">
                <a href="{{ route('blog.index', ['category' => $post->category?->slug]) }}" class="hover:underline">
                    {{ $post->category?->name ?? 'Insight' }}
                </a>
                <span>·</span>
                <span class="text-muted">{{ $post->readingTime() }} min read</span>
            </div>

            <h1 class="display-lg text-ink font-normal leading-tight">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-center gap-4 text-xs text-muted pt-2 border-t border-line max-w-xs mx-auto">
                <span class="font-medium text-slate">{{ $post->author?->name ?? 'Jane Audrey' }}</span>
                <span>·</span>
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F d, Y') }}</time>
            </div>
        </header>

        <!-- Featured Image -->
        <div class="container-x max-w-4xl mb-14">
            <div class="aspect-16/9 overflow-hidden rounded-[2px] shadow-lift">
                <img src="{{ asset($post->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Article Content Body -->
        <div class="container-narrow prose-crm">
            {!! $post->body !!}

            <!-- Author Bio Card -->
            <div class="mt-16 p-8 bg-paper border border-line rounded-[2px] flex flex-col sm:flex-row items-center gap-6 not-prose">
                <div class="size-20 shrink-0 rounded-full overflow-hidden border-2 border-green-500">
                    <img src="{{ asset($post->author?->avatarUrl() ?? 'images/site/who-we-are.jpg') }}" alt="{{ $post->author?->name }}" class="w-full h-full object-cover">
                </div>
                <div class="space-y-1 text-center sm:text-left">
                    <span class="text-xs uppercase tracking-wider text-muted font-semibold">About the Author</span>
                    <h3 class="font-display text-lg font-semibold text-ink">{{ $post->author?->name ?? 'Jane Audrey' }}</h3>
                    <p class="text-xs text-slate leading-relaxed">{{ $post->author?->bio ?? 'Lead Spiritual Coach and founder of Cross Rivers Ministries.' }}</p>
                </div>
            </div>

            <!-- Share Buttons & Back Link -->
            <div class="mt-10 pt-6 border-t border-line flex flex-col sm:flex-row items-center justify-between gap-4 not-prose" x-data="share('{{ url()->current() }}', '{{ addslashes($post->title) }}')">
                <a href="{{ route('blog.index') }}" class="link-arrow">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    <span>Back to all articles</span>
                </a>
                <div class="flex items-center gap-3">
                    <button @click="native" type="button" class="btn btn-outline btn-sm">
                        <span x-text="copied ? 'Link Copied!' : 'Share Article'"></span>
                    </button>
                </div>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    @if($relatedPosts->count())
        <section class="section bg-paper border-t border-line">
            <div class="container-x">
                <h3 class="display-sm mb-8">Related Articles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $rel)
                        <div class="card p-6 bg-white space-y-3">
                            <span class="text-[0.6875rem] font-semibold uppercase text-green-600">{{ $rel->category?->name }}</span>
                            <h4 class="font-display text-xl font-normal text-ink">
                                <a href="{{ route('blog.show', $rel) }}" class="hover:text-brand-500">{{ $rel->title }}</a>
                            </h4>
                            <p class="text-xs text-slate line-clamp-2">{{ $rel->summary() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
