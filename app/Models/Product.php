<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasImage, HasSlug;

    protected $fillable = ['product_category_id', 'name', 'slug', 'excerpt', 'description', 'price', 'compare_at_price', 'stock', 'image', 'is_featured', 'is_active'];

    protected string $slugSource = 'name';

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'compare_at_price' => 'decimal:2', 'is_featured' => 'boolean', 'is_active' => 'boolean', 'stock' => 'integer'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public function stockLabel(): string
    {
        return match (true) {
            $this->stock <= 0 => 'Out of stock',
            $this->stock <= 5 => 'Only '.$this->stock.' left',
            default => 'In stock',
        };
    }

    public function formattedPrice(): string
    {
        return '$'.number_format((float) $this->price, 2);
    }
}
