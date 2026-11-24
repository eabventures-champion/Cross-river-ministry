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
              x-data="{
                  isNew: {{ $product->exists ? 'false' : 'true' }},
                  name: {{ Js::from(old('name', $product->name ?? '')) }},
                  sku: {{ Js::from(old('sku', $product->sku ?? '')) }},
                  customSku: {{ old('sku') || ($product->exists && $product->sku) ? 'true' : 'false' }},
                  categoryMode: '{{ old('new_category') ? 'input' : 'select' }}',
                  newCategory: '{{ old('new_category', '') }}',
                  categories: {{ Js::from($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])) }},
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
                  }
              }"
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
                        <!-- Fixed single plus icon button -->
                        <button 
                            type="button" 
                            x-show="categoryMode === 'select'"
                            @click="categoryMode = 'input'; $nextTick(() => $refs.newCatInput.focus())"
                            class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors"
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
                            class="text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors"
                        >
                            Select Existing
                        </button>
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
