<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConsultingService extends Model
{
    use HasImage, HasSlug;

    public const TYPES = [
        'consulting' => 'Consulting Service',
        'speaking' => 'Speaking Engagement',
        'workshop' => 'Workshop',
    ];

    protected $fillable = ['type', 'title', 'slug', 'icon', 'excerpt', 'description', 'points', 'image', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['points' => 'array', 'is_active' => 'boolean'];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }
}
