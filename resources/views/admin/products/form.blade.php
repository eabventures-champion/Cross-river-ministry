<x-layouts.admin :title="$product->exists ? 'Edit Product' : 'Add Product'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Products</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h1>
            <p class="text-xs text-slate">Product pricing, description, imagery, and inventory controls.</p>
        </div>

        <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST"
              enctype="multipart/form-data"
              x-data="productForm()"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="name">Product Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required 
                        class="form-input" 
                        x-model="name"
                        @input="onNameChange()"
                        value="{{ old('name', $product->name) }}" 
                        placeholder="e.g. Crossing the River Journal"
                    >
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="form-label mb-0" for="product_category_id">Product Category</label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="showCategoryManager = true"
                                class="text-xs font-semibold text-slate-500 hover:text-red-600 flex items-center gap-1 transition-colors cursor-pointer"
                                title="Manage and remove product categories"
                            >
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span>Manage / Remove</span>
                            </button>
                            <button 
                                type="button" 
                                x-show="categoryMode === 'select'"
                                @click="categoryMode = 'input'; $nextTick(() => $refs.newCatInput.focus())"
                                class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors cursor-pointer"
                            >
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>New Category</span>
                            </button>
                            <button 
                                type="button" 
                                x-show="categoryMode === 'input'" 
                                x-cloak
                                @click="categoryMode = 'select'"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors cursor-pointer"
                            >
                                Select Existing
                            </button>
                        </div>
                    </div>

                    <!-- Existing Categories Dropdown Mode -->
                    <div x-show="categoryMode === 'select'">
                        <select 
                            id="product_category_id" 
                            name="product_category_id" 
                            class="form-input" 
                            x-model="selectedCategoryId"
                            @change="if ($event.target.value === '__new__') { categoryMode = 'input'; $nextTick(() => $refs.newCatInput.focus()); } else { onCategoryChange(); }"
                        >
                            <option value="">Select Category</option>
                            <template x-for="c in categories" :key="c.id">
                                <option :value="c.id" x-text="c.name" :selected="String(c.id) === String(selectedCategoryId)"></option>
                            </template>
                            <option value="__new__" class="font-semibold text-brand-600">+ Create New Category...</option>
                        </select>
                        <p x-show="quickAddMessage" x-cloak class="text-[0.6875rem] text-green-600 mt-1" x-text="quickAddMessage"></p>
                    </div>

                    <!-- Dynamic Category Input Mode -->
                    <div x-show="categoryMode === 'input'" x-cloak class="space-y-2">
                        <div class="flex items-center gap-2">
                            <input 
                                type="text" 
                                x-ref="newCatInput"
                                name="new_category" 
                                x-model="newCategory"
                                @input="onCategoryChange()"
                                class="form-input flex-grow" 
                                placeholder="Type new category (e.g. TEES, MUGS, HOODIES)..."
                                @keydown.enter.prevent="quickCreateCategory()"
                            >
                            <button 
                                type="button" 
                                @click="quickCreateCategory()" 
                                :disabled="savingCategory || !newCategory.trim()"
                                class="btn btn-secondary btn-sm shrink-0 px-3 py-2 text-xs"
                                :class="{'opacity-50 cursor-not-allowed': savingCategory || !newCategory.trim()}"
                            >
                                <span x-show="!savingCategory">Add</span>
                                <span x-show="savingCategory" x-cloak>Saving...</span>
                            </button>
                        </div>
                        <p class="text-[0.6875rem] text-slate-500">
                            Type a category and click <strong>Add</strong> or submit this form to auto-create and link it.
                        </p>
                    </div>

                    @error('product_category_id')<p class="form-error">{{ $message }}</p>@enderror
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                    @error('new_category')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="form-label" for="price">Price ($ USD) *</label>
                    <input type="number" step="0.01" id="price" name="price" required class="form-input" value="{{ old('price', $product->price) }}" placeholder="19.99">
                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="form-label mb-0" for="sku">SKU / Item Code</label>
                        <button 
                            type="button" 
                            @click="autoGenerateSku()"
                            class="text-[0.6875rem] font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors"
                            title="Regenerate SKU from name and category"
                        >
                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Auto Generate</span>
                        </button>
                    </div>
                    <input 
                        type="text" 
                        id="sku" 
                        name="sku" 
                        x-model="sku"
                        @input="customSku = true"
                        class="form-input uppercase font-mono tracking-wider font-semibold" 
                        value="{{ old('sku', $product->sku) }}" 
                        placeholder="e.g. CRM-TEE-YFAW"
                    >
                    <p class="text-[0.6875rem] text-slate-500 mt-1">
                        Auto-generated as you type. You can edit manually or click <strong>Auto Generate</strong> anytime.
                    </p>
                    @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="stock">Available Stock (Default 50)</label>
                    <input type="number" id="stock" name="stock" min="0" class="form-input" value="{{ old('stock', old('stock_quantity', $product->stock ?? 50)) }}">
                    <p class="text-[0.6875rem] text-slate-500 mt-1">
                        @if($product->exists)
                            Ever stocked: <strong>{{ $product->ever_stocked }}</strong> | Purchased: <strong>{{ $product->total_purchased }}</strong>
                        @else
                            Default is 50 units. Alert triggers when stock &le; 10.
                        @endif
                    </p>
                    @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="description">Product Description</label>
                <textarea id="description" name="description" rows="5" class="form-input" placeholder="Features, dimensions, spiritual value, format...">{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Product Image</label>
                <div class="flex items-center gap-4 mt-2">
                    @if($product->imageUrl())
                        <div class="relative group">
                            <button 
                                type="button" 
                                @click="showImageModal = true"
                                class="size-16 rounded-[2px] overflow-hidden shrink-0 border border-slate-200 block relative cursor-pointer hover:border-brand-500 transition-all shadow-2xs focus:outline-none focus:ring-2 focus:ring-brand-500"
                                title="Click to view full image"
                            >
                                <img src="{{ asset($product->imageUrl()) }}" alt="{{ $product->name }}" class="size-full object-cover group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white">
                                    <svg class="size-4 drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('image')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="in_stock" value="1" {{ old('in_stock', $product->exists ? $product->in_stock : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">In Stock</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->exists ? $product->is_active : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Active (Visible in Store)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $product->exists ? 'Update Product' : 'Create Product' }}</span>
                </button>
            </div>

            @if($product->imageUrl())
                <!-- Product Image Preview / Lightbox Modal -->
                <div 
                    x-show="showImageModal" 
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-sm"
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
                        class="bg-white rounded-[2px] shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200 flex flex-col"
                    >
                        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                            <div class="min-w-0 pr-3">
                                <h3 class="font-display text-base font-semibold text-ink truncate">{{ $product->name }}</h3>
                                <p class="text-[0.6875rem] font-mono text-slate-500">{{ $product->sku }}</p>
                            </div>
                            <button 
                                type="button" 
                                @click="showImageModal = false" 
                                class="size-8 rounded-[2px] text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
                            >
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="relative bg-slate-900/5 p-4 sm:p-6 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
                            <img 
                                src="{{ asset($product->imageUrl()) }}" 
                                alt="{{ $product->name }}" 
                                class="max-h-[60vh] max-w-full object-contain rounded-[2px] shadow-md select-none"
                            >
                        </div>
                        <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-white">
                            <a 
                                href="{{ asset($product->imageUrl()) }}" 
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
                                @click="showImageModal = false" 
                                class="btn btn-secondary btn-sm px-4 text-xs cursor-pointer"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Manage Product Categories Modal -->
            <div 
                x-show="showCategoryManager" 
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-sm"
                @keydown.escape.window="showCategoryManager = false"
            >
                <div 
                    x-show="showCategoryManager"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click.outside="showCategoryManager = false"
                    class="bg-white rounded-[2px] shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 flex flex-col"
                >
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/70">
                        <div class="flex items-center gap-2.5">
                            <div class="size-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-base font-semibold text-ink">Manage Categories</h3>
                                <p class="text-[0.6875rem] text-slate-500">Remove unused categories or unlink products safely.</p>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="showCategoryManager = false" 
                            class="size-8 rounded-[2px] text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center transition-colors cursor-pointer"
                        >
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Notification Banners inside modal -->
                    <div x-show="categoryDeleteMessage" x-cloak class="p-3 bg-green-50 border-b border-green-200 text-xs font-semibold text-green-800 flex items-center gap-2">
                        <svg class="size-4 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span x-text="categoryDeleteMessage"></span>
                    </div>
                    <div x-show="categoryDeleteError" x-cloak class="p-3 bg-red-50 border-b border-red-200 text-xs font-semibold text-red-800 flex items-center gap-2">
                        <svg class="size-4 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <span x-text="categoryDeleteError"></span>
                    </div>

                    <!-- Category Items List -->
                    <div class="p-4 sm:p-5 max-h-[60vh] overflow-y-auto divide-y divide-slate-100">
                        <template x-for="cat in categories" :key="cat.id">
                            <div class="py-3 flex items-center justify-between gap-3 group">
                                <div class="min-w-0">
                                    <span class="text-xs font-semibold text-ink block truncate" x-text="cat.name"></span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span 
                                            class="text-[0.6875rem] font-medium px-2 py-0.5 rounded-[2px]"
                                            :class="cat.products_count > 0 ? 'bg-brand-50 text-brand-700 border border-brand-200/80 font-bold' : 'bg-slate-100 text-slate-500'"
                                            x-text="(cat.products_count ?? 0) + ' product' + ((cat.products_count ?? 0) === 1 ? '' : 's')"
                                        ></span>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    @click="deleteCategory(cat.id, cat.name, cat.products_count ?? 0)"
                                    :disabled="deletingCategoryId === cat.id"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-red-600 hover:text-white hover:bg-red-600 border border-red-200 hover:border-red-600 rounded-[2px] transition-colors cursor-pointer shrink-0 disabled:opacity-50"
                                    title="Remove this category"
                                >
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    <span>Remove</span>
                                </button>
                            </div>
                        </template>
                        <div x-show="categories.length === 0" class="py-8 text-center text-xs text-slate-400">
                            No categories found.
                        </div>
                    </div>

                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                        <p class="text-[0.6875rem] text-slate-500">
                            Products in removed categories are safely retained as uncategorized.
                        </p>
                        <button 
                            type="button" 
                            @click="showCategoryManager = false" 
                            class="btn btn-secondary btn-sm px-4 text-xs cursor-pointer"
                        >
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function productForm() {
            return {
                showImageModal: false,
                showCategoryManager: false,
                categoryDeleteMessage: '',
                categoryDeleteError: '',
                deletingCategoryId: null,
                isNew: {{ $product->exists ? 'false' : 'true' }},
                name: {{ Js::from(old('name', $product->name ?? '')) }},
                sku: {{ Js::from(old('sku', $product->sku ?? '')) }},
                customSku: {{ old('sku') || ($product->exists && $product->sku) ? 'true' : 'false' }},
                categoryMode: '{{ old('new_category') ? 'input' : 'select' }}',
                newCategory: '{{ old('new_category', '') }}',
                categories: {{ Js::from($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'products_count' => $c->products_count ?? 0])) }},
                selectedCategoryId: '{{ old('product_category_id', old('category_id', $product->product_category_id)) }}',
                savingCategory: false,
                quickAddMessage: '',

                getCategoryName() {
                    if (this.categoryMode === 'input') {
                        return this.newCategory.trim();
                    }
                    const found = this.categories.find(c => String(c.id) === String(this.selectedCategoryId));
                    return found ? found.name : '';
                },

                generateSku(name, categoryName) {
                    if (!name || !name.trim()) return '';
                    const clean = name.replace(/[^a-zA-Z0-9\s]/g, '').trim();
                    const words = clean.split(/\s+/).filter(Boolean);
                    if (words.length === 0) return 'ITEM';

                    let code = 'ITEM';
                    if (words.length === 1) {
                        code = words[0].substring(0, 4).toUpperCase();
                    } else if (words.length === 2) {
                        code = (words[0].substring(0, 3) + words[1].substring(0, 2)).toUpperCase();
                    } else {
                        let acronym = '';
                        for (let i = 0; i < words.length; i++) {
                            acronym += words[i][0];
                        }
                        code = acronym.substring(0, 5).toUpperCase();
                    }

                    code = code.replace(/-+$/, '');
                    if (code.length < 3) {
                        code = code.padEnd(3, 'X');
                    }

                    let catCode = '';
                    const catUpper = (categoryName || '').toUpperCase();
                    if (catUpper.includes('TEE')) catCode = 'TEE';
                    else if (catUpper.includes('MUG')) catCode = 'MUG';
                    else if (catUpper.includes('BOOK')) catCode = 'BOK';

                    return catCode ? `CRM-${catCode}-${code}` : `CRM-${code}`;
                },

                onNameChange() {
                    if (this.isNew && !this.customSku) {
                        this.sku = this.generateSku(this.name, this.getCategoryName());
                    }
                },

                onCategoryChange() {
                    if (this.isNew && !this.customSku) {
                        this.sku = this.generateSku(this.name, this.getCategoryName());
                    }
                },

                autoGenerateSku() {
                    this.customSku = false;
                    this.sku = this.generateSku(this.name, this.getCategoryName());
                },

                async quickCreateCategory() {
                    const catName = this.newCategory.trim();
                    if (!catName) return;
                    this.savingCategory = true;
                    this.quickAddMessage = '';
                    try {
                        const res = await fetch('{{ route('admin.products.categories.quick-store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ name: catName })
                        });
                        const data = await res.json();
                        if (data.success && data.category) {
                            if (!this.categories.some(c => c.id === data.category.id)) {
                                this.categories.push(data.category);
                            }
                            this.selectedCategoryId = data.category.id;
                            this.newCategory = '';
                            this.categoryMode = 'select';
                            this.quickAddMessage = 'Category created and selected!';
                            this.onCategoryChange();
                            setTimeout(() => this.quickAddMessage = '', 3000);
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.savingCategory = false;
                    }
                },

                async deleteCategory(id, name, count) {
                    const confirmMsg = count > 0 
                        ? `Category "${name}" is assigned to ${count} product(s).\n\nDeleting it will unlink these products (they will become uncategorized), but will NOT delete the products.\n\nAre you sure you want to remove this category?`
                        : `Are you sure you want to remove category "${name}"?`;
                    if (!confirm(confirmMsg)) return;

                    this.deletingCategoryId = id;
                    this.categoryDeleteMessage = '';
                    this.categoryDeleteError = '';

                    try {
                        const res = await fetch(`{{ url('admin/products/categories') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.categories = this.categories.filter(c => c.id !== id);
                            if (String(this.selectedCategoryId) === String(id)) {
                                this.selectedCategoryId = '';
                                this.onCategoryChange();
                            }
                            this.categoryDeleteMessage = `Category "${name}" removed successfully.`;
                            setTimeout(() => this.categoryDeleteMessage = '', 3500);
                        } else {
                            this.categoryDeleteError = data.message || 'Failed to remove category.';
                        }
                    } catch (err) {
                        this.categoryDeleteError = 'An error occurred while deleting category.';
                    } finally {
                        this.deletingCategoryId = null;
                    }
                }
            };
        }
    </script>
</x-layouts.admin>
