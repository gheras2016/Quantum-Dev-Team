<?php

namespace App\Models\Concerns;

/**
 * Helper for models whose `title` / `description` columns store a JSON map
 * of locale => value (e.g. {"ar": "...", "en": "..."}).
 *
 * Usage in Blade:  {{ $service->translate('title') }}
 */
trait HasLocalizedContent
{
    public function translate(string $attribute, ?string $locale = null): ?string
    {
        $value = $this->getAttribute($attribute);

        if (! is_array($value)) {
            return $value;
        }

        $locale ??= app()->getLocale();

        return $value[$locale]
            ?? $value[config('app.fallback_locale')]
            ?? collect($value)->first();
    }

    public function getTitleTextAttribute(): ?string
    {
        return $this->translate('title');
    }

    public function getDescriptionTextAttribute(): ?string
    {
        return $this->translate('description');
    }
}
