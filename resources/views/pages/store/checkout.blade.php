<x-layouts.app>
    <section class="py-16 bg-paper min-h-[60vh]">
        <div class="container-x max-w-4xl">
            <h1 class="display-md mb-8">Checkout <span class="accent-italic">Order.</span></h1>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Shipping & Contact Form -->
                <div class="lg:col-span-7 card p-8 bg-white border border-line">
                    <form action="{{ route('store.checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        <h3 class="font-display text-xl font-normal text-ink border-b border-line pb-3">Shipping & Customer Information</h3>

                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" required class="form-input" value="{{ old('name') }}">
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" required class="form-input" value="{{ old('email') }}">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Delivery Address (Street, City, State, ZIP) *</label>
                            <textarea name="address" rows="3" required class="form-input">{{ old('address') }}</textarea>
                            @error('address')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Order Notes / Devotional Dedication</label>
                            <textarea name="notes" rows="2" class="form-input" placeholder="Optional notes for our team...">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-green w-full py-4 text-base">
                            <span>Place Order (${{ number_format($total, 2) }})</span>
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="card p-6 bg-white border border-line space-y-4">
                        <h3 class="font-display text-xl font-normal text-ink border-b border-line pb-3">Order Summary</h3>
                        <div class="divide-y divide-line text-sm">
                            @foreach($lines as $line)
                                <div class="py-3 flex justify-between gap-4">
                                    <div>
                                        <span class="font-medium text-ink block">{{ $line['product']->name }}</span>
                                        <span class="text-xs text-muted">Qty: {{ $line['quantity'] }}</span>
                                    </div>
                                    <span class="font-semibold text-brand-500">${{ number_format($line['subtotal'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4 border-t border-line flex justify-between items-center">
                            <span class="font-display text-lg font-normal text-ink">Total Due:</span>
                            <span class="font-display text-2xl font-bold text-brand-500">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
