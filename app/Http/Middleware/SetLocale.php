<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public const SUPPORTED = ['en', 'ms'];
    public const DEFAULT   = 'en';

    public function handle(Request $request, Closure $next): mixed
    {
        // Read locale from route action metadata (set via route group options)
        $locale = $request->route()?->getAction('locale') ?? self::DEFAULT;

        if (!in_array($locale, self::SUPPORTED)) {
            $locale = self::DEFAULT;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
