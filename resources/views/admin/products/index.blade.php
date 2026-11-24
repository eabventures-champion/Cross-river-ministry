<x-layouts.admin title="Store Products">
    <div class="space-y-6" x-data="{ 
        showDeleteAllModal: false, 
        confirmed: false, 
        showImportModal: false,
        viewImageModal: false,
        previewImage: '',
        previewTitle: '',
        previewSku: '',
        previewCategory: '',
        previewPrice: '',
        openImage(img, title, sku, cat, price) {
            if (!img) return;
            this.previewImage = img;
            this.previewTitle = title;
            this.previewSku = sku;
            this.previewCategory = cat;
            this.previewPrice = price;
            this.viewImageModal = true;
        }
    }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-light text-ink">Store Products</h1>
                <p class="text-xs text-slate">Manage books, devotionals, study journals, and ministry merchandise inventory.</p>
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

        <!-- Low Stock Alert Notification Banner -->
        @if(($lowStockCount ?? 0) > 0 || ($outOfStockCount ?? 0) > 0)
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-[2px] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="size-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wide">
                            Low Stock Inventory Notification
                        </h4>
                        <p class="text-xs text-amber-800 mt-0.5">
                            @if(($outOfStockCount ?? 0) > 0)
                                <span class="font-bold text-red-700">{{ $outOfStockCount }} product(s) are completely Out of Stock!</span>
                            @endif
                            @if(($lowStockCount ?? 0) > 0)
                                <span class="font-medium">{{ $lowStockCount }} product(s) have low inventory (&le; 10 units remaining). Restock soon to prevent order interruptions.</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.products.index', ['inventory_filter' => 'low_stock']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-[2px] bg-amber-600 hover:bg-amber-700 text-white transition-colors">
                        View Low Stock ({{ $lowStockCount ?? 0 }})
                    </a>
                    @if(request('inventory_filter'))
                        <a href="{{ route('admin.products.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-[2px] bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors">
                            Show All
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- 4 Platform Inventory Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- 1. Available Stock -->
            <div class="card p-4 sm:p-5 bg-white border border-slate-200 rounded-[2px] shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[0.6875rem] font-bold uppercase tracking-wider">Available Stock</span>
                    <span class="size-7 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-ink">
                    {{ number_format($totalStock ?? 0) }}
                </div>
                <p class="text-[0.6875rem] text-slate-400 mt-1">Units ready for purchase</p>
            </div>

            <!-- 2. Ever Stocked -->
            <div class="card p-4 sm:p-5 bg-white border border-slate-200 rounded-[2px] shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[0.6875rem] font-bold uppercase tracking-wider">Ever Stocked</span>
                    <span class="size-7 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-brand-600">
                    {{ number_format($everStocked ?? 0) }}
                </div>
                <p class="text-[0.6875rem] text-slate-400 mt-1">Total inventory supplied</p>
            </div>

            <!-- 3. Total Purchased -->
            <div class="card p-4 sm:p-5 bg-white border border-slate-200 rounded-[2px] shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[0.6875rem] font-bold uppercase tracking-wider">Total Purchased</span>
                    <span class="size-7 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-purple-600">
                    {{ number_format($totalPurchased ?? 0) }}
                </div>
                <p class="text-[0.6875rem] text-slate-400 mt-1">Units bought by customers</p>
            </div>

            <!-- 4. Low Stock Alerts -->
            <div class="card p-4 sm:p-5 bg-white border border-slate-200 rounded-[2px] shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-1">
                    <span class="text-[0.6875rem] font-bold uppercase tracking-wider">Low Stock Alerts</span>
                    <span class="size-7 rounded-full {{ ($lowStockCount ?? 0) > 0 ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </span>
                </div>
                <div class="text-xl sm:text-2xl font-bold {{ ($lowStockCount ?? 0) > 0 ? 'text-amber-600' : 'text-slate-700' }}">
                    {{ $lowStockCount ?? 0 }}
                </div>
                <p class="text-[0.6875rem] text-slate-400 mt-1">Products &le; 10 units</p>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-4 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 shadow-xs">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none py-0.5">
                <a href="{{ route('admin.products.index', request()->only('q', 'category')) }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-[2px] whitespace-nowrap transition-colors {{ !request('inventory_filter') ? 'bg-brand-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    All ({{ $totalProducts ?? $products->total() }})
                </a>
                <a href="{{ route('admin.products.index', array_merge(request()->only('q', 'category'), ['inventory_filter' => 'in_stock'])) }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-[2px] whitespace-nowrap transition-colors {{ request('inventory_filter') === 'in_stock' ? 'bg-brand-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    In Stock ({{ max(0, ($totalProducts ?? 0) - ($lowStockCount ?? 0) - ($outOfStockCount ?? 0)) }})
                </a>
                <a href="{{ route('admin.products.index', array_merge(request()->only('q', 'category'), ['inventory_filter' => 'low_stock'])) }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-[2px] whitespace-nowrap transition-colors {{ request('inventory_filter') === 'low_stock' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                    ⚠️ Low Stock ({{ $lowStockCount ?? 0 }})
                </a>
                <a href="{{ route('admin.products.index', array_merge(request()->only('q', 'category'), ['inventory_filter' => 'out_of_stock'])) }}" 
                   class="px-3 py-1.5 text-xs font-semibold rounded-[2px] whitespace-nowrap transition-colors {{ request('inventory_filter') === 'out_of_stock' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' }}">
                    Out of Stock ({{ $outOfStockCount ?? 0 }})
                </a>
            </div>

            <!-- Search by Name or SKU -->
            <form action="{{ route('admin.products.index') }}" method="GET" class="relative w-full md:w-72 shrink-0">
                @if(request('inventory_filter'))
                    <input type="hidden" name="inventory_filter" value="{{ request('inventory_filter') }}">
                @endif
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}" 
                        placeholder="Search product name or SKU..." 
                        class="form-input pl-9 pr-8 py-1.5 text-xs rounded-[2px] border border-slate-300 w-full"
                    >
                    @if(request('q'))
                        <a href="{{ route('admin.products.index', request()->only('inventory_filter')) }}" class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs">×</a>
                    @endif
                </div>
            </form>
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
                            <p class="text-xs text-slate">Import products, categories, unique SKUs, prices, and default stock (50).</p>
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
                        Pre-exported catalog with categories <strong>TEES</strong> ($24.99) and <strong>MUGS</strong> ($9.99), auto-generated SKUs, and default stock of 50.
                    </p>
                    <form action="{{ route('admin.products.import') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full py-2.5 text-xs flex items-center justify-center gap-2">
                            <span>Import All 147 Hostinger Products</span>
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </form>
                </div>

                <!-- 2. Custom File Upload (JSON or CSV) -->
                <div class="pt-2 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Or Upload Custom JSON / CSV</span>
                        <div class="h-px bg-slate-200 flex-1"></div>
                    </div>
                    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <input 
                                type="file" 
                                name="file" 
                                accept=".json,.csv"
                                required 
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-[2px]"
                            >
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-1">
                            <button type="button" @click="showImportModal = false" class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-800">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-secondary btn-sm py-1.5 text-xs">
                                Upload & Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete All Confirmation Modal -->
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

        <!-- Products Table -->
        <div class="bg-white border border-slate-200 rounded-[2px] overflow-x-auto shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Product & SKU</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Inventory & Tracking</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Product Name & Generated Unique SKU -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($p->imageUrl())
                                        <button 
                                            type="button"
                                            @click="openImage('{{ asset($p->imageUrl()) }}', '{{ addslashes($p->name) }}', '{{ $p->sku ?: Product::generateSku($p->name, $p->category?->name, $p->id) }}', '{{ addslashes($p->category->name ?? 'General') }}', '{{ number_format($p->price, 2) }}')"
                                            class="size-11 rounded-[2px] bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center relative group cursor-pointer hover:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all shadow-2xs"
                                            title="Click to view image"
                                        >
                                            <img src="{{ asset($p->imageUrl()) }}" alt="{{ $p->name }}" class="size-full object-cover group-hover:scale-110 transition-transform duration-200">
                                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white">
                                                <svg class="size-4 drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                                </svg>
                                            </div>
                                        </button>
                                    @else
                                        <div class="size-11 rounded-[2px] bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                            <span class="text-xs font-bold text-slate-400">ITEM</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <span class="font-semibold text-ink block truncate max-w-xs sm:max-w-md">{{ $p->name }}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="inline-flex items-center font-mono text-[0.6875rem] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-[2px] border border-brand-200/80">
                                                {{ $p->sku ?: Product::generateSku($p->name, $p->category?->name, $p->id) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="p-4 text-xs font-medium text-slate-700">
                                <span class="inline-block px-2 py-0.5 rounded-[2px] bg-slate-100 text-slate-800 font-semibold text-[0.6875rem] uppercase tracking-wider">
                                    {{ $p->category->name ?? 'General' }}
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="p-4 text-xs font-bold text-ink">
                                ${{ number_format($p->price, 2) }}
                            </td>

                            <!-- Inventory: Available Stock, Purchased, Ever Stocked & Notifications -->
                            <td class="p-4 text-xs space-y-1">
                                <div>
                                    @if($p->stock <= 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[2px] text-[0.6875rem] font-bold bg-red-100 text-red-800">
                                            <span class="size-1.5 rounded-full bg-red-600"></span>
                                            <span>Out of Stock (0)</span>
                                        </span>
                                    @elseif($p->isLowStock())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[2px] text-[0.6875rem] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                            <span class="size-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            <span>Low Stock: {{ $p->stock }} left</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[2px] text-[0.6875rem] font-bold bg-green-100 text-green-800">
                                            <span class="size-1.5 rounded-full bg-green-600"></span>
                                            <span>In Stock ({{ $p->stock }})</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[0.6875rem] text-slate-500 font-medium">
                                    <span>Ever Stocked: <strong class="text-slate-700">{{ $p->ever_stocked }}</strong></span>
                                    <span class="text-slate-300 mx-1">|</span>
                                    <span>Purchased: <strong class="text-purple-700">{{ $p->total_purchased }}</strong></span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $p->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>

                            <!-- Actions -->
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
                                No products found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $products->links() }}

        <!-- Product Image Preview / Lightbox Modal -->
        <div 
            x-show="viewImageModal" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-sm"
            @keydown.escape.window="viewImageModal = false"
        >
            <div 
                x-show="viewImageModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.outside="viewImageModal = false"
                class="bg-white rounded-[2px] shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200 flex flex-col"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <div class="min-w-0 pr-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[0.6875rem] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-[2px] border border-brand-200" x-text="previewSku"></span>
                            <span class="text-[0.6875rem] font-bold uppercase tracking-wider text-slate-500" x-text="previewCategory"></span>
                        </div>
                        <h3 class="font-display text-base font-semibold text-ink truncate mt-1" x-text="previewTitle"></h3>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="font-bold text-sm text-brand-600" x-show="previewPrice" x-text="'$' + previewPrice"></span>
                        <button 
                            type="button" 
                            @click="viewImageModal = false" 
                            class="size-8 rounded-[2px] text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
                            title="Close viewer (Esc)"
                        >
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body: Image Display -->
                <div class="relative bg-slate-900/5 p-4 sm:p-6 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
                    <img 
                        :src="previewImage" 
                        :alt="previewTitle" 
                        class="max-h-[60vh] max-w-full object-contain rounded-[2px] shadow-md select-none"
                    >
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-white">
                    <a 
                        :href="previewImage" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-brand-800 transition-colors"
                    >
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        <span>Open Full Resolution</span>
                    </a>
                    <button 
                        type="button" 
                        @click="viewImageModal = false" 
                        class="btn btn-secondary btn-sm px-4 text-xs cursor-pointer"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
