<?php

namespace App\Services;

use App\Models\CmsPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PageContent
{
    public function value(string $pageKey, string $section, string $field, mixed $default = null): mixed
    {
        $value = data_get($this->data($pageKey), "fields.{$section}.{$field}");

        return $value ?? $default;
    }

    public function imageUrl(string $pageKey, string $section, string $field): ?string
    {
        $path = $this->value($pageKey, $section, $field);

        return is_string($path) && $path !== ''
            ? Storage::disk('public')->url($path)
            : null;
    }

    public function order(string $pageKey, string $section, int $default): int
    {
        $order = data_get($this->data($pageKey), "sections.{$section}.order", $default);

        return is_numeric($order) ? (int) $order : $default;
    }

    public function enabled(string $pageKey, string $section): bool
    {
        return (bool) data_get($this->data($pageKey), "sections.{$section}.enabled", true);
    }

    public function clear(string $pageKey): void
    {
        Cache::forget($this->cacheKey($pageKey));
    }

    private function data(string $pageKey): array
    {
        return Cache::remember($this->cacheKey($pageKey), now()->addMinutes(10), function () use ($pageKey): array {
            return CmsPage::query()->where('page_key', $pageKey)->value('content') ?? [];
        });
    }

    private function cacheKey(string $pageKey): string
    {
        return 'cms.page.'.$pageKey;
    }
}
