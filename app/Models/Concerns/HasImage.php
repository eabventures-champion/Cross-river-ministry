<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves an image attribute to a public URL. Supports:
 *  - absolute URLs (http/https)
 *  - bundled assets stored as "images/..." (served from /public)
 *  - uploaded files on the public storage disk
 */
trait HasImage
{
    public function imageUrl(?string $attribute = null): ?string
    {
        $attribute ??= $this->imageAttribute ?? 'image';
        $path = $this->{$attribute};

        return static::resolveImageUrl($path);
    }

    public static function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
