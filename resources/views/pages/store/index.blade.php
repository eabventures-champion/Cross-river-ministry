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

    <!-- Categories & Search -->
    <section class="py-8 bg-paper border-b border-line">
        <div class="container-x flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('store.index') }}" method="GET" class="w-full md:w-96 flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="form-input rounded-r-none border-r-0 text-sm">
                <button type="submit" class="btn btn-primary rounded-l-none px-4 py-2">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </form>

            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <a href="{{ route('store.index') }}" class="tag {{ !request('category') ? 'tag-active' : '' }}">All Products</a>
                @foreach($categories as $cat)
                    <a href="{{ route('store.index', ['category' => $cat->slug]) }}" class="tag {{ request('category') === $cat->slug ? 'tag-active' : '' }}">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                @endforeach
            </div>
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
