<x-layouts.admin title="Store Products">
    <div class="space-y-6" x-data="{ showDeleteAllModal: false, confirmed: false, showImportModal: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-light text-ink">Store Products</h1>
                <p class="text-xs text-slate">Manage books, devotionals, study journals, and ministry merchandise.</p>
            </div>
            <div class="flex items-center gap-2.5 self-start sm:self-auto flex-wrap">
                <!-- Import Products Button -->
                <button 
                    type="button" 
                    @click="showImportModal = true"
                    class="btn btn-secondary btn-sm flex items-center gap-1.5"
                    title="Import store products from file or hostinger export"
                >
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    <span>Import Products</span>
                </button>

                @if(($totalProducts ?? $products->total()) > 0)
                    <button 
                        type="button" 
                        @click="showDeleteAllModal = true; confirmed = false"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[2px] border border-red-300 text-red-600 bg-red-50/70 hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors shadow-xs"
                        title="Delete all products in the store"
                    >
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span>Delete All Products</span>
                    </button>
                @endif

                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Product</span>
                </a>
            </div>
        </div>

        <!-- Import Products Modal -->
        <div 
            x-show="showImportModal" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @keydown.escape.window="showImportModal = false"
        >
            <div 
                x-show="showImportModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="showImportModal = false"
                class="bg-white rounded-[2px] shadow-lift max-w-lg w-full p-6 sm:p-7 border border-slate-200 space-y-5"
            >
                <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-200">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-lg font-semibold text-ink">Import Store Products</h3>
                            <p class="text-xs text-slate">Import products, categories (TEES & MUGS), prices, and images.</p>
                        </div>
                    </div>
                    <button type="button" @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- 1. Quick One-Click Import from Hostinger Export -->
                <div class="p-4 bg-brand-50/60 border border-brand-200 rounded-[2px] space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-brand-900 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="size-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Exported Hostinger Store Catalog
                        </span>
                        <span class="text-[0.6875rem] font-semibold bg-brand-200 text-brand-800 px-2 py-0.5 rounded">147 Products</span>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        Pre-exported from <code class="text-[11px] bg-white px-1 py-0.5 border border-brand-200 rounded">ghostwhite-lark-853090.hostingersite.com/store/</code> with categories <strong>TEES</strong> (108 items) and <strong>MUGS</strong> (39 items) with live product images and prices.
                    </p>
                    <form action="{{ route('admin.products.import') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full py-2.5 text-xs flex items-center justify-center gap-2">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                            <span>1-Click Import 147 Hostinger Products</span>
                        </button>
                    </form>
                </div>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-xs text-slate-400 uppercase font-semibold">Or upload custom file</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- 2. Custom File Upload (JSON or CSV) -->
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label" for="import_file">Select JSON or CSV file</label>
                        <input 
                            type="file" 
                            id="import_file" 
                            name="file" 
                            accept=".json,.csv" 
                            required
                            class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer w-full border border-slate-200 p-1"
                        >
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                            <span>Download export:</span>
                            <a href="{{ asset('exports/exported_products.json') }}" download class="text-brand-600 hover:underline font-semibold">JSON</a>
                            <span>•</span>
                            <a href="{{ asset('exports/exported_products.csv') }}" download class="text-brand-600 hover:underline font-semibold">CSV</a>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm px-4">
                            <span>Upload & Import</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Caution Modal: Delete All Products -->
        <div 
            x-show="showDeleteAllModal" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @keydown.escape.window="showDeleteAllModal = false"
        >
            <div 
                x-show="showDeleteAllModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="showDeleteAllModal = false"
                class="bg-white rounded-[2px] shadow-lift max-w-lg w-full p-6 sm:p-7 border border-slate-200 space-y-5"
            >
                <div class="flex items-start gap-4">
                    <div class="size-11 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 border border-red-200">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-semibold text-ink">Caution: Delete All Store Products?</h3>
                        <p class="text-xs text-slate mt-1">This action is permanent and will delete every store product from your inventory and catalog.</p>
                    </div>
                </div>

                <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-[2px] text-amber-900 text-xs space-y-1.5">
                    <div class="flex items-center gap-1.5 font-bold text-amber-800">
                        <svg class="size-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Irreversible Catalog Action</span>
                    </div>
                    <p class="text-amber-950/80 leading-relaxed">
                        You are preparing to delete all <span class="font-bold text-amber-950">{{ $totalProducts ?? $products->total() }}</span> product(s). Once deleted, they cannot be recovered and will be removed immediately from the public website store.
                    </p>
                </div>

                <form action="{{ route('admin.products.destroy-all') }}" method="POST" class="space-y-4 pt-1" onsubmit="return confirm('FINAL CAUTION: Are you absolutely certain you want to delete all store products? This cannot be undone.')">
                    @csrf
                    @method('DELETE')

                    <label class="flex items-start gap-2.5 p-3 rounded-[2px] bg-slate-50 border border-slate-200 text-xs text-slate-800 cursor-pointer select-none hover:bg-slate-100/70 transition-colors">
                        <input 
                            type="checkbox" 
                            x-model="confirmed" 
                            class="mt-0.5 size-4 text-red-600 rounded-[2px] border-slate-300 focus:ring-red-500 cursor-pointer"
                        >
                        <span class="font-medium">
                            I understand the consequences and confirm that I want to proceed with permanently deleting all store products.
                        </span>
                    </label>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="showDeleteAllModal = false" 
                            class="px-4 py-2 text-xs font-semibold text-slate-700 hover:text-slate-900 rounded-[2px] border border-slate-200 hover:bg-slate-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            :disabled="!confirmed" 
                            :class="!confirmed ? 'opacity-40 cursor-not-allowed bg-red-400' : 'bg-red-600 hover:bg-red-700 shadow-sm'"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white rounded-[2px] transition-colors"
                        >
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            <span>Proceed to Delete All Products</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-x-auto shadow-sm">
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
