<x-layouts.app>
    <section class="bg-brand-950 text-white py-16">
        <div class="container-x max-w-3xl space-y-4">
            <span class="eyebrow eyebrow-light">Apparel & Inspiration</span>
            <h1 class="display-lg text-white">Thought-Provoking <span class="text-lime-400 italic">Store.</span></h1>
            <p class="text-lg text-white/80 font-light">
                Wear and share reminders of God's unchanging love, spiritual resilience, and divine purpose.
            </p>
        </div>
    </section>

    <!-- Categories & Search Bar -->
    <section class="py-6 bg-white border-b border-line shadow-xs">
        <div class="container-x space-y-4">
            <!-- Top Controls Row: Category Filter Tabs & Integrated Search -->
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <!-- Category Tabs Navigation -->
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-none py-1">
                    <a href="{{ route('store.index', request()->only('q')) }}" 
                       class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-150 {{ !request('category') ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface hover:bg-slate-100 text-slate-700 border border-line' }}">
                        All Products <span class="opacity-75 font-normal ml-1">({{ $totalActiveProducts ?? $products->total() }})</span>
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('store.index', array_merge(request()->only('q'), ['category' => $cat->slug])) }}" 
                           class="px-4 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-150 {{ request('category') === $cat->slug ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface hover:bg-slate-100 text-slate-700 border border-line' }}">
                            {{ $cat->name }} <span class="opacity-75 font-normal ml-1">({{ $cat->products_count }})</span>
                        </a>
                    @endforeach
                </div>

                <!-- Search Input Form -->
                <form action="{{ route('store.index') }}" method="GET" class="relative w-full lg:w-80 shrink-0">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ request('q') }}" 
                            placeholder="Search products..." 
                            class="form-input pl-10 pr-20 py-2 text-xs rounded-full border border-line focus:border-brand-500 focus:ring-1 focus:ring-brand-500 w-full bg-surface"
                        >
                        @if(request('q'))
                            <a href="{{ route('store.index', request()->only('category')) }}" class="absolute right-12 text-slate-400 hover:text-slate-600 text-xs p-1" title="Clear search">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                        <button type="submit" class="absolute right-1 px-3 py-1 bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold rounded-full transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Filters / Status Row (visible when filtering or searching) -->
            @if(request('category') || request('q'))
                <div class="flex items-center justify-between text-xs pt-2 border-t border-line/60">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-slate-500 font-medium">Active Filters:</span>
                        @if(request('category'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 font-medium">
                                <span>Category: <strong>{{ $categories->firstWhere('slug', request('category'))?->name ?? ucfirst(request('category')) }}</strong></span>
                                <a href="{{ route('store.index', request()->only('q')) }}" class="hover:text-brand-900 font-bold ml-0.5" title="Remove category filter">×</a>
                            </span>
                        @endif
                        @if(request('q'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 font-medium">
                                <span>Search: &ldquo;<strong>{{ request('q') }}</strong>&rdquo;</span>
                                <a href="{{ route('store.index', request()->only('category')) }}" class="hover:text-brand-900 font-bold ml-0.5" title="Remove search filter">×</a>
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('store.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold hover:underline">
                        Reset All Filters
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Product Grid -->
    <section class="section bg-white">
        <div class="container-x">
            @if($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($products as $product)
                        <div class="card p-5 flex flex-col justify-between hover:border-brand-500 transition-colors reveal">
                            <div class="space-y-4">
                                <div class="aspect-square overflow-hidden rounded-[2px] bg-paper">
                                    <a href="{{ route('store.show', $product) }}">
                                        <img src="{{ asset($product->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    </a>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[0.6875rem] font-semibold uppercase tracking-wider text-green-600">{{ $product->category?->name }}</span>
                                    <h3 class="font-display text-lg font-normal text-ink">
                                        <a href="{{ route('store.show', $product) }}" class="hover:text-brand-500">{{ $product->name }}</a>
                                    </h3>
                                    <div class="flex items-center gap-2 pt-1">
                                        <span class="font-bold text-brand-500">{{ $product->formattedPrice() }}</span>
                                        @if($product->compare_at_price)
                                            <span class="text-xs text-muted line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-line mt-4">
                                <form action="{{ route('store.cart.add', $product) }}" method="POST" x-data="cart" @submit.prevent="submit($event)">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-outline btn-sm w-full" :disabled="loading">
                                        <span x-text="added ? 'Added to Cart ✓' : 'Add to Cart'"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 space-y-4">
                    <p class="text-slate text-lg">No products found.</p>
                    <a href="{{ route('store.index') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
