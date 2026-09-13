<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Get a translated attribute value.
     *
     * @param string $attribute
     * @param string|null $locale
     * @return string|null
     */
    public function getTranslation(string $attribute, ?string $locale = null): ?string
    {
        $locale = $locale ?: App::getLocale();
        $translations = $this->{$attribute};

        if (is_array($translations) && isset($translations[$locale])) {
            return $translations[$locale];
        }

        // Fallback to primary locale (e.g., 'tr') if current locale is not found
        $fallbackLocale = config('app.fallback_locale', 'tr');
        if (is_array($translations) && isset($translations[$fallbackLocale])) {
            return $translations[$fallbackLocale];
        }

        return null;
    }

    /**
     * Set a translated attribute value.
     *
     * @param string $attribute
     * @param string $locale
     * @param string $value
     * @return $this
     */
    public function setTranslation(string $attribute, string $locale, string $value): self
    {
        $translations = $this->{$attribute} ?? [];
        if (!is_array($translations)) {
            $translations = [];
        }

        $translations[$locale] = $value;
        $this->{$attribute} = $translations;

        return $this;
    }
}
