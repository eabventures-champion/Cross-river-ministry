<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Session-backed shopping cart. Stores product_id => quantity only;
 * prices are always re-read from the database.
 */
class CartService
{
    protected const KEY = 'cart.items';

    public function raw(): array
    {
        return Session::get(self::KEY, []);
    }

    public function add(Product $product, int $qty = 1): void
    {
        $items = $this->raw();
        $current = $items[$product->id] ?? 0;
        $items[$product->id] = min($current + max(1, $qty), max(0, $product->stock));
        if ($items[$product->id] <= 0) {
            unset($items[$product->id]);
        }
        Session::put(self::KEY, $items);
    }

    public function update(Product $product, int $qty): void
    {
        $items = $this->raw();
        $qty = min(max(0, $qty), $product->stock);
        if ($qty === 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = $qty;
        }
        Session::put(self::KEY, $items);
    }

    public function remove(Product $product): void
    {
        $items = $this->raw();
        unset($items[$product->id]);
        Session::put(self::KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }

    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    /** @return Collection<int, array{product: Product, quantity: int, subtotal: float}> */
    public function lines(): Collection
    {
        $raw = $this->raw();
        if (! $raw) {
            return collect();
        }

        return Product::active()->whereIn('id', array_keys($raw))->get()
            ->map(fn (Product $p) => [
                'product' => $p,
                'quantity' => (int) $raw[$p->id],
                'subtotal' => (float) $p->price * (int) $raw[$p->id],
            ])->values();
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('subtotal');
    }
}
