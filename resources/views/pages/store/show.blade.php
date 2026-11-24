<x-layouts.app>
    <section class="py-12 bg-white" x-data="{ showImageModal: false }">
        <div class="container-x">
            <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-wider text-brand-500 hover:text-green-600 mb-8 transition-colors">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Store</span>
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Product Image -->
                <div class="lg:col-span-6">
                    <div 
                        @click="showImageModal = true"
                        class="aspect-square rounded-[2px] overflow-hidden bg-paper border border-line shadow-soft relative group cursor-pointer"
                        title="Click to zoom image"
                    >
                        <img src="{{ asset($product->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <span class="inline-flex items-center gap-2 bg-white/95 text-ink text-xs font-semibold px-4 py-2 rounded-full shadow-lift backdrop-blur-xs">
                                <svg class="size-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                </svg>
                                <span>Click to Zoom</span>
                            </span>
                        </div>
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

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="btn btn-primary w-full py-3.5 sm:py-4" :disabled="loading || {{ $product->stock <= 0 ? 'true' : 'false' }}">
                                <span x-text="added ? 'Added to Cart ✓' : 'Add to Cart'"></span>
                            </button>
                            <a href="{{ route('store.cart') }}" class="btn btn-outline w-full py-3.5 sm:py-4">
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

        <!-- Lightbox Zoom Modal -->
        <div 
            x-show="showImageModal" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/85 backdrop-blur-sm"
            @keydown.escape.window="showImageModal = false"
        >
            <div 
                x-show="showImageModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="showImageModal = false"
                class="bg-white rounded-[2px] shadow-2xl max-w-3xl w-full overflow-hidden border border-slate-200 flex flex-col"
            >
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-line bg-surface">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-brand-500 font-semibold">{{ $product->category?->name }}</span>
                        <h3 class="font-display text-base font-semibold text-ink">{{ $product->name }}</h3>
                    </div>
                    <button 
                        type="button" 
                        @click="showImageModal = false" 
                        class="size-8 rounded-[2px] text-slate hover:text-ink hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                    >
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="relative bg-slate-900/5 p-4 sm:p-8 flex items-center justify-center min-h-[350px] max-h-[75vh] overflow-hidden">
                    <img 
                        src="{{ asset($product->imageUrl() ?? 'images/site/journal.jpg') }}" 
                        alt="{{ $product->name }}" 
                        class="max-h-[65vh] max-w-full object-contain rounded-[2px] shadow-md select-none"
                    >
                </div>
                <div class="flex items-center justify-between px-5 py-3 border-t border-line bg-white">
                    <span class="font-display text-lg font-bold text-brand-500">{{ $product->formattedPrice() }}</span>
                    <button 
                        type="button" 
                        @click="showImageModal = false" 
                        class="btn btn-secondary btn-sm px-4 text-xs cursor-pointer"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
