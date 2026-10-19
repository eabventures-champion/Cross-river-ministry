<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request): View
    {
        seo()
            ->title('Store — Thought-Provoking Tees, Mugs & Devotionals')
            ->description('Wear and share faith-centered apparel and gifts from Cross Rivers Ministries.');

        $query = Product::active()->with('category');

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = ProductCategory::withCount('products')->get();

        return view('pages.store.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        seo()
            ->title($product->name)
            ->description($product->excerpt)
            ->image($product->imageUrl());

        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->where('product_category_id', $product->product_category_id)
            ->take(4)
            ->get();

        return view('pages.store.show', compact('product', 'relatedProducts'));
    }

    public function cart(CartService $cart): View
    {
        seo()->title('Shopping Cart');

        $lines = $cart->lines();
        $total = $cart->total();

        return view('pages.store.cart', compact('lines', 'total'));
    }

    public function addToCart(Request $request, Product $product, CartService $cart): JsonResponse|RedirectResponse
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $cart->add($product, $qty);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'count' => $cart->count(),
                'total' => $cart->total(),
                'message' => "{$product->name} added to your cart.",
            ]);
        }

        return back()->with('success', "{$product->name} added to your cart.");
    }

    public function updateCart(Request $request, Product $product, CartService $cart): JsonResponse|RedirectResponse
    {
        $qty = max(0, (int) $request->input('quantity', 1));
        $cart->update($product, $qty);

        if ($request->expectsJson()) {
            $lineSubtotal = (float) $product->price * $qty;
            return response()->json([
                'success' => true,
                'count' => $cart->count(),
                'line_subtotal' => $lineSubtotal,
                'formatted_line_subtotal' => '$' . number_format($lineSubtotal, 2),
                'total' => $cart->total(),
                'formatted_total' => '$' . number_format($cart->total(), 2),
                'message' => 'Cart updated.',
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function removeFromCart(Request $request, Product $product, CartService $cart): JsonResponse|RedirectResponse
    {
        $cart->remove($product);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'count' => $cart->count(),
                'total' => $cart->total(),
                'formatted_total' => '$' . number_format($cart->total(), 2),
                'message' => 'Item removed from cart.',
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout(CartService $cart): View|RedirectResponse
    {
        if ($cart->count() === 0) {
            return redirect()->route('store.cart')->with('warning', 'Your cart is empty.');
        }

        seo()->title('Checkout');

        $lines = $cart->lines();
        $total = $cart->total();

        return view('pages.store.checkout', compact('lines', 'total'));
    }

    public function processCheckout(Request $request, CartService $cart): RedirectResponse
    {
        if ($cart->count() === 0) {
            return redirect()->route('store.cart')->with('warning', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = Order::create([
            'reference' => 'CRM-' . strtoupper(Str::random(8)),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'],
            'notes' => $validated['notes'] ?? null,
            'total' => $cart->total(),
            'status' => 'pending',
        ]);

        foreach ($cart->lines() as $line) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $line['product']->id,
                'product_name' => $line['product']->name,
                'price' => $line['product']->price,
                'quantity' => $line['quantity'],
            ]);
            // Decrease stock
            $line['product']->decrement('stock', $line['quantity']);
        }

        $cart->clear();

        return redirect()->route('store.order-success', $order->reference);
    }

    public function orderSuccess(string $reference): View
    {
        $order = Order::where('reference', $reference)->with('items')->firstOrFail();

        seo()->title('Order Confirmation');

        return view('pages.store.order-success', compact('order'));
    }
}
