<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Podcast extends Model
{
    use HasImage, HasSlug;

    protected $fillable = ['title', 'slug', 'description', 'cover', 'sort_order'];

    protected string $imageAttribute = 'cover';

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }
}
