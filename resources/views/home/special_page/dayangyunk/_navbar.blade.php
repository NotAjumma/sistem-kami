@php
    $routeName   = request()->route()->getName() ?? '';
    $isHome      = str_ends_with($routeName, 'business.profile');
    $isWedding   = str_ends_with($routeName, 'special-page.wedding');
    $isPackages  = str_ends_with($routeName, 'special-page.packages');
    $homeUrl     = route('business.profile',      ['slug' => $specialPage]);
    $weddingUrl  = route('special-page.wedding',  ['slug' => $specialPage]);
    $packagesUrl = route('special-page.packages', ['slug' => $specialPage]);
@endphp

<nav class="ldt-nav" id="ldt-nav">
    <div class="nav-inner">
        <a href="{{ $homeUrl }}" class="nav-brand">{{ $organizer->name }}</a>
        <ul class="nav-links">
            <li><a href="{{ $homeUrl }}" class="{{ $isHome ? 'active' : '' }}">Laman Utama</a></li>
            <li><a href="{{ $isHome ? '#story' : $homeUrl . '#story' }}">Kisah Kami</a></li>
            <li><a href="{{ $weddingUrl }}" class="{{ $isWedding ? 'active' : '' }}">Perkahwinan</a></li>
            <li><a href="{{ $packagesUrl }}" class="{{ $isPackages ? 'active' : '' }}">Pakej</a></li>
            <li><a href="{{ $isHome ? '#location' : $homeUrl . '#location' }}">Lokasi</a></li>
            @if($organizer->phone)
            <li><a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}?text=Assalamualaikum%2C%20saya%20berminat%20dengan%20pakej%20perkahwinan%20anda." target="_blank">Hubungi Kami</a></li>
            @endif
        </ul>
        <button class="nav-toggle" id="ldt-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="ldt-mobile-menu" id="ldt-mobile-menu">
    <a href="{{ $homeUrl }}" class="ldt-ml{{ $isHome ? ' active' : '' }}">Laman Utama</a>
    <a href="{{ $isHome ? '#story' : $homeUrl . '#story' }}" class="ldt-ml">Kisah Kami</a>
    <a href="{{ $weddingUrl }}" class="ldt-ml{{ $isWedding ? ' active' : '' }}">Perkahwinan</a>
    <a href="{{ $packagesUrl }}" class="ldt-ml{{ $isPackages ? ' active' : '' }}">Pakej</a>
    <a href="{{ $isHome ? '#location' : $homeUrl . '#location' }}" class="ldt-ml">Lokasi</a>
    @if($organizer->phone)
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}" target="_blank" class="ldt-ml">Hubungi Kami</a>
    @endif
</div>
