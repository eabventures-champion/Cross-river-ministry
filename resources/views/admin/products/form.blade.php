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
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" id="name" name="name" required class="form-input" value="{{ old('name', $product->name) }}" placeholder="e.g. Crossing the River Journal">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="category_id">Product Category</label>
                    <select id="category_id" name="category_id" class="form-input">
                        <option value="">Select Category</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="form-label" for="price">Price ($ USD) *</label>
                    <input type="number" step="0.01" id="price" name="price" required class="form-input" value="{{ old('price', $product->price) }}" placeholder="19.99">
                    @error('price')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="sku">SKU / Item Code</label>
                    <input type="text" id="sku" name="sku" class="form-input" value="{{ old('sku', $product->sku) }}" placeholder="CRM-BOK-01">
                    @error('sku')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="stock_quantity">Stock Quantity</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-input" value="{{ old('stock_quantity', $product->stock_quantity ?? 50) }}">
                    @error('stock_quantity')<p class="form-error">{{ $message }}</p>@enderror
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
                        <div class="size-16 rounded-[2px] overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="size-full object-cover">
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
        </form>
    </div>
</x-layouts.admin>
