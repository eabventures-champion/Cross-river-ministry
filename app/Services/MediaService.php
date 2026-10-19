<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores uploads on the public disk and registers them in the media library.
 * Large raster images are down-scaled to keep pages fast.
 */
class MediaService
{
    public const MAX_WIDTH = 2000;

    public function store(UploadedFile $file, string $folder = 'uploads', ?string $alt = null): Media
    {
        $folder = trim($folder, '/').'/'.now()->format('Y/m');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $filename = $name.'-'.Str::lower(Str::random(6)).'.'.strtolower($file->getClientOriginalExtension());

        $path = $file->storeAs($folder, $filename, 'public');
        [$width, $height] = $this->optimise($path, (string) $file->getMimeType());

        return Media::create([
            'path' => $path,
            'disk' => 'public',
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => Storage::disk('public')->size($path),
            'alt' => $alt,
            'width' => $width,
            'height' => $height,
        ]);
    }

    /** Store and return just the relative path (for model image columns). */
    public function storePath(UploadedFile $file, string $folder = 'uploads'): string
    {
        return $this->store($file, $folder)->path;
    }

    public function delete(Media $media): void
    {
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();
    }

    /** Down-scale oversized JPEG/PNG/WebP files with GD (when available). */
    protected function optimise(string $path, string $mime): array
    {
        $full = Storage::disk('public')->path($path);
        $info = @getimagesize($full);
        if (! $info) {
            return [null, null];
        }
        [$w, $h] = $info;

        if (! extension_loaded('gd') || $w <= self::MAX_WIDTH) {
            return [$w, $h];
        }

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($full),
            'image/png' => @imagecreatefrompng($full),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($full) : false,
            default => false,
        };
        if (! $src) {
            return [$w, $h];
        }

        $nw = self::MAX_WIDTH;
        $nh = (int) round($h * ($nw / $w));
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        match ($mime) {
            'image/jpeg' => imagejpeg($dst, $full, 82),
            'image/png' => imagepng($dst, $full, 7),
            'image/webp' => imagewebp($dst, $full, 82),
        };
        imagedestroy($src);
        imagedestroy($dst);

        return [$nw, $nh];
    }
}
