<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'orderItems']);

        // Search by name or SKU
        if ($request->filled('q')) {
            $term = trim($request->get('q'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }

        // Inventory status filter
        if ($request->get('inventory_filter') === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($request->get('inventory_filter') === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($request->get('inventory_filter') === 'in_stock') {
            $query->where('stock', '>', 10);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        // Calculate platform-wide inventory metrics
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock');
        $availableStock = (int) Product::where('is_active', true)->sum('stock');
        $totalPurchased = (int) (OrderItem::sum('quantity') ?? 0);
        $everStocked = $totalStock + $totalPurchased;
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<=', 10)->with('category')->limit(6)->get();
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('admin.products.index', compact(
            'products',
            'totalProducts',
            'totalStock',
            'availableStock',
            'totalPurchased',
            'everStocked',
            'lowStockCount',
            'outOfStockCount',
            'lowStockProducts',
            'categories'
        ));
    }

    public function create(): View
    {
        $product = new Product();
        $product->stock = 50;
        $product->initial_stock = 50;
        $product->low_stock_threshold = 10;
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'product_category_id' => ['nullable'],
            'category_id' => ['nullable'],
            'new_category' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:60', 'unique:products,sku'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'initial_stock' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'products');
        }

        // Dynamically resolve category
        if ($request->filled('new_category')) {
            $catName = trim($request->input('new_category'));
            $category = ProductCategory::firstOrCreate(
                ['name' => $catName],
                ['slug' => Str::slug($catName)]
            );
            $validated['product_category_id'] = $category->id;
        } else {
            $catId = $request->input('product_category_id', $request->input('category_id'));
            $validated['product_category_id'] = ($catId && $catId !== '__new__') ? (int) $catId : null;
        }
        unset($validated['new_category'], $validated['category_id']);

        $stock = (int) $request->input('stock', $request->input('stock_quantity', 50));
        if ($stock === 0 && ! $request->has('stock') && ! $request->has('stock_quantity')) {
            $stock = 50;
        }
        $validated['stock'] = $stock;
        $validated['initial_stock'] = (int) $request->input('initial_stock', max(50, $stock));
        $validated['low_stock_threshold'] = (int) $request->input('low_stock_threshold', 10);
        unset($validated['stock_quantity']);

        // Auto-generate SKU if not provided
        if (empty($validated['sku'])) {
            $cat = ! empty($validated['product_category_id']) ? ProductCategory::find($validated['product_category_id']) : null;
            $validated['sku'] = Product::generateSku($validated['name'], $cat?->name);
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'product_category_id' => ['nullable'],
            'category_id' => ['nullable'],
            'new_category' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'sku' => ['nullable', 'string', 'max:60', 'unique:products,sku,' . $product->id],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'initial_stock' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'products');
        }

        // Dynamically resolve category
        if ($request->filled('new_category')) {
            $catName = trim($request->input('new_category'));
            $category = ProductCategory::firstOrCreate(
                ['name' => $catName],
                ['slug' => Str::slug($catName)]
            );
            $validated['product_category_id'] = $category->id;
        } else {
            $catId = $request->input('product_category_id', $request->input('category_id'));
            $validated['product_category_id'] = ($catId && $catId !== '__new__') ? (int) $catId : null;
        }
        unset($validated['new_category'], $validated['category_id']);

        $stock = (int) $request->input('stock', $request->input('stock_quantity', $product->stock));
        $validated['stock'] = $stock;
        $validated['initial_stock'] = (int) $request->input('initial_stock', max($product->initial_stock ?: 50, $stock));
        $validated['low_stock_threshold'] = (int) $request->input('low_stock_threshold', $product->low_stock_threshold ?: 10);
        unset($validated['stock_quantity']);

        // Auto-generate SKU if still empty
        if (empty($validated['sku'])) {
            $cat = ! empty($validated['product_category_id']) ? ProductCategory::find($validated['product_category_id']) : null;
            $validated['sku'] = Product::generateSku($validated['name'], $cat?->name, $product->id);
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['is_active'] = $request->boolean('is_active', false);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function destroyAll(): RedirectResponse
    {
        $count = Product::count();
        if ($count === 0) {
            return redirect()->route('admin.products.index')->with('info', 'There are no products in the store to delete.');
        }
        Product::query()->delete();

        return redirect()->route('admin.products.index')->with('success', "Successfully deleted all {$count} store products.");
    }

    public function quickStoreCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = trim($request->input('name'));
        $category = ProductCategory::firstOrCreate(
            ['name' => $name],
            ['slug' => Str::slug($name)]
        );

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'products_count' => $category->products()->count(),
            ],
        ]);
    }

    public function categoriesList(): JsonResponse
    {
        $categories = ProductCategory::withCount('products')->orderBy('name')->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'products_count' => $cat->products_count,
            ];
        });

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    public function destroyCategory(ProductCategory $category): JsonResponse|RedirectResponse
    {
        $unlinkedCount = $category->products()->count();
        $name = $category->name;
        $category->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Category '{$name}' deleted successfully.",
                'unlinked_products' => $unlinkedCount,
            ]);
        }

        return redirect()->back()->with('success', "Category '{$name}' deleted successfully.");
    }

    public function import(Request $request): RedirectResponse
    {
        $productsData = [];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'json') {
                $content = file_get_contents($file->getRealPath());
                $productsData = json_decode($content, true) ?: [];
            } elseif ($extension === 'csv') {
                if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                    $header = fgetcsv($handle);
                    if ($header) {
                        $header = array_map(fn ($h) => trim(strtolower((string) $h)), $header);
                        while (($row = fgetcsv($handle)) !== false) {
                            if (count($row) === count($header)) {
                                $productsData[] = array_combine($header, $row);
                            }
                        }
                    }
                    fclose($handle);
                }
            } else {
                return back()->with('error', 'Please upload a valid JSON or CSV file.');
            }
        } else {
            // Default: use the preloaded exported products file
            $defaultFile = public_path('exports/exported_products.json');
            if (! file_exists($defaultFile)) {
                $defaultFile = base_path('exported_products.json');
            }

            if (file_exists($defaultFile)) {
                $content = file_get_contents($defaultFile);
                $productsData = json_decode($content, true) ?: [];
            }
        }

        if (empty($productsData)) {
            return back()->with('error', 'No valid product records found to import.');
        }

        $importedCount = 0;
        foreach ($productsData as $item) {
            if (empty($item['name'])) {
                continue;
            }

            $catName = trim($item['category'] ?? 'General');
            if (empty($catName)) {
                $catName = 'General';
            }

            $category = ProductCategory::firstOrCreate(
                ['name' => $catName],
                ['slug' => Str::slug($catName)]
            );

            $slug = ! empty($item['slug']) ? Str::slug($item['slug']) : Str::slug($item['name']);

            $price = isset($item['price']) ? (float) $item['price'] : 0.00;
            $stock = isset($item['stock']) ? (int) $item['stock'] : 50;
            if ($stock <= 0) {
                $stock = 50;
            }
            $image = $item['full_image_url'] ?? $item['image_url'] ?? $item['image'] ?? null;
            $isActive = isset($item['is_active']) ? filter_var($item['is_active'], FILTER_VALIDATE_BOOLEAN) : true;

            $sku = ! empty($item['sku']) ? trim($item['sku']) : Product::generateSku($item['name'], $category->name);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'product_category_id' => $category->id,
                    'name' => $item['name'],
                    'sku' => $sku,
                    'price' => $price,
                    'stock' => $stock,
                    'initial_stock' => $stock,
                    'low_stock_threshold' => 10,
                    'image' => $image,
                    'is_active' => $isActive,
                ]
            );
            $importedCount++;
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Successfully imported {$importedCount} products into your store catalog.");
    }
}
