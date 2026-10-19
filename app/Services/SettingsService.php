<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

/**
 * Reads editable site content. Database values (key "group.field") override
 * the defaults declared in config/crm.php.
 */
class SettingsService
{
    protected ?array $values = null;

    public function all(): array
    {
        return $this->values ??= Setting::allCached();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $values = $this->all();
        if (array_key_exists($key, $values) && $values[$key] !== null && $values[$key] !== '') {
            return $values[$key];
        }

        [$group, $field] = array_pad(explode('.', $key, 2), 2, null);
        $configDefault = config("crm.groups.{$group}.fields.{$field}.default");

        return $configDefault ?? $default;
    }

    /**
     * Parse a "lines" field. Lines containing "|" become ['title' => …, 'text' => …].
     */
    public function lines(string $key): array
    {
        $raw = (string) $this->get($key, '');

        return collect(preg_split('/\r\n|\r|\n/', $raw))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->map(function ($line) {
                if (! str_contains($line, '|')) {
                    return ['title' => $line, 'text' => null];
                }
                [$title, $text] = array_map('trim', explode('|', $line, 2));

                return ['title' => $title, 'text' => $text];
            })
            ->values()
            ->all();
    }

    /** Converts "*word*" into an italic accent span, escaping everything else. */
    public function accent(string $key, string $class = 'accent-italic'): HtmlString
    {
        return self::accentify((string) $this->get($key, ''), $class);
    }

    public static function accentify(string $text, string $class = 'accent-italic'): HtmlString
    {
        $escaped = e($text);

        return new HtmlString(preg_replace('/\*(.+?)\*/u', '<em class="'.$class.'">$1</em>', $escaped));
    }

    public function save(string $group, array $data): void
    {
        foreach (Arr::only($data, array_keys(config("crm.groups.{$group}.fields", []))) as $field => $value) {
            Setting::updateOrCreate(['key' => "{$group}.{$field}"], ['group' => $group, 'value' => $value]);
        }
        $this->values = null;
    }
}
