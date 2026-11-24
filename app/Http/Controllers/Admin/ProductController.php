<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(15);
        $totalProducts = Product::count();

        return view('admin.products.index', compact('products', 'totalProducts'));
    }

    public function create(): View
    {
        $product = new Product();
        $categories = ProductCategory::orderBy('name')->get();

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
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
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

        $validated['stock'] = (int) $request->input('stock', $request->input('stock_quantity', 0));
        unset($validated['stock_quantity']);

        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('name')->get();

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
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
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

        $validated['stock'] = (int) $request->input('stock', $request->input('stock_quantity', $product->stock));
        unset($validated['stock_quantity']);

        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['is_active'] = $request->boolean('is_active', false);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
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

        return redirect()->route('admin.products.index')->with('success', "All {$count} store products have been permanently deleted.");
    }

    public function quickStoreCategory(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = trim($validated['name']);
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
            ],
        ]);
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
            $image = $item['full_image_url'] ?? $item['image_url'] ?? $item['image'] ?? null;
            $isActive = isset($item['is_active']) ? filter_var($item['is_active'], FILTER_VALIDATE_BOOLEAN) : true;

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'product_category_id' => $category->id,
                    'name' => $item['name'],
                    'price' => $price,
                    'stock' => $stock,
                    'image' => $image,
                    'is_active' => $isActive,
                ]
            );

            $importedCount++;
        }

        return redirect()->route('admin.products.index')->with('success', "Successfully imported {$importedCount} products into your store catalog!");
    }
}
