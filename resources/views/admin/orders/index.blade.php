<x-layouts.admin title="Customer Orders">
    <div class="space-y-6">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">Store Orders</h1>
            <p class="text-xs text-slate">Manage merchandise orders, shipping addresses, and status updates.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Reference</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $o)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-mono font-bold text-ink">{{ $o->reference }}</td>
                            <td class="p-4">
                                <span class="font-medium text-ink block">{{ $o->name }}</span>
                                <span class="text-xs text-muted">{{ $o->email }}</span>
                            </td>
                            <td class="p-4 font-bold text-brand-500">${{ number_format($o->total, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-xs uppercase font-bold rounded-[2px] bg-slate-100 text-slate-700">
                                    {{ $o->status }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-mono">{{ $o->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.orders.show', $o) }}" class="text-brand-500 hover:underline text-xs">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-slate-400">No orders placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</x-layouts.admin>
