<?php

use App\Services\SeoService;
use App\Services\SettingsService;
use Illuminate\Support\HtmlString;

if (! function_exists('setting')) {
    /** Read an editable content value, e.g. setting('home.hero_title'). */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('setting_lines')) {
    function setting_lines(string $key): array
    {
        return app(SettingsService::class)->lines($key);
    }
}

if (! function_exists('accent')) {
    /** Render text where *word* becomes an italic brand accent. */
    function accent(?string $text, string $class = 'accent-italic'): HtmlString
    {
        return SettingsService::accentify((string) $text, $class);
    }
}

if (! function_exists('seo')) {
    function seo(): SeoService
    {
        return app(SeoService::class);
    }
}

if (! function_exists('img_url')) {
    function img_url(?string $path): ?string
    {
        return \App\Models\Media::resolveImageUrl($path);
    }
}
