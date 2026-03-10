<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LocaleUrl
{
    /**
     * Generate a locale-aware URL for a named route.
     * In English: route('about'), in BM: route('bm.about')
     */
    public static function route(string $name, array $parameters = []): string
    {
        $locale = App::getLocale();
        $routeName = $locale === 'en' ? $name : "bm.{$name}";

        try {
            return route($routeName, $parameters);
        } catch (\Exception $e) {
            return '/';
        }
    }

    /**
     * Return the equivalent URL for the given target locale on the current page.
     * Used by the language switcher to generate the alternate language link.
     */
    public static function alternate(string $targetLocale): string
    {
        $currentRoute = Route::currentRouteName();

        if (!$currentRoute) {
            return $targetLocale === 'en' ? url('/') : url('/bm');
        }

        // Strip 'bm.' prefix if present to get the base route name
        $baseName = str_starts_with($currentRoute, 'bm.') ? substr($currentRoute, 3) : $currentRoute;

        $targetRouteName = $targetLocale === 'en' ? $baseName : "bm.{$baseName}";

        // Pass current route parameters (e.g. slug for profile pages)
        $params = Route::current()?->parameters() ?? [];

        try {
            return route($targetRouteName, $params);
        } catch (\Exception $e) {
            return $targetLocale === 'en' ? url('/') : url('/bm');
        }
    }
}
