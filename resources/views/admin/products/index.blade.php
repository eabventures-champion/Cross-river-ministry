<x-layouts.admin title="Store Products">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Store Products</h1>
                <p class="text-xs text-slate">Manage books, devotionals, study journals, and ministry merchandise.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Product</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Product</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Inventory</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-[2px] bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($p->imageUrl())
                                            <img src="{{ $p->imageUrl() }}" alt="{{ $p->name }}" class="size-full object-cover">
                                        @else
                                            <span class="text-xs font-bold text-slate-400">ITEM</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $p->name }}</span>
                                        <span class="text-xs text-muted">{{ $p->sku ?: 'No SKU' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700">
                                {{ $p->category->name ?? 'General' }}
                            </td>
                            <td class="p-4 text-xs font-semibold text-ink">
                                ${{ number_format($p->price, 2) }}
                            </td>
                            <td class="p-4 text-xs">
                                <span class="{{ $p->in_stock ? 'text-green-700 font-semibold' : 'text-red-600 font-semibold' }}">
                                    {{ $p->in_stock ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $p->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $p) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No products created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $products->links() }}
    </div>
</x-layouts.admin>
