<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CoachingService extends Model
{
    use HasImage, HasSlug;

    protected $fillable = ['title', 'slug', 'icon', 'tagline', 'excerpt', 'description', 'benefits', 'audience', 'image', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['benefits' => 'array', 'audience' => 'array', 'is_active' => 'boolean'];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }
}
