@extends('home.homeLayout')
@push('styles')
    <link rel="preload" as="style" href="{{ asset('css/home.css') }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/home.css') }}"></noscript>
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
@endpush

@section('content')
    <main class="">
        <!-- Hero Section -->
        <section class="search-section w-100 pt-0 pb-16 mt-0">
            <div class="container-search container mt-0">
                <form class="row g-2 align-items-center justify-content-center" method="GET" action="{{ route('search') }}">
                    <div class="col-12 col-sm-5">
                        <label for="inputWhat" class="form-label visually-hidden">What</label>
                        <input type="text" name="keyword" class="form-control" id="inputWhat" placeholder="Enter keywords"
                            aria-label="What" />
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="inputWhere" class="form-label visually-hidden">Where</label>
                        <input type="text" name="location" class="form-control" id="inputWhere"
                            placeholder="Enter district, city, or state" aria-label="Where" />
                    </div>
                    <div class="col-12 col-sm-2 text-sm-start text-center">
                        <button type="submit" class="btn btn-seek w-100" aria-label="Seek Jobs Button">Search</button>
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
                                        href="{{ route('search', ['category' => $category->slug]) }}">
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
                <h2 class="text-center mb-4 fw-bold" style="user-select: text; font-size: 2.2rem;">Service Providers</h2>
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
                            <a href="{{ url('/' . ($organizer->slug ?? $organizer->id)) }}">
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
                                                        <li style="font-size:0.95rem;">and more ...</li>
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

        <!-- Opinions Section -->
        <div class="container-fluid courses overflow-hidden py-5">
            <div class="container">
                <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                    <!-- <h4 class="text-primary"> Our Features</h4> -->
                    <h2 class="section-title display-4 text-white mb-4">Our Features</h2>
                    <!-- <p class="text-white mb-0">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tenetur adipisci
                                facilis cupiditate recusandae aperiam temporibus corporis itaque quis facere, numquam, ad culpa
                                deserunt sint dolorem autem obcaecati, ipsam mollitia hic.
                            </p> -->
                </div>
                <div class="row gy-4 gx-0 justify-content-center">
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3"> Event Ticketing Made Easy</div>
                                <p class="mb-4">Empower organizers to create, promote, and sell tickets for any
                                    event—concerts, esports, workshops, and more.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Vendor & Service Management</div>
                                <p class="mb-4">Business owners like wedding planners, photographers, and caterers can
                                    showcase and manage their services seamlessly.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Smart Booking Calendar</div>
                                <p class="mb-4">Allow customers to check real-time availability and book services with an
                                    interactive, user-friendly calendar system.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Secure & Streamlined Payments</div>
                                <p class="mb-4">Integrated payment gateways ensure smooth, secure transactions for both
                                    event tickets and service bookings.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Dashboard & Analytics</div>
                                <p class="mb-4">Gain full control with an intuitive dashboard—track sales, manage bookings,
                                    and monitor your performance in real-time.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Centralized Platform for All</div>
                                <p class="mb-4">Whether you're planning an event or offering services, Sistem Kami brings
                                    everything under one roof—simple, scalable, and powerful.
                                </p>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>

        <section class="how-it-works py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="font-size: 1.2rem;">How It Works</h2>
                    <p class="">Simple steps for organizers, vendors, and customers to get started.</p>
                </div>
                <div class="row text-center">
                    <!-- Event Organizer -->
                    <div class="col-md-4 mb-4">
                        <div class="card position-relative h-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fa-solid fa-calendar-check fa-2x text-primary"></i>
                                </div>
                                <h5 class="card-title">For Event Organizers</h5>
                                <p class="card-text">
                                    Create and publish your events, set ticket pricing, track bookings, and manage
                                    participants — all in one place.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Vendor -->
                    <div class="col-md-4 mb-4">
                        <div class="card position-relative h-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fa-solid fa-briefcase fa-2x text-primary"></i>
                                </div>
                                <h5 class="card-title">For Service Vendors</h5>
                                <p class="card-text">
                                    List your services like photography or wedding planning, manage calendar availability,
                                    and accept client bookings easily.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Customers -->
                    <div class="col-md-4 mb-4">
                        <div class="card position-relative h-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fa-solid fa-user-check fa-2x text-primary"></i>
                                </div>
                                <h5 class="card-title">For Customers</h5>
                                <p class="card-text">
                                    Discover exciting events, book your favorite vendors, and manage all your tickets and
                                    bookings in one account.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Cookie Consent Banner -->
    <div id="cookieConsent" style="display:none;position:fixed;bottom:20px;left:20px;right:20px;z-index:9999;background:#fff;padding:16px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.12);display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="icon-info" style="width:48px;height:48px;background:#001f4d;color:#fff;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:700;">i</div>
            <div>
                <div style="font-weight:700;margin-bottom:4px;">We use cookies to give you the best online experience.</div>
                <div style="font-size:0.95rem;color:#4b5563;">By continuing to browse the site you are agreeing to our use of cookies.</div>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <button id="cookieDecline" class="btn btn-outline-secondary">Decline</button>
            <button id="cookieAccept" class="btn btn-primary">Accept</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function(){
            const COOKIE_NAME = 'cookie_consent';
            const QUEUE_KEY = 'visitor_log_queue';

            function getCookie(name) {
                const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                return v ? v.pop() : null;
            }
            function setCookie(name, value, days=365) {
                const d = new Date();
                d.setTime(d.getTime() + (days*24*60*60*1000));
                document.cookie = name + '=' + value + ';path=/;expires=' + d.toUTCString();
            }

            function queueLog(payload) {
                try {
                    const q = JSON.parse(localStorage.getItem(QUEUE_KEY) || '[]');
                    q.push(payload);
                    localStorage.setItem(QUEUE_KEY, JSON.stringify(q));
                } catch(e){ console.error(e); }
            }

            function flushQueue() {
                try {
                    const q = JSON.parse(localStorage.getItem(QUEUE_KEY) || '[]');
                    q.forEach(item => {
                        fetch('/visitor-log', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(item)
                        }).catch(err => console.error('Logging failed', err));
                    });
                    localStorage.removeItem(QUEUE_KEY);
                } catch(e){ console.error(e); }
            }

            function sendVisitorLog(payload) {
                const consent = getCookie(COOKIE_NAME);
                if (consent === '1') {
                    fetch('/visitor-log', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    }).then(res => res.json())
                    .then(data => console.log('Visitor logged', data))
                    .catch(err => console.error('Logging failed', err));
                } else {
                    // store until user accepts
                    queueLog(payload);
                }
            }

            // show banner if consent not set
            document.addEventListener('DOMContentLoaded', function() {
                const consent = getCookie(COOKIE_NAME);
                const banner = document.getElementById('cookieConsent');
                if (!consent) {
                    banner.style.display = 'flex';
                }

                document.getElementById('cookieAccept').addEventListener('click', function(){
                    setCookie(COOKIE_NAME, '1', 365);
                    banner.style.display = 'none';
                    flushQueue();
                });

                document.getElementById('cookieDecline').addEventListener('click', function(){
                    setCookie(COOKIE_NAME, '0', 365);
                    banner.style.display = 'none';
                    // clear any queued logs to respect decline
                    localStorage.removeItem(QUEUE_KEY);
                });

                // original visitor log replaced with consent-aware sender
                sendVisitorLog({ action: 'visit_page', page: 'home' });
            });
        })();
    </script>
@endpush