<x-layouts.app>
    <section class="py-16 bg-paper min-h-[60vh]"
             x-data="{
                 items: [
                     @foreach($lines as $line)
                         {
                             id: {{ $line['product']->id }},
                             price: {{ (float) $line['product']->price }},
                             quantity: {{ (int) $line['quantity'] }},
                             stock: {{ (int) $line['product']->stock }},
                             updateUrl: '{{ route('store.cart.update', $line['product']) }}',
                             removeUrl: '{{ route('store.cart.remove', $line['product']) }}',
                         },
                     @endforeach
                 ],
                 get total() {
                     return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                 },
                 get count() {
                     return this.items.reduce((sum, item) => sum + item.quantity, 0);
                 },
                 formatMoney(amount) {
                     return '$' + Number(amount).toFixed(2);
                 },
                 async changeQuantity(item, newQty) {
                     newQty = Math.max(1, Math.min(parseInt(newQty) || 1, item.stock || 9999));
                     item.quantity = newQty;
                     try {
                         const form = new FormData();
                         form.append('_token', '{{ csrf_token() }}');
                         form.append('quantity', newQty);
                         const res = await fetch(item.updateUrl, {
                             method: 'POST',
                             headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                             body: form
                         });
                         const data = await res.json();
                         window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                     } catch (e) {
                         // Fallback is silent as local state already computed
                     }
                 },
                 async removeItem(index) {
                     const item = this.items[index];
                     if (!item) return;
                     this.items.splice(index, 1);
                     try {
                         const form = new FormData();
                         form.append('_token', '{{ csrf_token() }}');
                         const res = await fetch(item.removeUrl, {
                             method: 'POST',
                             headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                             body: form
                         });
                         const data = await res.json();
                         window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                     } catch (e) {
                         window.location.reload();
                     }
                 }
             }">
        <div class="container-x max-w-4xl">
            <h1 class="display-md mb-8">Shopping <span class="accent-italic">Cart.</span></h1>

            <!-- Non-empty Cart -->
            <div x-show="items.length > 0" class="bg-white border border-line rounded-[2px] shadow-soft divide-y divide-line">
                @foreach($lines as $index => $line)
                    <div class="p-6 flex flex-col sm:flex-row items-center justify-between gap-6"
                         x-show="items[{{ $index }}]"
                         x-transition>
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="size-20 shrink-0 bg-paper rounded-[2px] overflow-hidden">
                                <img src="{{ asset($line['product']->imageUrl() ?? 'images/site/journal.jpg') }}" alt="{{ $line['product']->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-display text-lg font-normal text-ink">
                                    <a href="{{ route('store.show', $line['product']) }}" class="hover:text-brand-500">{{ $line['product']->name }}</a>
                                </h3>
                                <span class="text-xs text-muted block">{{ $line['product']->formattedPrice() }} each</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                            <!-- Reactive Quantity Input -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border border-line rounded-[2px] overflow-hidden bg-white">
                                    <button type="button"
                                            @click="changeQuantity(items[{{ $index }}], items[{{ $index }}].quantity - 1)"
                                            class="px-2.5 py-1.5 text-slate hover:bg-slate-100 transition-colors text-xs font-semibold focus:outline-none"
                                            title="Decrease quantity">
                                        −
                                    </button>
                                    <input type="number"
                                           x-model.number="items[{{ $index }}].quantity"
                                           @change="changeQuantity(items[{{ $index }}], $event.target.value)"
                                           @input="items[{{ $index }}].quantity = Math.max(1, parseInt($event.target.value) || 1)"
                                           min="1"
                                           max="{{ $line['product']->stock }}"
                                           class="w-12 text-center text-xs font-semibold py-1.5 border-x border-line focus:outline-none focus:ring-0">
                                    <button type="button"
                                            @click="changeQuantity(items[{{ $index }}], items[{{ $index }}].quantity + 1)"
                                            class="px-2.5 py-1.5 text-slate hover:bg-slate-100 transition-colors text-xs font-semibold focus:outline-none"
                                            title="Increase quantity">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Reactive Line Subtotal -->
                            <span class="font-bold text-brand-500 w-24 text-right"
                                  x-text="formatMoney(items[{{ $index }}].price * items[{{ $index }}].quantity)">
                                ${{ number_format($line['subtotal'], 2) }}
                            </span>

                            <!-- Remove Button -->
                            <button type="button"
                                    @click="removeItem({{ $index }})"
                                    class="text-red-500 hover:text-red-700 p-1.5 rounded-[2px] hover:bg-red-50 transition-colors"
                                    title="Remove item">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Total & Checkout Action -->
                <div class="p-8 bg-paper flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <span class="text-xs uppercase tracking-wider text-muted font-semibold block">Order Subtotal</span>
                        <span class="font-display text-3xl font-bold text-brand-500" x-text="formatMoney(total)">
                            ${{ number_format($total, 2) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('store.index') }}" class="btn btn-outline">Continue Shopping</a>
                        <a href="{{ route('store.checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
                    </div>
                </div>
            </div>

            <!-- Empty Cart Fallback -->
            <div x-show="items.length === 0" x-cloak class="card p-14 text-center space-y-4">
                <p class="text-slate text-lg">Your shopping cart is currently empty.</p>
                <a href="{{ route('store.index') }}" class="btn btn-primary">Browse Thought-Provoking Store</a>
            </div>
        </div>
    </section>
</x-layouts.app>
