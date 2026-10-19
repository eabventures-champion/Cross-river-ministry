<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug'];

    protected string $slugSource = 'name';

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
