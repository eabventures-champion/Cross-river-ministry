<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PodcastEpisode extends Model
{
    use HasImage, HasSlug, Publishable;

    protected $fillable = ['podcast_id', 'title', 'slug', 'episode_number', 'excerpt', 'description', 'audio_url', 'audio_file', 'image', 'duration', 'is_featured', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_featured' => 'boolean'];
    }

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    public function audioSource(): ?string
    {
        if ($this->audio_file) {
            return Storage::disk('public')->url($this->audio_file);
        }

        return $this->audio_url;
    }

    /** Episode artwork, falling back to the show cover. */
    public function artworkUrl(): ?string
    {
        return $this->imageUrl() ?? $this->podcast?->imageUrl();
    }

    public function summary(int $limit = 150): string
    {
        return $this->excerpt ?: Str::limit(strip_tags((string) $this->description), $limit);
    }
}
