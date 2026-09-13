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
        $locale = $locale ?: (string) App::getLocale();
        /** @var mixed $translations */
        $translations = $this->{$attribute};

        if (is_array($translations) && isset($translations[$locale])) {
            return is_string($translations[$locale]) ? $translations[$locale] : (string) $translations[$locale];
        }

        // Fallback to primary locale (e.g., 'tr') if current locale is not found
        $fallbackLocale = (string) config('app.fallback_locale', 'tr');
        if (is_array($translations) && isset($translations[$fallbackLocale])) {
            return is_string($translations[$fallbackLocale]) ? $translations[$fallbackLocale] : (string) $translations[$fallbackLocale];
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
