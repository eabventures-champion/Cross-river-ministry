<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Str;

/**
 * Collects per-page SEO metadata (title, description, OG, canonical, JSON-LD).
 * Controllers call seo()->title(...)->description(...); the layout renders it.
 */
class SeoService
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $image = null;

    protected ?string $canonical = null;

    protected string $type = 'website';

    protected bool $noindex = false;

    protected array $schemas = [];

    public function __construct(protected SettingsService $settings) {}

    public function title(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function description(?string $description): static
    {
        $this->description = $description ? Str::limit(strip_tags($description), 158) : null;

        return $this;
    }

    public function image(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function canonical(?string $url): static
    {
        $this->canonical = $url;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function noindex(bool $value = true): static
    {
        $this->noindex = $value;

        return $this;
    }

    public function schema(array $schema): static
    {
        $this->schemas[] = $schema;

        return $this;
    }

    public function siteName(): string
    {
        return (string) $this->settings->get('general.site_name', config('app.name'));
    }

    public function fullTitle(): string
    {
        $site = $this->siteName();

        return $this->title ? "{$this->title} — {$site}" : "{$site} — ".$this->settings->get('general.site_tagline');
    }

    public function metaDescription(): string
    {
        return $this->description ?? (string) $this->settings->get('general.site_description');
    }

    public function imageUrl(): ?string
    {
        $image = $this->image ?? $this->settings->get('general.og_image');

        return Media::resolveImageUrl($image);
    }

    public function canonicalUrl(): string
    {
        return $this->canonical ?? url()->current();
    }

    public function ogType(): string
    {
        return $this->type;
    }

    public function isNoindex(): bool
    {
        return $this->noindex;
    }

    /** JSON-LD graph: Organization + WebSite on every page, plus any page schemas. */
    public function jsonLd(): string
    {
        $s = $this->settings;
        $sameAs = array_values(array_filter([
            $s->get('general.facebook_url'), $s->get('general.instagram_url'),
            $s->get('general.twitter_url'), $s->get('general.youtube_url'),
        ]));

        $graph = array_merge([
            [
                '@type' => 'Organization',
                '@id' => url('/').'#organization',
                'name' => $this->siteName(),
                'url' => url('/'),
                'logo' => asset('images/site/crm-logo.png'),
                'email' => $s->get('general.contact_email'),
                'description' => $s->get('general.site_description'),
                'sameAs' => $sameAs,
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'url' => url('/'),
                'name' => $this->siteName(),
                'publisher' => ['@id' => url('/').'#organization'],
            ],
        ], $this->schemas);

        return json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }
}
