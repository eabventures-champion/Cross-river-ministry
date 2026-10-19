<x-layouts.admin title="Order {{ $order->reference }}">
    <div class="space-y-6 max-w-4xl">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-slate hover:underline mb-1 block">← Back to Orders</a>
                <h1 class="font-display text-3xl font-light text-ink">Order {{ $order->reference }}</h1>
            </div>
            <span class="px-3 py-1 text-xs uppercase font-bold rounded-[2px] bg-slate-200 text-slate-800">
                Status: {{ $order->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Line items -->
                <div class="bg-white p-6 border border-slate-200 rounded-[2px] shadow-sm space-y-4">
                    <h3 class="font-display text-lg font-semibold text-ink border-b border-slate-100 pb-2">Order Line Items</h3>
                    <div class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <div class="py-3 flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-medium text-ink block">{{ $item->product_name }}</span>
                                    <span class="text-xs text-muted">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</span>
                                </div>
                                <span class="font-bold text-slate-800">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="font-semibold text-ink">Total Order Amount:</span>
                        <span class="font-display text-2xl font-bold text-brand-500">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <!-- Update Status Form -->
                <div class="bg-white p-6 border border-slate-200 rounded-[2px] shadow-sm space-y-4">
                    <h3 class="font-display text-lg font-semibold text-ink border-b border-slate-100 pb-2">Update Order Status</h3>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <select name="status" class="form-input text-sm w-48">
                            @foreach(\App\Models\Order::STATUSES as $st)
                                <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Customer Details Column -->
            <div class="space-y-6">
                <div class="bg-white p-6 border border-slate-200 rounded-[2px] shadow-sm space-y-3 text-sm">
                    <h3 class="font-display text-lg font-semibold text-ink border-b border-slate-100 pb-2">Customer & Shipping</h3>
                    <div>
                        <span class="text-xs text-muted block">Name:</span>
                        <span class="font-medium text-ink">{{ $order->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-muted block">Email:</span>
                        <a href="mailto:{{ $order->email }}" class="text-brand-500">{{ $order->email }}</a>
                    </div>
                    @if($order->phone)
                        <div>
                            <span class="text-xs text-muted block">Phone:</span>
                            <span class="text-slate-800">{{ $order->phone }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-xs text-muted block">Delivery Address:</span>
                        <p class="text-xs text-slate-800 mt-1 whitespace-pre-line">{{ $order->address }}</p>
                    </div>
                    @if($order->notes)
                        <div>
                            <span class="text-xs text-muted block">Customer Notes:</span>
                            <p class="text-xs text-slate-800 mt-1 italic">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
