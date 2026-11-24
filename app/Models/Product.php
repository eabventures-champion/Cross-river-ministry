<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasImage, HasSlug;

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'sku',
        'excerpt',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'initial_stock',
        'low_stock_threshold',
        'image',
        'is_featured',
        'is_active',
    ];

    protected string $slugSource = 'name';

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if ($product->stock === null) {
                $product->stock = 50;
            }
            if ($product->initial_stock === null || $product->initial_stock === 0) {
                $product->initial_stock = max(50, (int) $product->stock);
            }
            if (! $product->low_stock_threshold) {
                $product->low_stock_threshold = 10;
            }
            if (empty($product->sku)) {
                $catName = $product->category?->name;
                $product->sku = static::generateSku($product->name, $catName);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'stock' => 'integer',
            'initial_stock' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeLowStock(Builder $q): Builder
    {
        return $q->where('stock', '>', 0)->where('stock', '<=', 10);
    }

    public function scopeOutOfStock(Builder $q): Builder
    {
        return $q->where('stock', '<=', 0);
    }

    public function inStock(): bool
    {
        return (int) $this->stock > 0;
    }

    public function getInStockAttribute(): bool
    {
        return (int) $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        $threshold = $this->low_stock_threshold ?: 10;
        return (int) $this->stock > 0 && (int) $this->stock <= $threshold;
    }

    public function isOutOfStock(): bool
    {
        return (int) $this->stock <= 0;
    }

    public function getTotalPurchasedAttribute(): int
    {
        return (int) ($this->orderItems()->sum('quantity') ?? 0);
    }

    public function getEverStockedAttribute(): int
    {
        $initial = (int) ($this->initial_stock ?: 50);
        $currentAndSold = (int) $this->stock + $this->total_purchased;
        return max($initial, $currentAndSold);
    }

    public function stockLabel(): string
    {
        return match (true) {
            $this->stock <= 0 => 'Out of stock',
            $this->stock <= ($this->low_stock_threshold ?: 10) => 'Only ' . $this->stock . ' left',
            default => 'In stock (' . $this->stock . ')',
        };
    }

    public function formattedPrice(): string
    {
        return '$' . number_format((float) $this->price, 2);
    }

    public static function generateSku(string $name, ?string $categoryName = null, ?int $productId = null): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        $words = array_values(array_filter(explode(' ', trim((string) $clean))));

        if (empty($words)) {
            $codeName = 'ITEM';
        } elseif (count($words) === 1) {
            $codeName = strtoupper(substr($words[0], 0, 4));
        } elseif (count($words) === 2) {
            $codeName = strtoupper(substr($words[0], 0, 3) . substr($words[1], 0, 2));
        } else {
            $acronym = '';
            foreach ($words as $w) {
                $acronym .= substr($w, 0, 1);
            }
            $codeName = strtoupper(substr($acronym, 0, 5));
        }

        $codeName = rtrim($codeName, '-');
        if (strlen($codeName) < 3) {
            $codeName = str_pad($codeName, 3, 'X');
        }

        $catCode = match (strtoupper(trim((string) $categoryName))) {
            'TEES', 'TEE' => 'TEE',
            'MUGS', 'MUG' => 'MUG',
            'BOOKS & GUIDES', 'BOOKS', 'BOOK' => 'BOK',
            default => '',
        };

        $base = $catCode ? "CRM-{$catCode}-{$codeName}" : "CRM-{$codeName}";

        $sku = $base;
        $seq = 1;
        $query = static::where('sku', $sku);
        if ($productId) {
            $query->where('id', '!=', $productId);
        }
        while ($query->exists()) {
            $padded = str_pad((string) $seq, 2, '0', STR_PAD_LEFT);
            $sku = "{$base}-{$padded}";
            $seq++;
            $query = static::where('sku', $sku);
            if ($productId) {
                $query->where('id', '!=', $productId);
            }
        }

        return $sku;
    }
}
