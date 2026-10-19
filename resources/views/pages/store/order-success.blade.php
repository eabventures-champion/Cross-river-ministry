<x-layouts.app>
    <section class="py-20 bg-white min-h-[65vh] flex items-center">
        <div class="container-narrow text-center space-y-6">
            <div class="size-16 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto shadow-sm">
                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>

            <span class="eyebrow mx-auto justify-center">Order Confirmed</span>
            <h1 class="display-lg text-ink font-light">Thank You for Your Order!</h1>
            <p class="text-slate max-w-md mx-auto leading-relaxed">
                Your order reference is <strong class="text-brand-500">{{ $order->reference }}</strong>. We have sent a confirmation details receipt to <strong class="text-ink">{{ $order->email }}</strong>.
            </p>

            <div class="p-6 bg-paper border border-line rounded-[2px] max-w-md mx-auto text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-muted">Customer:</span>
                    <span class="font-medium text-ink">{{ $order->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Total:</span>
                    <span class="font-bold text-brand-500">${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-muted">Status:</span>
                    <span class="uppercase font-semibold text-xs text-green-600">{{ $order->status }}</span>
                </div>
            </div>

            <div class="pt-4">
                <a href="{{ route('store.index') }}" class="btn btn-primary">
                    <span>Return to Store</span>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
