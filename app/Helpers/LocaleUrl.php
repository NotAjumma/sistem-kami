<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class LocaleUrl
{
    const PREFIXES = ['bm.' => 'ms', 'zh.' => 'zh'];
    const ROUTE_AS  = ['en' => '', 'ms' => 'bm.', 'zh' => 'zh.'];

    /**
     * Generate a locale-aware URL for a named route.
     */
    public static function route(string $name, array $parameters = []): string
    {
        $as = self::ROUTE_AS[App::getLocale()] ?? '';
        try {
            return route($as . $name, $parameters);
        } catch (\Exception $e) {
            return '/';
        }
    }

    /**
     * Return the equivalent URL for the given target locale on the current page.
     */
    public static function alternate(string $targetLocale): string
    {
        $fallbacks = ['en' => url('/'), 'ms' => url('/bm'), 'zh' => url('/zh')];
        $currentRoute = Route::currentRouteName();

        if (!$currentRoute) {
            return $fallbacks[$targetLocale] ?? url('/');
        }

        // Strip any locale prefix to get the base route name
        $baseName = $currentRoute;
        foreach (array_keys(self::PREFIXES) as $pfx) {
            if (str_starts_with($currentRoute, $pfx)) {
                $baseName = substr($currentRoute, strlen($pfx));
                break;
            }
        }

        $as = self::ROUTE_AS[$targetLocale] ?? '';
        $params = Route::current()?->parameters() ?? [];

        try {
            return route($as . $baseName, $params);
        } catch (\Exception $e) {
            return $fallbacks[$targetLocale] ?? url('/');
        }
    }
}
