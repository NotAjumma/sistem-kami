@php
    $routeName  = request()->route()->getName() ?? '';
    $isHome     = str_ends_with($routeName, 'business.profile');
    $isWedding  = str_ends_with($routeName, 'special-page.wedding');
    $locale     = app()->getLocale();
    // Build locale-aware URLs directly from $specialPage so they always work
    // regardless of which route (business.profile or event.slug) served this page
    $homeUrl    = \App\Helpers\LocaleUrl::route('business.profile', ['slug' => $specialPage]);
    $weddingUrl = \App\Helpers\LocaleUrl::route('special-page.wedding', ['slug' => $specialPage]);
    $enHomeUrl  = route('business.profile',      ['slug' => $specialPage]);
    $bmHomeUrl  = route('bm.business.profile',   ['slug' => $specialPage]);
    $enLangUrl  = $isWedding ? route('special-page.wedding',    ['slug' => $specialPage]) : $enHomeUrl;
    $bmLangUrl  = $isWedding ? route('bm.special-page.wedding', ['slug' => $specialPage]) : $bmHomeUrl;
@endphp

<nav class="ldt-nav" id="ldt-nav">
    <div class="nav-inner">
        <a href="{{ $homeUrl }}" class="nav-brand">{{ $organizer->name }}</a>
        <ul class="nav-links">
            <li><a href="{{ $homeUrl }}" class="{{ $isHome ? 'active' : '' }}">{{ __('lady_d_touch.nav_home') }}</a></li>
            <li><a href="{{ $isHome ? '#story' : $homeUrl . '#story' }}">{{ __('lady_d_touch.nav_story') }}</a></li>
            <li><a href="{{ $weddingUrl }}" class="{{ $isWedding ? 'active' : '' }}">{{ __('lady_d_touch.nav_wedding') }}</a></li>
            <li><a href="{{ $isHome ? '#location' : $homeUrl . '#location' }}">{{ __('lady_d_touch.nav_location') }}</a></li>
            @if($organizer->phone)
            <li><a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}?text=Hi%2C%20I%27m%20interested%20in%20your%20wedding%20package." target="_blank">{{ __('lady_d_touch.nav_contact') }}</a></li>
            @endif
            <li>
                <div class="lang-switcher">
                    <a href="{{ $enLangUrl }}" class="{{ $locale === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ $bmLangUrl }}" class="{{ $locale === 'ms' ? 'active' : '' }}">BM</a>
                </div>
            </li>
        </ul>
        <button class="nav-toggle" id="ldt-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="ldt-mobile-menu" id="ldt-mobile-menu">
    <div class="mobile-lang">
        <a href="{{ $enLangUrl }}" class="{{ $locale === 'en' ? 'active' : '' }}">EN</a>
        <a href="{{ $bmLangUrl }}" class="{{ $locale === 'ms' ? 'active' : '' }}">BM</a>
    </div>
    <a href="{{ $homeUrl }}" class="ldt-ml{{ $isHome ? ' active' : '' }}">{{ __('lady_d_touch.nav_home') }}</a>
    <a href="{{ $isHome ? '#story' : $homeUrl . '#story' }}" class="ldt-ml">{{ __('lady_d_touch.nav_story') }}</a>
    <a href="{{ $weddingUrl }}" class="ldt-ml{{ $isWedding ? ' active' : '' }}">{{ __('lady_d_touch.nav_wedding') }}</a>
    <a href="{{ $isHome ? '#location' : $homeUrl . '#location' }}" class="ldt-ml">{{ __('lady_d_touch.nav_location') }}</a>
    @if($organizer->phone)
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}" target="_blank" class="ldt-ml">{{ __('lady_d_touch.nav_contact') }}</a>
    @endif
</div>
