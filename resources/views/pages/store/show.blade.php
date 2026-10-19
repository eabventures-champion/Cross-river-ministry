<x-layouts.app>
    <section class="py-12 bg-white">
        <div class="container-x">
            <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-brand-500 hover:text-green-600 mb-8 transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Store</span>
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Product Image -->
                <div class="lg:col-span-6">
                    <div class="aspect-square rounded-[2px] overflow-hidden bg-paper border border-line shadow-soft">
                        <img src="{{ asset($product->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Product Details & Cart Action -->
                <div class="lg:col-span-6 space-y-6">
                    <span class="eyebrow">{{ $product->category?->name }}</span>
                    <h1 class="display-md text-ink">{{ $product->name }}</h1>

                    <div class="flex items-center gap-3">
                        <span class="font-display text-3xl font-semibold text-brand-500">{{ $product->formattedPrice() }}</span>
                        @if($product->compare_at_price)
                            <span class="text-base text-muted line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                        @endif
                        <span class="text-xs px-2.5 py-1 bg-green-50 text-green-700 font-semibold rounded-[2px]">
                            {{ $product->stockLabel() }}
                        </span>
                    </div>

                    <p class="text-sm text-slate leading-relaxed">{{ $product->excerpt }}</p>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('store.cart.add', $product) }}" method="POST" class="space-y-4 pt-4 border-t border-line" x-data="cart" @submit.prevent="submit($event)">
                        @csrf
                        <div class="flex items-center gap-4">
                            <label class="text-xs font-semibold uppercase text-ink">Quantity</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}" class="w-20 form-input py-2 text-center text-sm">
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <button type="submit" class="btn btn-primary w-full py-4" :disabled="loading || {{ $product->stock <= 0 ? 'true' : 'false' }}">
                                <span x-text="added ? 'Added to Cart ✓' : 'Add to Cart'"></span>
                            </button>
                            <a href="{{ route('store.cart') }}" class="btn btn-outline w-full py-4">
                                <span>View Cart</span>
                            </a>
                        </div>
                    </form>

                    <!-- Detailed Description -->
                    <div class="pt-8 border-t border-line prose-crm text-sm">
                        <h3 class="font-display text-xl font-normal text-ink mb-2">Product Description</h3>
                        {!! $product->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
