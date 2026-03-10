@extends('home.homeLayout')
@push('styles')
    <link rel="preload" as="style" href="{{ asset('css/home.css') }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/home.css') }}"></noscript>
    <style>
    .feature-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 31, 77, 0.12);
    }
    .feature-card .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    .feature-card:hover .feature-icon { transform: scale(1.1); }
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .fade-in-up.visible { opacity: 1; transform: translateY(0); }
    .stagger-1 { transition-delay: 0.1s; }
    .stagger-2 { transition-delay: 0.2s; }
    .stagger-3 { transition-delay: 0.3s; }
    .stagger-4 { transition-delay: 0.4s; }
    .stagger-5 { transition-delay: 0.5s; }
    .stagger-6 { transition-delay: 0.6s; }
    </style>
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/SISTEM-KAMI-LOGO.png') }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "011-2406-9291",
        "contactType": "customer service",
        "areaServed": "MY"
    },
    "sameAs": [
        "https://www.instagram.com/sistemkami/"
    ]
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "{{ config('app.name') }}",
    "applicationCategory": "BusinessApplication",
    "operatingSystem": "Web",
    "description": "Online booking system in Malaysia for event organizers and vendors. Manage packages, schedules, and customer bookings all in one platform.",
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "MYR"
    },
    "url": "{{ url('/') }}",
    "areaServed": {
        "@type": "Country",
        "name": "Malaysia"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search') }}?keyword={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
@endpush

@section('content')
    <main class="">
        <!-- Hero Section -->
        <section class="search-section w-100 pt-0 pb-16 mt-0">
            <div class="container-search container mt-0">
                <h1 class="visually-hidden">{{ __('home.hero_h1', ['app' => config('app.name')]) }}</h1>
                <form class="row g-2 align-items-center justify-content-center" method="GET" action="{{ lroute('search') }}">
                    <div class="col-12 col-sm-5">
                        <label for="inputWhat" class="form-label visually-hidden">What</label>
                        <input type="text" name="keyword" class="form-control" id="inputWhat" placeholder="{{ __('home.search_keyword_placeholder') }}"
                            aria-label="What" />
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="inputWhere" class="form-label visually-hidden">Where</label>
                        <input type="text" name="location" class="form-control" id="inputWhere"
                            placeholder="{{ __('home.search_location_placeholder') }}" aria-label="Where" />
                    </div>
                    <div class="col-12 col-sm-2 text-sm-start text-center">
                        <button type="submit" class="btn btn-seek w-100" aria-label="Seek Jobs Button">{{ __('home.search_button') }}</button>
                    </div>
                </form>
                <nav class="filters-scroll mt-3" aria-label="Category filters">
                    <!-- Package Category Dropdown -->
                    @if($packageCategories->count())
                    <div class="dropdown d-inline-block me-2">
                        <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Category
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($packageCategories as $category)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ lroute('search', ['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </nav>
            </div>
        </section>

        @php
            // Ensure $organizers exists: use passed value or derive from $packages if available
            $organizers = $organizers ?? (isset($packages) ? $packages->pluck('organizer')->unique('id')->values() : collect());
        @endphp

        <!-- Service Providers Section -->
        <section class="container pt-10 pb-16 md:pb-20 lg:pb-24">
            <div>
                <h2 class="text-center mb-4 fw-bold" style="user-select: text; font-size: 2.2rem;">{{ __('home.service_providers') }}</h2>
                <div class="row g-4 justify-content-center">
                    @foreach ($organizers as $organizer)
                        @php
                            // packages related to this organizer (from $packages passed to view)
                            $orgPackages = isset($packages) ? $packages->filter(function($p) use ($organizer) {
                                return optional($p->organizer)->id == optional($organizer)->id;
                            }) : collect();

                            $firstPackage = $orgPackages->first();

                            $firstPackageImageUrl = null;
                            if ($firstPackage && $firstPackage->images && $firstPackage->images->first()) {
                                $firstPackageImageUrl = asset('storage/uploads/' . $organizer->id . '/packages/' . $firstPackage->id . '/' . $firstPackage->images->first()->url);
                            
                            // 2️⃣ Kalau tak ada package image → check slot_images
                            }elseif ($firstPackage->slot_images && $firstPackage->slot_images->first()) {

                                $firstPackageImageUrl = asset(
                                    'storage/uploads/' . $organizer->id .
                                    '/packages/' . $firstPackage->id . '/slots/' .
                                    $firstPackage->slot_images->first()->url
                                );

                            }

                            $minPrice = null;
                            $multiplePrices = false;
                            if ($orgPackages->count()) {
                                if (isset($organizer->what_flow) && $organizer->what_flow == 2) {
                                    // collect possible vendor time slots from packages
                                    $allSlots = $orgPackages->flatMap(function($p) {
                                        // try common slot relations/names
                                        if (isset($p->vendor_time_slots)) return $p->vendor_time_slots;
                                        if (isset($p->slots)) return $p->slots;
                                        if (isset($p->time_slots)) return $p->time_slots;
                                        if (isset($p->vendorTimeSlots)) return $p->vendorTimeSlots;
                                        return collect();
                                    });

                                    $prices = $allSlots->map(function($s) {
                                        if (is_array($s)) {
                                            return $s['slot_price'] ?? $s['price'] ?? null;
                                        }
                                        return $s->slot_price ?? $s->price ?? null;
                                    })->filter()->unique();

                                    $minPrice = $prices->min();
                                    $multiplePrices = $prices->count() > 1;
                                } else {
                                    $prices = $orgPackages->map(function($p) {
                                        return $p->final_price ?? $p->base_price;
                                    })->filter()->unique();

                                    $minPrice = $prices->min();
                                    $multiplePrices = $prices->count() > 1;
                                }
                            }
                        @endphp

                        <div class="col-12 col-sm-6 col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ lroute('business.profile', ['slug' => $organizer->slug ?? $organizer->id]) }}">
                                <div class="card position-relative">
                                    <div class="bookmark-icon" title="Category">
                                        {{ $organizer->category ?? '' }}
                                    </div>

                                    <picture>
                                        @if($organizer->first_package->display_image_webp_url)
                                            <source srcset="{{ $organizer->first_package->display_image_webp_url }}" type="image/webp">
                                        @endif
                                        <img src="{{ $organizer->first_package->display_image_url }}"
                                            class="d-block w-100 package-img"
                                            alt="{{ $organizer->name }}"
                                            width="400" height="260"
                                            @if($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif>
                                    </picture>

                                    <div class="card-body px-3 pb-3 pt-0" style="margin-top: 12px;">
                                        <div class="event-organizer text-primary" title="Organizer">By
                                            {{ $organizer->name }}
                                        </div>
                                        <div class="event-title mb-2" title="{{ $organizer->name }}" style="height: 30px;">
                                            {{ Str::limit(($organizer->name), 45) }}
                                        </div>

                                        <div class="event-desc" style="height: 80px; overflow: hidden;">
                                            @if($orgPackages->isNotEmpty())
                                                <ul class="mb-0 ps-3">
                                                    @foreach($orgPackages->take(3) as $pkg)
                                                        <li style="font-size:0.95rem;">{{ Str::limit($pkg->name, 60) }}</li>
                                                    @endforeach
                                                    @if($orgPackages->count() > 3)
                                                        <li style="font-size:0.95rem;">{{ __('home.and_more') }}</li>
                                                    @endif
                                                </ul>
                                            @else
                                                {{ Str::limit(strip_tags($organizer->description ?? ''), 140) }}
                                            @endif
                                        </div>

                                        <hr class="dashed-hr mb-2 mt-4" />
                                        @php
                                            $location = collect([
                                                $organizer->city ?? null,
                                                $organizer->state ?? null
                                            ])->filter()->implode(', ');
                                        @endphp

                                        <div class="event-footer d-flex justify-content-between align-items-center">
                                            @if ($location)
                                                <div class="location" title="Location"><i
                                                        class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $location }}</div>
                                            @else
                                                <div class="location" title="Location"></div>
                                            @endif

                                            @if($minPrice)
                                                <div class="event-price fw-bold">RM{{ number_format($minPrice, 2) }}@if(!empty($multiplePrices))<sup>*</sup>@endif</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5" style="background: linear-gradient(135deg, #001f4d 0%, #001233 100%);">
            <div class="container">
                <div class="text-center mx-auto mb-5 fade-in-up" style="max-width: 800px;">
                    <p class="fw-bold text-uppercase mb-2" style="letter-spacing: 0.1em; font-size: 0.85rem; color: rgba(255,255,255,0.55);">{{ __('home.features_label') }}</p>
                    <h2 class="fw-bold mb-3" style="font-size: 2rem; color: #fff !important;">{{ __('home.features_heading') }}</h2>
                    <p style="color: rgba(255,255,255,0.65); margin-bottom:0;">{{ __('home.features_sub') }}</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-1">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-calendar-check fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.booking_system') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.booking_system_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-2">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-boxes fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.package_slot') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.package_slot_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-3">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-credit-card fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.secure_payment') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.secure_payment_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-4">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-chart-line fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.dashboard_analytics') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.dashboard_analytics_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-5">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-users-cog fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.worker_commission') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.worker_commission_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-6">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                                <i class="fas fa-bell fa-lg" style="color: #001f4d;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.notifications') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.notifications_desc') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 fade-in-up stagger-1">
                        <div class="feature-card p-4 h-100">
                            <div class="feature-icon" style="background: rgba(99, 102, 241, 0.1);">
                                <i class="fas fa-robot fa-lg" style="color: #6366f1;"></i>
                            </div>
                            <h5 class="fw-bold mb-2">{{ __('home.ai_chatbot') }}</h5>
                            <p class="text-muted small mb-0">
                                {{ __('home.ai_chatbot_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Visitor log (consent-aware)
            if (localStorage.getItem('sk_cookie_consent') === 'accepted') {
                fetch('/visitor-log', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ action: 'visit_page', page: 'home' })
                }).catch(function(){});
            }
            // Scroll animations for feature cards
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.fade-in-up').forEach(function(el) { observer.observe(el); });
        });
    </script>
@endpush