<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasImage, HasSlug, Publishable;

    protected $fillable = ['video_category_id', 'title', 'slug', 'excerpt', 'description', 'video_url', 'thumbnail', 'duration', 'is_featured', 'published_at'];

    protected string $imageAttribute = 'thumbnail';

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_featured' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VideoCategory::class, 'video_category_id');
    }

    /** Converts YouTube / Vimeo share URLs into privacy-friendly embed URLs. */
    public function embedUrl(): ?string
    {
        $url = $this->video_url;

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/{$m[1]}?rel=0&modestbranding=1";
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}?dnt=1";
        }

        return $url;
    }

    public function isFile(): bool
    {
        return (bool) preg_match('~\.(mp4|webm|ogg)(\?.*)?$~i', $this->video_url);
    }

    public function thumbnailUrl(): ?string
    {
        if ($url = $this->imageUrl()) {
            return $url;
        }
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $this->video_url, $m)) {
            return "https://i.ytimg.com/vi/{$m[1]}/hqdefault.jpg";
        }

        return null;
    }

    public function summary(int $limit = 140): string
    {
        return $this->excerpt ?: Str::limit(strip_tags((string) $this->description), $limit);
    }
}
