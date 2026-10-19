<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasImage;

    protected $fillable = ['path', 'disk', 'original_name', 'mime_type', 'size', 'alt', 'width', 'height'];

    protected string $imageAttribute = 'path';

    public function url(): ?string
    {
        return $this->imageUrl('path');
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function humanSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1).' '.$units[$i];
    }
}
