<x-layouts.app>
    <!-- Store Hero Header -->
    <section class="bg-brand-950 text-white py-10 sm:py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-radial from-brand-800/30 via-transparent to-transparent pointer-events-none"></div>
        <div class="container-x max-w-3xl space-y-3 sm:space-y-4 relative z-10">
            <span class="eyebrow eyebrow-light text-xs sm:text-sm">Apparel & Inspiration</span>
            <h1 class="display-lg text-white text-3xl sm:text-4xl md:text-5xl font-light">
                Thought-Provoking <span class="text-lime-400 italic">Store.</span>
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-white/80 font-light leading-relaxed">
                Wear and share reminders of God's unchanging love, spiritual resilience, and divine purpose.
            </p>
        </div>
    </section>

    <!-- Categories & Search Bar (Sticky Controls) -->
    <section class="py-3.5 sm:py-5 bg-white border-b border-line shadow-xs sticky top-[65px] sm:top-[75px] md:top-[85px] z-30">
        <div class="container-x space-y-3 sm:space-y-4">
            <!-- Top Controls Row: Category Filter Tabs & Integrated Search -->
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 sm:gap-4">
                <!-- Category Tabs Navigation (Horizontal Swipe on Mobile) -->
                <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto scrollbar-none py-1 -mx-4 px-4 sm:mx-0 sm:px-0 touch-pan-x">
                    <a href="{{ route('store.index', request()->only('q')) }}" 
                       class="shrink-0 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-150 {{ !request('category') ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface hover:bg-slate-100 text-slate-700 border border-line' }}">
                        All Products <span class="opacity-75 font-normal ml-1">({{ $totalActiveProducts ?? $products->total() }})</span>
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('store.index', array_merge(request()->only('q'), ['category' => $cat->slug])) }}" 
                           class="shrink-0 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-150 {{ request('category') === $cat->slug ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface hover:bg-slate-100 text-slate-700 border border-line' }}">
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
                            class="form-input pl-10 pr-20 py-2 sm:py-2 text-xs rounded-full border border-line focus:border-brand-500 focus:ring-1 focus:ring-brand-500 w-full bg-surface"
                        >
                        @if(request('q'))
                            <a href="{{ route('store.index', request()->only('category')) }}" class="absolute right-14 text-slate-400 hover:text-slate-600 text-xs p-1" title="Clear search">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                        <button type="submit" class="absolute right-1 px-3 py-1 bg-brand-500 hover:bg-brand-600 text-white text-xs font-semibold rounded-full transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Active Filters / Status Row -->
            @if(request('category') || request('q'))
                <div class="flex items-center justify-between text-xs pt-2 border-t border-line/60 flex-wrap gap-2">
                    <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                        <span class="text-slate-500 font-medium">Active:</span>
                        @if(request('category'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 font-medium text-[0.6875rem] sm:text-xs">
                                <span>Category: <strong>{{ $categories->firstWhere('slug', request('category'))?->name ?? ucfirst(request('category')) }}</strong></span>
                                <a href="{{ route('store.index', request()->only('q')) }}" class="hover:text-brand-900 font-bold ml-1" title="Remove category filter">×</a>
                            </span>
                        @endif
                        @if(request('q'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 font-medium text-[0.6875rem] sm:text-xs">
                                <span>Search: &ldquo;<strong>{{ request('q') }}</strong>&rdquo;</span>
                                <a href="{{ route('store.index', request()->only('category')) }}" class="hover:text-brand-900 font-bold ml-1" title="Remove search filter">×</a>
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('store.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold hover:underline">
                        Reset Filters
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Product Grid Section with 2-Column Approach -->
    <section 
        class="py-6 sm:py-12 bg-white"
        x-data="{ 
            cols: (window.innerWidth < 640 ? '2' : (localStorage.getItem('crm_store_cols') || '4')),
            setCols(c) {
                this.cols = c;
                localStorage.setItem('crm_store_cols', c);
            }
        }"
    >
        <div class="container-x">
            @if($products->count())
                <!-- Bar above products: Product count & Column view toggle (2 Cols vs 4 Cols) -->
                <div class="flex items-center justify-between pb-4 sm:pb-6 text-xs text-slate-500 border-b border-line mb-4 sm:mb-6">
                    <div>
                        Showing <span class="font-semibold text-slate-800">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span> of <span class="font-semibold text-slate-800">{{ $products->total() }}</span> products
                    </div>

                    <!-- View Switcher (2-column vs 4-column layout) -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="hidden sm:inline text-[0.6875rem] uppercase tracking-wider font-semibold text-slate-400">View:</span>
                        <!-- 2 Columns Button -->
                        <button 
                            type="button" 
                            @click="setCols('2')"
                            :class="cols === '2' ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface text-slate-600 hover:bg-slate-100 border border-line'"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-[2px] text-xs font-semibold transition-colors"
                            title="2 Column Grid View"
                        >
                            <svg class="size-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M4 5a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1V5z"/>
                            </svg>
                            <span>2 Cols</span>
                        </button>

                        <!-- 4 Columns / Compact Grid Button (available on tablet/desktop) -->
                        <button 
                            type="button" 
                            @click="setCols('4')"
                            :class="cols === '4' ? 'bg-brand-500 text-white shadow-xs' : 'bg-surface text-slate-600 hover:bg-slate-100 border border-line'"
                            class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-[2px] text-xs font-semibold transition-colors"
                            title="Multi-column Grid View"
                        >
                            <svg class="size-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M3 5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5zm6 0a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V5zm6 0a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V5zm6 0a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V5z"/>
                            </svg>
                            <span>4 Cols</span>
                        </button>
                    </div>
                </div>

                <!-- Products Grid: 2 columns on mobile, configurable 2 or 4 on desktop -->
                <div 
                    :class="cols === '2' ? 'grid grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-6 lg:gap-8' : 'grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8'"
                    class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8 transition-all duration-300"
                >
                    @foreach($products as $product)
                        <div class="card p-2.5 sm:p-4 lg:p-5 flex flex-col justify-between hover:border-brand-500 transition-colors shadow-xs rounded-[2px] bg-white group">
                            <div class="space-y-2 sm:space-y-3">
                                <!-- Product Image Container -->
                                <div class="aspect-square overflow-hidden rounded-[2px] bg-paper relative">
                                    <a href="{{ route('store.show', $product) }}" class="block size-full">
                                        <img 
                                            src="{{ asset($product->imageUrl() ?? 'images/site/journal.jpg') }}" 
                                            alt="{{ $product->name }}" 
                                            loading="lazy"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        >
                                    </a>
                                    @if($product->category)
                                        <span class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-brand-950/75 backdrop-blur-xs text-[0.5625rem] sm:text-[0.625rem] font-bold uppercase tracking-wider text-lime-300 rounded-[2px]">
                                            {{ $product->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="space-y-1">
                                    <span class="text-[0.625rem] sm:text-[0.6875rem] font-bold uppercase tracking-wider text-green-700 truncate block">
                                        {{ $product->category?->name ?? 'Merchandise' }}
                                    </span>
                                    <h3 class="font-display text-xs sm:text-base lg:text-lg font-normal text-ink line-clamp-2 min-h-[2rem] sm:min-h-[2.75rem] leading-snug">
                                        <a href="{{ route('store.show', $product) }}" class="hover:text-brand-500 transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center flex-wrap gap-1.5 pt-0.5">
                                        <span class="font-bold text-xs sm:text-base text-brand-600">
                                            {{ $product->formattedPrice() }}
                                        </span>
                                        @if($product->compare_at_price)
                                            <span class="text-[0.625rem] sm:text-xs text-muted line-through">
                                                ${{ number_format($product->compare_at_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Add to Cart CTA -->
                            <div class="pt-2 sm:pt-4 border-t border-line mt-2 sm:mt-3">
                                <form action="{{ route('store.cart.add', $product) }}" method="POST" x-data="cart" @submit.prevent="submit($event)">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button 
                                        type="submit" 
                                        class="btn btn-outline btn-xs sm:btn-sm w-full py-1.5 sm:py-2 px-2 text-[0.6875rem] sm:text-xs font-semibold tracking-wider transition-all" 
                                        :disabled="loading"
                                    >
                                        <span x-text="added ? 'Added ✓' : 'Add to Cart'"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 sm:mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16 sm:py-20 space-y-4">
                    <p class="text-slate text-base sm:text-lg">No products found matching your search.</p>
                    <a href="{{ route('store.index') }}" class="btn btn-primary btn-sm">Clear Filters</a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
