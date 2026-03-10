@extends('home.homeLayout')
@push('styles')
    <link rel="preload" as="style" href="{{ asset('css/home.css') }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/home.css') }}"></noscript>
@endpush

@section('content')
    <main class="">
        <section class="search-section w-100 pt-0 pb-16 mt-0">
            <div class="container-search container mt-0">
                <form class="row g-2 align-items-center justify-content-center">
                    <!-- Keyword input with clear button -->
                    <div class="col-12 col-sm-5">
                        <div class="position-relative">
                            <label for="inputWhat" class="form-label visually-hidden">What</label>
                            <input type="text" name="keyword" class="form-control pe-5" id="inputWhat"
                                placeholder="{{ __('search.search_keyword_placeholder') }}" value="{{ request()->input('keyword') }}" aria-label="What" />
                            @if(request()->filled('keyword'))
                                <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                                    data-bs-toggle="tooltip" title="Clear"
                                    onclick="document.getElementById('inputWhat').value=''; this.closest('form').submit();"
                                    aria-label="Clear keyword" style="z-index: 2; line-height: 1;">
                                    <i class="fa-solid fa-x" style="font-size: 0.7rem;"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Location input with clear button -->
                    <div class="col-12 col-sm-5">
                        <div class="position-relative">
                            <label for="inputWhere" class="form-label visually-hidden">Where</label>
                            <input type="text" name="location" class="form-control pe-5" id="inputWhere"
                                placeholder="{{ __('search.search_location_placeholder') }}" value="{{ request()->input('location') }}"
                                aria-label="Where" />
                            @if(request()->filled('location'))
                                <button type="button" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                                    data-bs-toggle="tooltip" title="Clear"
                                    onclick="document.getElementById('inputWhere').value=''; this.closest('form').submit();"
                                    aria-label="Clear location" style="z-index: 2; line-height: 1;">
                                    <i class="fa-solid fa-x" style="font-size: 0.7rem;"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-2 text-sm-start text-center">
                        <button type="submit" class="btn btn-seek w-100" aria-label="Search Button">{{ __('search.search_button') }}</button>
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
                                    <a class="dropdown-item {{ request('category') == $category->slug ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['category' => $category->slug]) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            @if (request()->has('category'))
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger"
                                        href="{{ request()->fullUrlWithQuery(['category' => null]) }}">
                                        Clear Filter
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </nav>
            </div>
        </section>
        <section class="container pb-16">
            <div>
                <span class="job-count" aria-live="polite" aria-atomic="true">
                    {{ trans_choice(__('search.result') . '|' . __('search.results'), $organizers->count(), ['count' => $organizers->count()]) }}
                </span>

                <div class="row g-4 justify-content-start">
                    @foreach ($organizers as $organizer)
                        @php
                            $orgPackages = $organizer->search_packages ?? collect();
                            $firstPackage = $orgPackages->first();

                            $minPrice = null;
                            $multiplePrices = false;
                            if ($orgPackages->count()) {
                                if (isset($organizer->what_flow) && $organizer->what_flow == 2) {
                                    $allSlots = $orgPackages->flatMap(function($p) {
                                        if (isset($p->vendorTimeSlots)) return $p->vendorTimeSlots;
                                        return collect();
                                    });
                                    $prices = $allSlots->map(function($s) {
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

                        <div class="col-12 col-sm-6 col-xl-3 col-lg-4 col-md-6 mt-5">
                            <a href="{{ lroute('business.profile', ['slug' => $organizer->slug ?? $organizer->id]) }}">
                                <div class="card position-relative">
                                    <div class="bookmark-icon" title="Category">
                                        {{ $organizer->category ?? '' }}
                                    </div>

                                    @if($firstPackage)
                                    <picture>
                                        @if($firstPackage->display_image_webp_url)
                                            <source srcset="{{ $firstPackage->display_image_webp_url }}" type="image/webp">
                                        @endif
                                        <img src="{{ $firstPackage->display_image_url }}"
                                            class="d-block w-100 package-img"
                                            alt="{{ $organizer->name }}"
                                            width="400" height="260"
                                            loading="lazy" decoding="async">
                                    </picture>
                                    @endif

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
                                                        <li style="font-size:0.95rem;">{{ __('search.and_more') }}</li>
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

                @if($organizers->isEmpty())
                    <div class="text-center py-5 mt-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('search.no_results') }}</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush