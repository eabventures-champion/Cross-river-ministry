<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasImage;

    protected $fillable = ['name', 'location', 'category', 'headline', 'content', 'photo', 'is_featured', 'is_published', 'sort_order'];

    protected string $imageAttribute = 'photo';

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_published' => 'boolean'];
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)->orderBy('sort_order')->latest();
    }

    public function initials(): string
    {
        return collect(preg_split('/[\s.]+/', $this->name, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    }

    // Accessor aliases for backward compatibility
    public function getAuthorNameAttribute(): string
    {
        return $this->attributes['name'] ?? '';
    }

    public function getAuthorTitleAttribute(): ?string
    {
        return $this->attributes['location'] ?? null;
    }

    public function getQuoteAttribute(): string
    {
        return $this->attributes['content'] ?? '';
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['is_published'] ?? false);
    }
}
