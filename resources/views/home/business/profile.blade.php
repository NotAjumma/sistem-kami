@extends('home.homeLayout')
@push('styles')
    <link rel="preload" as="style" href="{{ asset('css/profile.css') }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/profile.css') }}"></noscript>
    @if(!empty($packages) && $packages->first() && !empty($packages->first()->images) && $packages->first()->images->first())
        <link rel="preload" as="image" href="{{ asset('storage/uploads/' . $organizer->id . '/packages/' . $packages->first()->id . '/' . $packages->first()->images->first()->url) }}">
    @endif
@endpush

@push('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ $organizer->name ?? '' }}",
    "description": "{{ Str::limit(strip_tags($organizer->description ?? ''), 200) }}",
    "url": "{{ url('/' . ($organizer->slug ?? $organizer->id)) }}",
    "image": "{{ $seo['image'] ?? asset('images/og-default.jpg') }}",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "{{ $organizer->city ?? '' }}",
        "addressRegion": "{{ $organizer->state ?? '' }}",
        "addressCountry": "MY"
    },
    "telephone": "{{ $organizer->phone ?? '' }}"
}
</script>
@endpush

@section('content')
    <div class="container">

        {{-- Carousel --}}
        <!-- Size banner 1500px x 350px -->
        <!-- <div id="organizerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner" id="carouselInner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center align-items-center" style="height:350px;">
                        <div class="spinner-border text-secondary"></div>
                    </div>
                </div>
            </div>
            @if(!empty($organizer->banner_path) && count($organizer->banner_path) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#organizerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#organizerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div> -->

        <!-- Profile Section -->
        <section id="profile" class="position-relative mb-5 mt-5">
            <div class="card-profile profile-card">
                <div class="profile-intro">
                    <div class="profile-intro-name w-100 gap-2">
                        <div>
                            <h2 class="mb-0 text-center">{{ $organizer->name }}</h2>
                            <p class="mb-1 fst-italic text-primary text-center">{{ $organizer->category }}</p>
                        </div>
                    </div>

                    @php
                        $maxLengthLaptop = 300;
                        $maxLengthMobile = 100;

                        $excerptMobile = Str::limit($organizer->description, $maxLengthMobile);
                        $excerptLaptop = Str::limit($organizer->description, $maxLengthLaptop);

                        $isLongMobile = strlen($organizer->description) > $maxLengthMobile;
                        $isLongLaptop = strlen($organizer->description) > $maxLengthLaptop;
                    @endphp

                   {{-- Mobile --}}
                    <!-- <p class="profile-intro-desc mb-2 d-block d-md-none">
                        <span id="desc-preview-mobile-{{ $organizer->id }}">
                            {{ $excerptMobile }}
                            @if($isLongMobile)
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }}, 'mobile')">Read more</a>
                            @endif
                        </span>

                        @if($isLongMobile)
                            <span id="desc-full-mobile-{{ $organizer->id }}" style="display: none;">
                                {{ $organizer->description }}
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }}, 'mobile')">Show less</a>
                            </span>
                        @endif
                    </p>

                    {{-- Laptop --}}
                    <p class="profile-intro-desc mb-2 d-none d-lg-block">
                        <span id="desc-preview-laptop-{{ $organizer->id }}">
                            {{ $excerptLaptop }}
                            @if($isLongLaptop)
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }}, 'laptop')">Read more</a>
                            @endif
                        </span>

                        @if($isLongLaptop)
                            <span id="desc-full-laptop-{{ $organizer->id }}" style="display: none;">
                                {{ $organizer->description }}
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }}, 'laptop')">Show less</a>
                            </span>
                        @endif
                    </p> -->


                    @php
                        $addressParts = array_filter([
                            $organizer->address_line1,
                            $organizer->address_line2,
                            $organizer->postal_code,
                            $organizer->city,
                            $organizer->state
                        ]);
                    @endphp

                    @if (!empty($addressParts))
                        <div class="event-organizer text-primary text-center">
                            <span>{{ implode(', ', $addressParts) }}</span>
                        </div>
                    @endif
                </div>

            </div>
        </section>

       

        <!-- Packages Section -->
        <!-- Size package img 1024px x 1024px -->
        <section>
            <h3 class="mb-4 fw-bold text-center" style="font-size: 1.5rem; margin-top: 3rem;">
                @if($organizer->what_flow == 2)
                1. Pilih Tema
                @else
                1. Pilih Pakej
                @endif
            </h3>
            <div class="row g-4">
                @foreach($packages as $package)
                    <div class="col-12 col-md-6 col-xl-6">
                        <article 
                            class="card border-white portfolio-item transition-all duration-200 d-flex flex-column h-100"
                        >
                            @if(!empty($package->images) && count($package->images) > 0)
                                <div class="bookmark-icon" title="Bookmark">
                                    {{ $package->category->name }}
                                </div>
                            @endif

                            @if($package->items->where('show_on_card', 1)->first())
                                <div class="item-icon">
                                    {{ $package->items->where('show_on_card', 1)->first()->title }}
                                </div>
                            @endif

                            {{-- Package image --}}
                            <div id="packageCarousel_{{ $package->id }}" class="carousel slide mb-4 package-carousel"
                                data-bs-ride="carousel">
                                <div class="carousel-inner" id="carouselInner_{{ $package->id }}">
                                    @if(!empty($package->images) && count($package->images) > 0)
                                        @foreach($package->images as $img)
                                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                <img src="{{ asset('storage/uploads/' . $organizer->id . '/packages/' . $package->id . '/' . $img->url) }}"
                                                    class="d-block w-100"
                                                    style="height:260px; object-fit:cover;"
                                                    alt="{{ $img->alt_text ?? $package->name }}"
                                                    @if($loop->parent->first && $loop->first) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif
                                                    width="600" height="260">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <div class="d-flex justify-content-center align-items-center" style="height:260px;">
                                                <span class="text-muted">No images</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if(!empty($package->images) && count($package->images) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel_{{ $package->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel_{{ $package->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>

                            <div class="portfolio-content d-flex flex-column flex-grow-1">
                                <h4 class="event-title portfolio-title text-center">{{ $package->name }}</h4>

                                @php
                                    $validDiscount = $package->discounts[0] ?? null;
                                @endphp
                                @if($validDiscount && $validDiscount->is_active)
                                    <p class="text-danger mb-1">
                                        @if($validDiscount->type === 'percentage')
                                            Save {{ $validDiscount->amount }}%!
                                        @else
                                            Save RM {{ number_format($validDiscount->amount, 2) }}!
                                        @endif
                                    </p>
                                @endif

                                @php
                                    $originalPrice = $package->base_price;
                                    $finalPrice = $originalPrice;
                                    if ($validDiscount && $validDiscount->is_active) {
                                        if ($validDiscount->type === 'percentage') {
                                            $finalPrice = $originalPrice - ($originalPrice * $validDiscount->amount / 100);
                                        } else {
                                            $finalPrice = $originalPrice - $validDiscount->amount;
                                        }
                                    }
                                @endphp

                                @if($package->is_manual != 2)
                                <p class="event-title portfolio-price text-center mt-3 ">
                                    @if($finalPrice < $originalPrice)
                                        <del class="text-muted">RM {{ number_format($originalPrice, 2) }}</del><br>
                                    @endif
                                    <div class="event-title portfolio-price text-center">RM {{ number_format($finalPrice, 2) }}</div>
                                </p>
                                @endif

                                {{-- Description or items --}}
                                @if($package->description)
                                    <div class="event-desc text-center">
                                        {{ Str::limit(strip_tags($package->description), 340) }}
                                    </div>
                                @else
                                    @if($organizer->what_flow != 2)
                                        @php
                                            $includedItems = collect($package->items ?? [])->filter(function($i) {
                                                return ($i['is_optional'] ?? 0) == 0;
                                            })->pluck('title')->map(function($t) {
                                                return Str::limit($t, 80);
                                            })->toArray();
                                        @endphp

                                        @if(count($includedItems))
                                            <div class="event-title portfolio-title text-center fw-bold text-dark">
                                                @foreach($includedItems as $item)
                                                    <div>{{ $item }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                {{-- Select / Selected button --}}
                                <div class="mt-auto">
                                    <button type="button"
                                        class="package-btn btn btn-light w-100 text-dark"
                                        data-package-id="{{ $package->id }}"
                                        data-package-slot-qty="{{ $package->package_slot_quantity }}"
                                        data-package-name="{{ $package->name }}"
                                        data-excluded-slots='@json($package->exclude_vendor_time_slots ?? [])'>
                                        Select
                                    </button>
                                </div>

                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>

        @if($organizer->what_flow != 4)
        <section>
            <h3 class="mb-4 fw-bold text-center" style="font-size: 1.5rem;  margin-top: 3rem;">
                @if($organizer->what_flow == 2)
                2. Pilih Pakej
                @else
                2. Pilih Tema
                @endif
            </h3>
            <div class="row g-4">
                @foreach($slots as $slot)
                    <div class="col-12 col-md-6 col-xl-6">
                        <article class="card portfolio-item transition-all duration-200">
                            @if($organizer->what_flow != 2)
                            <div id="packageCarousel_{{ $slot->id }}" class="carousel slide mb-4 package-carousel"
                                data-bs-ride="carousel">
                                <div class="carousel-inner" id="carouselInner_slot_{{ $slot->id }}">
                                    @if(!empty($slot->images) && count($slot->images) > 0)
                                        @foreach($slot->images as $imgIndex => $img)
                                            <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/uploads/' . $organizer->id . '/slots/' . $slot->id . '/' . $img->url) }}"
                                                    class="d-block w-100"
                                                    style="height:260px; object-fit:cover;"
                                                    alt="{{ $img->alt_text ?? $slot->slot_name ?? 'Slot image' }}"
                                                    loading="lazy" decoding="async"
                                                    width="600" height="260">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <div class="d-flex justify-content-center align-items-center" style="height:260px;">
                                                <span class="text-muted">No images</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if(!empty($slot->images) && count($slot->images) > 1)
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#packageCarousel_{{ $slot->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#packageCarousel_{{ $slot->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                            @endif

                            <div class="portfolio-content">
                                <h4 class="event-title portfolio-title text-center">{{ $slot->slot_name ?? 'Tema' }}</h4>
                                @if($organizer->what_flow == 2)
                                    <h4 class="event-title portfolio-title text-center">RM {{ $slot->slot_price }}</h4>
                                    <h4 class="event-title portfolio-title text-center">{{ $slot->pax }}</h4>
                                    <h4 class="event-title portfolio-title text-center">{{ $slot->duration_minutes }} minit</h4>
                                @endif
                                <div class="mt-3">
                                    <button type="button"
                                        class="slot-btn btn btn-light w-100 text-dark"
                                        data-slot-id="{{ $slot->id }}"
                                        data-slot-price="{{ $slot->slot_price }}">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Select Package / Slot Warning Modal -->
        <div class="modal fade" id="packageSlotModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Perhatian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($organizer->what_flow == 2)
                    Sila pilih tema dahulu.
                @else
                    Sila pilih pakej dahulu.
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
        </div>


        <section style="margin-top: 3rem;">
            <div id="calendarSection">

                <!-- Default Placeholder -->
                <div id="calendarDefaultBox" 
                    class="border rounded-3 p-5 text-center bg-light">

                    <div class="mb-3">
                        <i class="fa-solid fa-calendar-check text-primary" style="font-size: 1.5rem;"></i>
                    </div>

                    <h5 class="fw-semibold mb-2">
                        Sila pilih pakej dan tema dahulu
                    </h5>

                    <p class="text-muted mb-0">
                        Pilih pakej dan tema untuk melihat tarikh dan masa yang tersedia.
                    </p>
                </div>
                
                <div id="calendarLoading" class="d-none d-flex justify-content-center align-items-center" style="height: 150px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted small">Loading calendar time slots...</div>
                    </div>
                </div>

                <div id="calendarContent" class="d-none">
                    <h3 class="mb-4 fw-bold mt-3 text-center" style="font-size: 1.3rem;">
                        @if($organizer->what_flow == 4)
                        2. 
                        @else
                        3.
                        @endif
                        Pilih Tarikh
                    </h3>
                    <hr class="mb-4" />
                    <!-- Legend -->
                    <div class="mt-0">
                        <h5 class="fw-semibold mb-1">Calendar Indicator:</h5>
                        <ul class="list-inline row gx-2 gy-2">
                            <li class="list-inline-item col-6 col-md-auto">
                                <span class="legend-box bg-secondary"></span> Today
                            </li>
                            <li class="list-inline-item col-6 col-md-auto">
                                <span class="legend-box bg-success"></span> Booked Date
                            </li>
                            <li class="list-inline-item col-6 col-md-auto">
                                <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Not
                                Available
                            </li>
                            <li class="list-inline-item col-6 col-md-auto">
                                <span class="legend-box bg-primary"></span> Selected Date
                            </li>
                        </ul>
                    </div>

                    <!-- Calendar Navigation Controls -->
                    <div class="calendar-navigation mt-3">
                        <div class="row g-2 col-12">
                            <!-- Previous Button -->
                            <div class="col-12 col-md-12">
                                <button id="prevMonth" class="btn btn-primary w-100"
                                    style="text-align: center; display: flex; justify-content: center;">
                                    <div>
                                        <i class="fa-regular fa-square-caret-left me-2"></i>
                                    </div>
                                    <div>Previous</div>
                                </button>
                            </div>

                            <!-- Month & Year Selectors -->
                            <div class="col-12 col-md-12 d-flex gap-2">
                                <select id="monthSelect" class="form-select w-50" style="font-size: 1rem;">
                                    <!-- Populated via JS -->
                                </select>
                                <select id="yearSelect" class="form-select w-50" style="font-size: 1rem;">
                                    <!-- Populated via JS -->
                                </select>
                            </div>

                            <!-- Today and Next Button -->
                            <div class="col-12 col-md-12">
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <button id="todayBtn" class="btn btn-secondary w-100">Today</button>
                                    <button id="nextMonth" class="btn btn-primary w-100"
                                        style="text-align: center; display: flex; justify-content: center;">
                                        <div>Next</div>
                                        <div><i class="fa-regular fa-square-caret-right ms-2"></i></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="calendar table table-bordered mb-3">
                        <thead>
                            <tr>
                                <th>Mo</th>
                                <th>Tu</th>
                                <th>We</th>
                                <th>Th</th>
                                <th>Fr</th>
                                <th>Sa</th>
                                <th style="text-align:center !important;">Su</th>
                            </tr>
                        </thead>
                        <tbody id="calendarBody">
                            <!-- Calendar will be dynamically generated here -->
                        </tbody>
                    </table>

                    <!-- Time Slots -->
                    <div id="timeSlots" class="d-none" style="margin-top: 3rem;">
                        <hr class="my-4" />
                        <h6 class="fw-semibold mb-2 text-center" style="font-size: 1.5rem;">
                            @if($organizer->what_flow == 4)
                            3. 
                            @else
                            4.
                            @endif
                            Pilih Masa
                        </h6>
                        <hr class="mb-4" />
                        <!-- Legend -->
                        <div class="mt-0">
                            <h5 class="fw-semibold mb-1">Time Slots Indicator:</h5>
                            <ul class="list-inline row gx-2 gy-2">
                                <li class="list-inline-item col-6 col-md-auto">
                                    <span class="legend-box bg-success"></span> Booked Time
                                </li>
                                <li class="list-inline-item col-6 col-md-auto">
                                    <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span>
                                    Available
                                </li>
                                <li class="list-inline-item col-6 col-md-auto">
                                    <span class="legend-box bg-primary"></span> Selected Date
                                </li>
                            </ul>
                        </div>
                        <div class="table-responsive mt-2" style="overflow-x: auto;">
                            <table class="table table-bordered text-center align-middle" id="slotTable">
                                <thead class="">
                                    <tr id="slotHeader">
                                        <th></th>
                                        <!-- JS will append time slots here -->
                                    </tr>
                                </thead>
                                <tbody id="slotBody">
                                    <!-- JS will append courts + time slot checkboxes here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($package->is_manual)
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <input type="hidden" name="organizer_id" value="{{ $organizer->id }}">

                        <input type="hidden" name="selected_date" id="selected_date">
                        <input type="hidden" name="selected_time" id="selected_time">
                        <input type="hidden" name="selected_slot" id="selected_slot">

                        <!-- Manual package → WhatsApp -->
                        <a href="#" 
                            class="btn btn-success mt-5 w-100" 
                            id="whatsappNowBtn" 
                            style="pointer-events: none; opacity: 0.5;">
                            WhatsApp Now
                        </a>
                    @else
                        <!-- Normal package → Form submission -->
                        <form id="packageForm" action="{{ route('business.select_package') }}" method="post">
                            @csrf

                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="organizer_id" value="{{ $organizer->id }}">

                            <input type="hidden" name="selected_date" id="selected_date">
                            <input type="hidden" name="selected_time" id="selected_time">
                            <input type="hidden" name="selected_slot" id="selected_slot">

                            <button type="submit" class="btn btn-primary mt-5 w-100" id="bookNowBtn" disabled>Book Now</button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        {{-- Google Map --}}
        @if($organizer->google_map_show)
        @if($organizer->latitude && $organizer->longitude)
            @php
                $mapQuery = $organizer->is_gmaps_verified
                    ? urlencode($organizer->office_name) . '%20' . $organizer->latitude . ',' . $organizer->longitude
                    : $organizer->latitude . ',' . $organizer->longitude;
            @endphp

            <div style="margin-top: 50px;">
                <iframe
                    src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed"
                    width="100%" height="500"
                    frameborder="0"
                    style="border:0"
                    allowfullscreen
                    loading="lazy">
                </iframe>
            </div>


        @endif
        @endif

    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Step 1: Customer Details -->
                <div id="step1">
                    <div class="modal-header">
                        <h5 class="modal-title">Customer Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Name</label>
                            <input type="text" id="customerName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerWhatsApp" class="form-label">WhatsApp Number</label>
                            <input type="text" id="customerWhatsApp" class="form-control" placeholder="0123456789" required>
                        </div>

                        @if($organizer->what_flow != 3)
                            <div class="mb-3">
                                <label for="paymentOption" class="form-label">Payment</label>
                                <select id="paymentOption" class="form-select">
                                    <option value="full">Full Payment</option>
                                    <option value="deposit">Deposit</option>
                                </select>
                            </div>

                            <!-- Deposit Info -->
                            <div class="mb-3 d-none" id="depositWrapper">
                                <label class="form-label">Deposit Amount</label>
                                <input type="number" id="depositAmount" class="form-control" min="50" value="50">
                                <small class="text-muted">Minimum deposit RM50</small>
                            </div>
                        @else
                            <div class="d-none mb-3">
                                <label for="paymentOption" class="form-label">Payment</label>
                                <select id="paymentOption" class="form-select">
                                    <option value="full">Full Payment</option>
                                    <option value="deposit">Deposit</option>
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="depositRemarkWrapper">
                                <small class="text-muted">Minimum deposit RM50</small>
                            </div>
                        @endif

                        @if($organizer->what_flow == 2)
                        <div class="mb-3">
                            <label for="selectedPackageSlot" class="form-label">Slot</label>
                            <input type="text" id="selectedPackageSlot" class="form-control" readonly placeholder="Sila pilih pakej dan tema dahulu">
                        </div>
                        <div class="mb-3">
                            <label for="modalSelectedDate" class="form-label">Date</label>
                            <input type="text" id="modalSelectedDate" value="0" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="selectedPackageSlot" class="form-label">Package</label>
                            <input type="text" id="selectedSlotDisplay" class="form-control" readonly placeholder="Sila pilih pakej dan tema dahulu">
                        </div>

                        <div class="mb-3">
                            <label for="customerWhatsApp" class="form-label">Package Price (RM)</label>
                            <input type="text" id="modalSlotPrice" value="0" class="form-control" readonly>
                        </div>
                        @else
                        <div class="mb-3">
                            <label for="selectedPackageSlot" class="form-label">Package</label>
                            <input type="text" id="selectedPackageSlot" class="form-control" readonly placeholder="Sila pilih pakej dan tema dahulu">
                        </div>

                        <div class="mb-3">
                            <label for="customerWhatsApp" class="form-label">Package Price (RM)</label>
                            <input type="text" id="modalPackagePrice" value="0" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="modalSelectedDate" class="form-label">Date</label>
                            <input type="text" id="modalSelectedDate" value="0" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="selectedPackageSlot" class="form-label">Slot</label>
                            <input type="text" id="selectedSlotDisplay" class="form-control" readonly placeholder="Sila pilih pakej dan tema dahulu">
                        </div>
                        @endif

                        <input type="hidden" id="selectedPackageId">


                        @if($organizer->what_flow != 3)
                        <hr>

                            @if($package->addons->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Add-Ons</label>

                                @foreach($package->addons as $addon)
                                    <div class="border rounded p-2 mb-2">

                                        @if($addon->is_qty)
                                            <!-- Quantity Type -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $addon->name }}</strong>
                                                    @if($addon->hint)
                                                        <span class="text-muted small">
                                                            {{ $addon->hint }}
                                                        </span>
                                                    @endif
                                                    <div class="text-muted small">
                                                        RM {{ number_format($addon->price, 2) }}
                                                    </div>
                                                </div>

                                                <input 
                                                    type="number"
                                                    class="form-control addon-qty"
                                                    data-name="{{ $addon->name }}"
                                                    data-price="{{ $addon->price }}"
                                                    data-special-start="{{ $addon->special_date_start }}"
                                                    data-special-end="{{ $addon->special_date_end }}"
                                                    min="0"
                                                    value="0"
                                                    style="width: 90px;"
                                                >
                                            </div>

                                        @else
                                            <!-- Checkbox Type -->
                                            <div class="form-check">
                                                <input 
                                                    type="checkbox"
                                                    class="form-check-input addon-checkbox"
                                                    data-name="{{ $addon->name }}"
                                                    data-price="{{ $addon->price }}"
                                                    data-special-start="{{ $addon->special_date_start }}"
                                                    data-special-end="{{ $addon->special_date_end }}"
                                                    id="addon_{{ $addon->id }}"
                                                >
                                                <label class="form-check-label" for="addon_{{ $addon->id }}">
                                                    {{ $addon->name }} (RM {{ number_format($addon->price, 2) }})
                                                </label>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                            @endif
                        <hr>

                        <div class="bg-light p-3 rounded">

                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total</strong>
                                <strong>RM <span id="totalAmount">0.00</span></strong>
                            </div>

                            <div id="depositSummary" class="d-none">

                                <div class="d-flex justify-content-between">
                                    <span>Deposit</span>
                                    <span>RM <span id="depositDisplay">0.00</span></span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span>Balance</span>
                                    <span>RM <span id="balanceDisplay">0.00</span></span>
                                </div>

                            </div>
                        @else
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total</strong>
                                <strong>RM <span id="totalAmount">0.00</span></strong>
                            </div>

                            <div id="depositSummary" class="d-none">

                                <div class="d-flex justify-content-between">
                                    <span>Deposit</span>
                                    <span>RM <span id="depositDisplay">0.00</span></span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span>Balance</span>
                                    <span>RM <span id="balanceDisplay">0.00</span></span>
                                </div>

                            </div>

                        @endif
                        </div>
                    </div>
                    @if($organizer->what_flow != 3)
                    <div class="modal-footer">
                        <button type="button" id="nextToTNC" class="btn btn-primary">Next</button>
                    </div>
                    @else
                    <div class="modal-footer">
                        <button type="button" id="confirmBooking" class="btn btn-success">WhatsApp Now</button>
                    </div>
                    @endif
                </div>

                <!-- Step 2: Terms & Conditions -->
                <div id="step2" class="d-none">
                    <div class="modal-header">
                        <h5 class="modal-title">Terms & Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        {!! $package->tnc !!}
                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" id="acceptTNC">
                            <label class="form-check-label" for="acceptTNC">I agree to the Terms & Conditions</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="backToStep1" class="btn btn-outline-secondary">Back</button>
                        <button type="button" id="confirmBooking" class="btn btn-primary" disabled>Proceed to WhatsApp</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
const whatFlow = {{ $organizer->what_flow }};
let slotPrice = 0.00;
function updateSelectedSlotInput() {
    const selectedSlotDisplay = document.getElementById('selectedSlotDisplay');
    const modalSelectedDate = document.getElementById('modalSelectedDate');
    const selected_date = document.getElementById('selected_date');

    if (!selectedTimes || !selectedTimes.length) {
        selectedSlotDisplay.value = "Sila pilih tema dahulu";
        return;
    }

    // Map to display format, e.g., "Court 1 (10:00 AM), Court 2 (11:00 AM)"
    const slotText = selectedTimes
        .map(s => `${s.slot_name} (${s.time})`)  // replace s.id with s.name if you have slot names
        .join(", ");

    selectedSlotDisplay.value = slotText;
    const rawDate = selected_date.value;
    const dateObj = new Date(rawDate);

    const formattedDate = dateObj.toLocaleDateString('en-MY', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    modalSelectedDate.value = formattedDate;

}
document.addEventListener('DOMContentLoaded', () => {

    let selectedPackageId = null;
    let selectedPackageQty = 0;
    let packagePrice = 0;
    const packageButtons = document.querySelectorAll('.package-btn');
    const slotButtons = document.querySelectorAll('.slot-btn');

    function updatePackagesUI() {
        packageButtons.forEach(b => {
            const card = b.closest('.card');
            const pId = parseInt(b.dataset.packageId);
            if (selectedPackageId === pId) {
                card.classList.add('border-primary','shadow-sm');
                card.classList.remove('border-white');
                b.classList.add('btn-primary','text-white');
                b.classList.remove('btn-light','text-dark');
                b.textContent = 'Selected';
            } else {
                card.classList.remove('border-primary','shadow-sm');
                card.classList.add('border-white');
                b.classList.remove('btn-primary','text-white');
                b.classList.add('btn-light','text-dark');
                b.textContent = 'Select';
            }
        });
    }

    function updateSlotsUI() {
        slotButtons.forEach(b => {
            const card = b.closest('.card');
            const slotId = parseInt(b.dataset.slotId);
            if (selectedSlotIds.includes(slotId)) {
                card.classList.add('border-primary','shadow-sm');
                card.classList.remove('border-white');
                b.classList.add('btn-primary','text-white');
                b.classList.remove('btn-light','text-dark');
                b.textContent = 'Selected';
            } else {
                card.classList.remove('border-primary','shadow-sm');
                card.classList.add('border-white');
                b.classList.remove('btn-primary','text-white');
                b.classList.add('btn-light','text-dark');
                b.textContent = 'Select';
            }
        });
    }

    function updateSelectedPackageSlot() {
        const input = document.getElementById("selectedPackageSlot");

        if (!selectedPackageId) {
            input.value = "";
            input.placeholder = "Sila pilih pakej dan tema dahulu";
            return;
        }

        // Get package name from button dataset
        const pkgBtn = document.querySelector(`.package-btn[data-package-id='${selectedPackageId}']`);
        const packageName = pkgBtn ? pkgBtn.dataset.packageName : "Pakej Terpilih";

        // Get selected slot names (optional: use selectedTimes or slot buttons)
        const slotNames = selectedSlotIds.map(id => {
            const slotBtn = document.querySelector(`.slot-button[data-slot-id='${id}']`);
            return slotBtn ? slotBtn.dataset.slotName : "";
        }).filter(Boolean);

        const slotText = slotNames.length ? ` | Slot: ${slotNames.join(", ")}` : "";

        input.value = packageName + slotText;
    }

    function resetTimeSlotUI() {

        const slotHeader = document.getElementById("slotHeader");
        const slotBody   = document.getElementById("slotBody");
        const timeSlotSection = document.getElementById("timeSlotSection");

        if (slotHeader) slotHeader.innerHTML = "<th></th>";
        if (slotBody) slotBody.innerHTML = "";
        if (timeSlotSection) timeSlotSection.classList.add("d-none");
    }

    packageButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            const pkgId  = parseInt(btn.dataset.packageId);
            const pkgName  = btn.dataset.packageName;
            const pkgQty = parseInt(btn.dataset.packageSlotQty);
            // console.log("pkgId");
            // console.log(pkgId);
            if (selectedPackageId === pkgId) {

                // 🔴 DESELECT
                selectedPackageId  = null;
                selectedPackageQty = 0;
                selectedSlotIds    = [];
                selectedTimes      = [];

                resetTimeSlotUI();
                document.getElementById('calendarDefaultBox')?.classList.add('d-none');
                document.getElementById('calendarContent')?.classList.remove('d-none');


            } else {

                document.getElementById('calendarDefaultBox')?.classList.remove('d-none');
                document.getElementById('calendarContent')?.classList.add('d-none');

                selectedPackageId  = pkgId;
                selectedPackageQty = pkgQty;

                selectedSlotIds = [];
                selectedTimes   = [];

                document.getElementById("selected_date").value = "";

                resetTimeSlotUI();

                fetchCalendarData(pkgId);

                // 🔥 NEW PART
                const excludedSlots = JSON.parse(btn.dataset.excludedSlots || "[]")
                    .map(id => parseInt(id));

                slotButtons.forEach(slotBtn => {
                    const slotId = parseInt(slotBtn.dataset.slotId);
                    const wrapper = slotBtn.closest('.col-12');

                    if (excludedSlots.includes(slotId)) {
                        wrapper.classList.add('d-none');
                    } else {
                        wrapper.classList.remove('d-none');
                    }
                });

                // 🔥 AUTO SELECT IF QTY > 1
                selectedSlotIds = [];

                if (selectedPackageQty > 1) {

                    const availableSlots = [];

                    slotButtons.forEach(slotBtn => {
                        const slotId = parseInt(slotBtn.dataset.slotId);
                        const wrapper = slotBtn.closest('.col-12');

                        if (!wrapper.classList.contains('d-none')) {
                            availableSlots.push(slotId);
                        }
                    });

                    // Auto select based on selectedPackageQty
                    selectedSlotIds = availableSlots.slice(0, selectedPackageQty);
                }


                let selectedPackageSlot = document.getElementById('selectedPackageSlot');
                if (selectedPackageSlot) {
                    selectedPackageSlot.value = pkgName;
                }
                let selectedPackageIdInput = document.getElementById('selectedPackageId');
                if (selectedPackageIdInput) {
                    selectedPackageIdInput.value = pkgId;
                }
            }

            updatePackagesUI();
            updateSlotsUI();
            updateSelectedPackageSlot();
        });
    });


    slotButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            if (!selectedPackageId) {
                const modal = new bootstrap.Modal(document.getElementById('packageSlotModal'));
                modal.show();
                return;
            }


            const slotId = parseInt(btn.dataset.slotId);
            slotPrice = parseFloat(btn.dataset.slotPrice);

            let modalSlotPriceInput = document.getElementById('modalSlotPrice');
            if (modalSlotPriceInput) {
                modalSlotPriceInput.value = slotPrice.toFixed(2);
            }

            // If already selected → unselect
            if (selectedSlotIds.includes(slotId)) {
                selectedSlotIds = selectedSlotIds.filter(id => id !== slotId);

            } else {

                // 🔥 IF ONLY 1 ALLOWED → REPLACE
                if (selectedPackageQty === 1) {

                    selectedSlotIds = [slotId]; // replace directly

                } else {

                    if (selectedSlotIds.length < selectedPackageQty) {
                        selectedSlotIds.push(slotId);
                    } else {
                        alert('Anda hanya boleh pilih ' + selectedPackageQty + ' tema.');
                        return;
                    }
                }
            }

            updateSlotsUI();
            updateSelectedPackageSlot();

            const selectedDate = document.getElementById('selected_date').value;
            if (selectedDate) {
                renderTimeSlot(selectedDate, new Date(selectedDate), selectedPackageId);
            }
        });
    });

    //  const packageButtons = document.querySelectorAll('.package-btn');

    // packageButtons.forEach(btn => {
    //     btn.addEventListener('click', function () {

    //         const packageId = this.dataset.packageId;

    //         if (!packageId) return;

    //         fetchCalendarData(packageId);

    //     });
    // });

    function fetchCalendarData(packageId) {
        const calendarSection = document.getElementById('calendarSection');
        const loading = document.getElementById('calendarLoading');
        const content = document.getElementById('calendarContent');


        document.getElementById('calendarDefaultBox')?.classList.add('d-none');

        // show loading
        loading.classList.remove('d-none');
        content.classList.add('d-none');

        fetch(`/packages/${packageId}/calendar-data`)
            .then(res => res.json())
            .then(data => {
                currentDate = new Date();
                calendarData = data;
                currentPackageId = packageId;
                renderCalendar(currentDate, calendarData, currentPackageId);

                // console.log(data.package.final_price);
                packagePrice = parseFloat(data.package.final_price || 0);

                const modalPriceInput = document.getElementById('modalPackagePrice');
                if (modalPriceInput) {
                    modalPriceInput.value = packagePrice.toFixed(2);
                }

                calculateSummary();

                // hide loading, show content
                loading.classList.add('d-none');
                content.classList.remove('d-none');
            })
            .catch(err => {
                console.error(err);
                loading.classList.add('d-none');
                alert('Failed to load calendar');
            });
    }


        window.applySpecialDateAddons = function() {
            const selectedDate = document.getElementById('selected_date').value;
            if (!selectedDate) return;

            const selected = new Date(selectedDate);
            selected.setHours(0,0,0,0);

            document.querySelectorAll('[data-special-start]').forEach(el => {
                const start = el.dataset.specialStart;
                const end   = el.dataset.specialEnd;
                const wrapper = el.closest('.border');

                if (!start || !end) {
                    wrapper.classList.remove('d-none');
                    if (el.type === "checkbox") el.disabled = false;
                    if (el.type === "number") el.readOnly = false;
                    return;
                }

                const startDate = new Date(start);
                startDate.setHours(0,0,0,0);
                const endDate = new Date(end);
                endDate.setHours(0,0,0,0);

                if (selected >= startDate && selected <= endDate) {
                    wrapper.classList.remove('d-none');
                    if (el.type === "checkbox") { el.checked = true; el.disabled = true; }
                    if (el.type === "number") { el.value = 1; el.readOnly = true; }
                } else {
                    wrapper.classList.add('d-none');
                    if (el.type === "checkbox") { el.checked = false; el.disabled = false; }
                    if (el.type === "number") { el.value = 0; el.readOnly = false; }
                }
            });

            calculateSummary();
        };

        function calculateSummary() {

            let addOnTotal           = 0;
            let packageTotal         = 0;
            let addOns               = [];
            const paymentOption      = document.getElementById('paymentOption');
            const depositAmountInput = document.getElementById('depositAmount');

            const isManual = {{ $organizer->what_flow }} == 2;

            if (isManual) {
                // let selectedTimes = [];
                // try {
                //     selectedTimes = JSON.parse(selectedTimeInput.value || "[]");
                // } catch(e) {}

                // selectedTimes.forEach(slot => {
                //     packageTotal += parseFloat(slot.price || 0);
                // });
                packageTotal =  slotPrice ;
                console.log("packageTotal");
                console.log(packageTotal);

            } else {
                packageTotal =  packagePrice ;
            }

            // Addons
            document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
                addOnTotal += parseFloat(cb.dataset.price);
                addOns.push(cb.dataset.name);
                
            });

            document.querySelectorAll('.addon-qty').forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    addOnTotal += qty * parseFloat(input.dataset.price);
                    addOns.push(`${input.dataset.name} x${qty}`);
                }
            });

            let grandTotal = packageTotal + addOnTotal;

            // Always update main total
            document.getElementById('totalAmount').innerText = grandTotal.toFixed(2);

            // 🔥 Deposit Mode
            if (paymentOption.value === 'deposit') {

                let depositAmount = parseFloat(depositAmountInput.value) || 0;

                // Optional: auto cap deposit if > total
                if (depositAmount > grandTotal) {
                    depositAmount = grandTotal;
                }

                const balance = grandTotal - depositAmount;

                document.getElementById('depositDisplay').innerText = depositAmount.toFixed(2);
                document.getElementById('balanceDisplay').innerText = balance.toFixed(2);

            } else {

                // Reset deposit display when not used
                document.getElementById('depositDisplay').innerText = "0.00";
                document.getElementById('balanceDisplay').innerText = "0.00";
            }

            return {
                grandTotal: grandTotal,
                addOns: addOns
            };
        }


        function updateSlotPriceDisplay(selectedTimes) {

            const isManual = {{ $package->is_manual }} == 2;
            if (!isManual) return;

            let total = 0;

            selectedTimes.forEach(slot => {
                total += parseFloat(slot.price || 0);
            });

            const display = document.getElementById('slotPriceDisplay');
            if (display) {
                display.value = total.toFixed(2);
            }

            calculateSummary(); // update grand total too
        }

            const whatsappNowBtn = document.getElementById("whatsappNowBtn");
            const fastTrack = {{ $organizer->what_flow }} === 3;
            const selectedDateInput = document.getElementById("selected_date");
            const selectedTimesInput = document.getElementById("selected_time");
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));

            document.getElementById('selected_date').addEventListener('change', applySpecialDateAddons);

            // Step elements
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const nextToTNC = document.getElementById('nextToTNC');
            const backToStep1 = document.getElementById('backToStep1');
            const confirmBooking = document.getElementById('confirmBooking');
            const acceptTNC = document.getElementById('acceptTNC');

            // WhatsApp numbers with names 
            const whatsappContacts = @json($whatsappNumbers);
            if (whatsappNowBtn) {
                whatsappNowBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    bookingModal.show();
                    step1.classList.remove('d-none');
                    step2.classList.add('d-none');
                });

                setTimeout(() => {
                    applySpecialDateAddons();
                }, 200);
            }

            // Step 1 → Step 2
            if (nextToTNC) {
                nextToTNC.addEventListener('click', () => {

                    const name = document.getElementById('customerName').value.trim();
                    const whatsapp = document.getElementById('customerWhatsApp').value.trim();

                    if (!name || !whatsapp) {
                        return alert("Tolong isi nama dan nombor WhatsApp");
                    }

                    // 🔥 IF FLOW 3 → DIRECT TO WHATSAPP
                    if (fastTrack) {
                        confirmBooking.click();
                        return;
                    }

                    // Normal flow → go to TNC
                    step1.classList.add('d-none');
                    step2.classList.remove('d-none');
                });
            }

            // Back button
            backToStep1.addEventListener('click', () => {
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
            });

            // Enable confirm button if T&C accepted
            acceptTNC.addEventListener('change', e => {
                confirmBooking.disabled = !e.target.checked;
            });

            const paymentOption = document.getElementById('paymentOption');
            const depositWrapper = document.getElementById('depositWrapper');
            const depositSummary = document.getElementById('depositSummary');
            const depositAmountInput = document.getElementById('depositAmount');

            paymentOption.addEventListener('change', function () {
                if (this.value === 'deposit') {
                    depositSummary.classList.remove('d-none');
                    depositWrapper.classList.remove('d-none');
                } else {
                    depositSummary.classList.add('d-none');
                    depositWrapper.classList.add('d-none');
                }

                calculateSummary(); // very important
            });

            document.addEventListener('change', function(e){
                if (
                    e.target.classList.contains('addon-checkbox') ||
                    e.target.id === 'paymentOption'
                ) {
                    calculateSummary();
                }
            });

            document.addEventListener('input', function(e){
                if (
                    e.target.classList.contains('addon-qty') ||
                    e.target.id === 'depositAmount'
                ) {
                    calculateSummary();
                }
            });

            // run once on load
            calculateSummary();


            // Confirm → WhatsApp
            confirmBooking.addEventListener('click', () => {
                const nameCustomer = document.getElementById('customerName').value.trim();
                const whatsappCustomer = document.getElementById('customerWhatsApp').value.trim();

                if (!nameCustomer || !whatsappCustomer) {
                    return alert("Tolong isi nama dan nombor WhatsApp");
                }
                bookingModal.hide();

                console.log("Booking confirmed");
                const date = selectedDateInput.value;
                if (!date) return;

                const { grandTotal, addOns } = calculateSummary();

                const selectedPerson = pickWeightedRandom(whatsappContacts);
                const studioAdminPhone = selectedPerson.phone;
                const studioAdminName  = selectedPerson.name;

                let selectedSlots = [];
                try { selectedSlots = JSON.parse(selectedTimesInput.value || "[]"); } 
                catch(e) { selectedSlots = []; }
                if (!selectedSlots.length) return;

                let nameInput = document.getElementById('customerName');
                let name = nameInput.value.trim();

                name = name
                    .toLowerCase()
                    .split(" ")
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" ");

                nameInput.value = name;

                const whatsapp = document.getElementById('customerWhatsApp').value.trim();
                const payment = document.getElementById('paymentOption').value;
                let paymentText = "";

                if (payment === 'deposit') {

                    let depositValue = parseFloat(depositAmountInput.value) || 0;

                    // Safety: prevent deposit > total
                    if (depositValue > grandTotal) {
                        depositValue = grandTotal;
                    }

                    const balance = grandTotal - depositValue;

                    paymentText = `Payment : Deposit Type \nTotal : RM ${grandTotal.toFixed(2)}\nDeposit : RM ${depositValue.toFixed(2)}\nBalance : RM ${balance.toFixed(2)}`;

                } else {

                    paymentText =
                `Payment : Full Payment\n Total   : RM ${grandTotal.toFixed(2)}`;

                }

                const whatFlow = {{ $organizer->what_flow }} == 2;

                let titlePakej = 'Pakej';
                let titleSlot = 'Slot';
                if(whatFlow){
                    titlePakej = 'Slot';
                    titleSlot = 'Pakej';
                }
                // Format date
                const dateObj = new Date(date);
                const formattedDate = dateObj.toLocaleDateString('ms-MY', { day: 'numeric', month:'long', year:'numeric' });

                const selectedSlotDisplay = document.getElementById('selectedSlotDisplay');
                const selectedPackageSlot = document.getElementById('selectedPackageSlot');
                const selectedPackageId = document.getElementById('selectedPackageId');

                let pkgId = selectedPackageId.value;

                // Slots text
                let slotsText = selectedSlotDisplay.value;

                // WhatsApp message
                let packageTitle = selectedPackageSlot.value;
                let message = `
                Hai ${studioAdminName}, \n\n Saya ${name} dan ingin menempah \n\n "${titlePakej}" : "${packageTitle}" \n Tarikh : ${formattedDate} \n ${titleSlot} : ${slotsText}.`;
                if (addOns.length) {
                    message += `\n\nAdd-ons:\n${addOns.map(a => `- ${a}`).join("\n")}`;
                }

                const promoterName = @json($promoter?->name);

                message += `\n\n ${paymentText}`;
                if (promoterName) {
                    message += `\n\nPromoted by ${promoterName}`;
                }
                

                // Optional: log click to backend
                fetch('/track/whatsapp', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({
                        organizer_id: "{{ $organizer->id }}",
                        package_id: pkgId,
                        date: date,
                        time: selectedSlots.map(s => s.time),
                        slot_name: selectedSlots.map(s => s.id),
                        customer_name: name,
                        whatsapp: whatsapp,
                        payment: payment,
                        add_ons: addOns
                    })
                }).then(res => console.log('WhatsApp click logged'))
                .catch(err => console.error('Logging failed', err));

                // Open WhatsApp
                window.open(`https://api.whatsapp.com/send?phone=${studioAdminPhone}&text=${encodeURIComponent(message)}`, '_blank');
            });

            // Weighted random helper
            function pickWeightedRandom(contacts) {
                const totalWeight = contacts.reduce((sum, c) => sum + (parseInt(c.weight) || 1), 0);
                let rand = Math.random() * totalWeight;

                for (let i = 0; i < contacts.length; i++) {
                    rand -= (parseInt(contacts[i].weight) || 1);
                    if (rand <= 0) {
                        return contacts[i];
                    }
                }

                return contacts[contacts.length - 1];
            }

            

});
</script>
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
                localStorage.removeItem(QUEUE_KEY);
            });

            // send profile visit log (consent-aware)
            sendVisitorLog({ action: 'visit_page', page: 'profile', reference_id: "{{ $organizer->id }}", uri: window.location.href });
        });
    })();
</script>
    <!-- calendar script -->
    
    <script>
        let calendarData            = null;
        let currentPackageId        = null;
        let currentDate             = new Date();

        const calendarBody          = document.getElementById("calendarBody");
        const currentMonthDisplay   = document.getElementById("currentMonth");
        const prevMonthBtn          = document.getElementById("prevMonth");
        const nextMonthBtn          = document.getElementById("nextMonth");
        const maxBookingOffset      = @json($package->max_booking_year_offset ?? 2);
        // console.log(vendorOffDays);
        const timeSlotSection       = document.getElementById("timeSlots");

        function renderCalendar(date, data, packageId) {
            const vendorTimeSlots      = data.timeSlots;
            const vendorOffDays        = data.offDays;
            const bookedVendorDates    = data.bookedDates;
            const fullyBookedDates     = data.fullyBookedDates;
            const limitReachedDays     = data.limitReachedDays;
            const weekRangeBlock       = data.weekRangeBlock;
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-indexed (0 = January)
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const firstWeekday = (firstDay.getDay() + 6) % 7; // Make Monday = 0
            const totalDays = lastDay.getDate();
            // Keep full info for each off day
            const offDaysFormatted = vendorOffDays.map(off => ({
                date: off.off_date,
                start_time: off.start_time,
                end_time: off.end_time
            }));
            
            let bookedDatesFormatted = [];

            if (vendorTimeSlots.length > 0) {
                bookedDatesFormatted = [...new Set(fullyBookedDates.map(b => b.date_start))];
            } else {
                bookedDatesFormatted = [...new Set(bookedVendorDates.map(b => b.date_start))];
            }
            
            // Update header
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            monthSelect.value = month;
            yearSelect.value = year;

            // Clear previous calendar
            calendarBody.innerHTML = "";

            let row = document.createElement("tr");
            let dayCount = 0;

            // Fill blanks before 1st
            for (let i = 0; i < firstWeekday; i++) {
                row.appendChild(document.createElement("td"));
                dayCount++;
            }

            // Fill days
            for (let day = 1; day <= totalDays; day++) {
                const td = document.createElement("td");
                td.textContent = day;

                const currentLoopDate = new Date(year, month, day);
                const yyyy = currentLoopDate.getFullYear();
                const mm = String(currentLoopDate.getMonth() + 1).padStart(2, '0');
                const dd = String(currentLoopDate.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;

                // Highlight today
                const today = new Date();
                const maxDate = new Date(today.getFullYear() + maxBookingOffset, 11);

                const offDayForDate = offDaysFormatted.find(off => off.date === formattedDate);

                today.setHours(0, 0, 0, 0); // Normalize time
                currentLoopDate.setHours(0, 0, 0, 0);
                if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    td.classList.add("today");
                }

                if (bookedDatesFormatted.includes(formattedDate)) {
                    td.classList.add("booked-day");
                    td.title = "Unavailable (Fully Booked)";
                } else if (limitReachedDays.includes(formattedDate)) {
                    td.classList.add("past-day");
                    td.title = "Booking limit reached for this week";
                } else if (offDayForDate) {
                    if ((!offDayForDate.start_time && !offDayForDate.end_time) ||
                        (offDayForDate.start_time === "00:00:00" && offDayForDate.end_time === "23:59:59")) {
                        // td.classList.add("off-day");
                        td.classList.add("booked-day");
                        // td.title = "Unavailable (Off Day)";
                        td.title = "Unavailable (Fully Booked)";
                    }
                } else if (currentLoopDate.getTime() < today.getTime()) {
                    td.classList.add("past-day");
                    td.title = "Cannot book past date";
                } else {
                    // Check if in a blocked week
                    const weekWithBooking = weekRangeBlock.find(range => {
                        const [start, end] = range.split(' - ');
                        return formattedDate >= start && formattedDate <= end;
                    });

                    if (weekWithBooking) {
                        if (bookedDatesFormatted.includes(formattedDate)) {
                            td.classList.add("booked-day");
                            td.title = "Unavailable (Fully Booked)";
                        } else {
                            td.classList.add("past-day");
                            td.title = "Booking limit reached for this week";
                        }
                    }
                }

                const isPrevMonthBeforeToday =
                    year < today.getFullYear() ||
                    (year === today.getFullYear() && month <= today.getMonth());

                prevMonthBtn.disabled = isPrevMonthBeforeToday;

                // Disable next button if going beyond maxDate (e.g. Dec 2027)
                const isNextMonthAfterLimit =
                    currentDate.getFullYear() > maxDate.getFullYear() ||
                    (currentDate.getFullYear() === maxDate.getFullYear() && currentDate.getMonth() >= maxDate.getMonth());

                nextMonthBtn.disabled = isNextMonthAfterLimit;

                td.addEventListener("click", () => {

                     // 🚫 BLOCK unavailable days
                    if (
                        td.classList.contains("off-day") ||
                        td.classList.contains("booked-day") ||
                        td.classList.contains("past-day")
                    ) {
                        return; // stop execution
                    }
                    
                    document.querySelectorAll("#calendarBody td").forEach(cell => cell.classList.remove("selected"));
                    td.classList.add("selected");

                    // Log selected date in yyyy-mm-dd format
                    const selectedDate = new Date(year, month, day);
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    const formattedDate = `${yyyy}-${mm}-${dd}`;

                    // Set the hidden input value
                    document.getElementById("selected_date").value = formattedDate;
                    applySpecialDateAddons();
                    updateSelectedSlotInput();
                    if (vendorTimeSlots.length > 0) {
                        const shouldHide =
                            currentLoopDate.getTime() < today.getTime() ||
                            offDaysFormatted.includes(formattedDate) ||
                            limitReachedDays.includes(formattedDate);

                        if (shouldHide) {
                            timeSlotSection.classList.add("d-none");
                        } else {
                            timeSlotSection.classList.remove("d-none");
                            renderTimeSlot(formattedDate, currentLoopDate, packageId, data.package.base_price);
                        }
                    } else {
                        checkDateSelected(bookedDatesFormatted, offDaysFormatted, currentLoopDate, formattedDate);
                    }   
                });

                row.appendChild(td);
                dayCount++;

                if (dayCount % 7 === 0) {
                    calendarBody.appendChild(row);
                    row = document.createElement("tr");
                }
            }

            // Fill blanks after last day
            while (dayCount % 7 !== 0) {
                row.appendChild(document.createElement("td"));
                dayCount++;
            }
            calendarBody.appendChild(row);
        }

        const todayBtn = document.getElementById("todayBtn");

        todayBtn.addEventListener("click", () => {
            currentDate = new Date();
            if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
            document.getElementById("selected_date").value = "";
            document.getElementById("bookNowBtn").setAttribute("disabled", true);
        });

        prevMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
        });

        nextMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
        });

        // Initial render
        // renderCalendar(currentDate);

        const selectedDateInput = document.getElementById('selected_date');
        const bookNowBtn        = document.getElementById('bookNowBtn');
        const whatsappNowBtn    = document.getElementById('whatsappNowBtn');
        const selectedTimeInput = document.getElementById("selected_time");

        function checkDateSelected(bookedDatesFormatted, offDaysFormatted, currentLoopDate, formattedDate) {
            if (selectedDateInput.value && bookedDatesFormatted.includes(formattedDate)) {
                // Disable 
                if (bookNowBtn) bookNowBtn.setAttribute('disabled', true);
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'none';
                    whatsappNowBtn.style.opacity = 0.5;
                }
            } else if (limitReachedDays.includes(formattedDate)) {
                if (bookNowBtn) bookNowBtn.setAttribute('disabled', true);
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'none';
                    whatsappNowBtn.style.opacity = 0.5;
                }
            } else if (offDaysFormatted.includes(formattedDate)) {
                if (bookNowBtn) bookNowBtn.setAttribute('disabled', true);
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'none';
                    whatsappNowBtn.style.opacity = 0.5;
                }
            } else if (currentLoopDate.getTime() < today.getTime()) {
                td.classList.add("past-day");
                td.title = "Cannot book past date";
            } else {
                // Enable
                if (bookNowBtn) bookNowBtn.removeAttribute('disabled');
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'auto';
                    whatsappNowBtn.style.opacity = 1;
                }
            }
        }

        function checkTimeSlotSelected(currentLoopDate) {
            const selectedTimeInput = document.getElementById("selected_time");
            const selectedSlotInput = document.getElementById("selected_slot");
            const bookNowBtn        = document.getElementById("bookNowBtn");
            const whatsappNowBtn    = document.getElementById("whatsappNowBtn");

            // Parse JSON safely
            let selected = [];
            try {
                selected = JSON.parse(selectedTimeInput.value || "[]");
            } catch (e) {
                selected = [];
            }

            // Enable if there are selected slots, disable otherwise
            if (Array.isArray(selected) && selected.length > 0) {
                if (bookNowBtn) {
                    bookNowBtn.removeAttribute("disabled");
                }
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'auto';
                    whatsappNowBtn.style.opacity = 1;
                }
            } else {
                if (bookNowBtn) {
                    bookNowBtn.setAttribute("disabled", true);
                }
                if (whatsappNowBtn) {
                    whatsappNowBtn.style.pointerEvents = 'none';
                    whatsappNowBtn.style.opacity = 0.5;
                }
            }
        }

    </script>
    <script>
        const monthSelect = document.getElementById("monthSelect");
        const yearSelect = document.getElementById("yearSelect");
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const today = new Date();
        // let currentDate = new Date(); // This will be updated when user changes month/year

        // Populate year dropdown (current year to next 2 years)
        const currentYear = today.getFullYear();
        for (let y = currentYear; y <= currentYear + maxBookingOffset; y++) {
            const option = document.createElement("option");
            option.value = y;
            option.textContent = y;
            yearSelect.appendChild(option);
        }

        // Function to populate month dropdown based on selected year
        function populateMonths(selectedYear) {
            monthSelect.innerHTML = ""; // Clear previous options

            monthNames.forEach((name, index) => {
                const option = document.createElement("option");
                option.value = index;
                option.textContent = name;

                if (selectedYear == today.getFullYear() && index < today.getMonth()) {
                    option.disabled = true;
                    option.style.color = "#ccc";
                }

                monthSelect.appendChild(option);
            });
        }

        // Set default selections
        yearSelect.value = currentDate.getFullYear();
        populateMonths(currentDate.getFullYear());
        monthSelect.value = currentDate.getMonth();

        // Event listener for year change
        yearSelect.addEventListener("change", () => {
            const selectedYear = parseInt(yearSelect.value);
            currentDate.setFullYear(selectedYear);

            populateMonths(selectedYear);

            // If current selected month is now disabled, move to current month
            if (
                selectedYear === today.getFullYear() &&
                parseInt(monthSelect.value) < today.getMonth()
            ) {
                monthSelect.value = today.getMonth();
                currentDate.setMonth(today.getMonth());
            }

            renderCalendar(currentDate, calendarData, currentPackageId);
        });

        // Event listener for month change
        monthSelect.addEventListener("change", () => {
            const selectedMonth = parseInt(monthSelect.value);
            currentDate.setMonth(selectedMonth);
            renderCalendar(currentDate, calendarData, currentPackageId);
        });
    </script>
    <!-- END Calendar script -->


    <!-- Time Slot script -->
    <script>
        let selectedTimes = [];

        let selectedSlotIds = [];
        function renderTimeSlot(date, currentLoopDate, packageId, base_price) {
            const slotHeader = document.getElementById("slotHeader");
            const slotBody = document.getElementById("slotBody");
            const selectedTimeInput = document.getElementById("selected_time");

            const flowHidePackage = {{ $organizer->what_flow }} != 4;
            if(flowHidePackage){
                if (!selectedSlotIds.length) {

                    timeSlotSection.classList.remove("d-none");
                    slotHeader.innerHTML = "<th></th>";
                    slotBody.innerHTML = `
                        <tr>
                            <td colspan="100%" class="text-center py-4 text-muted">
                                Sila pilih tema dahulu.
                            </td>
                        </tr>
                    `;

                    return; // ❗ STOP here
                }
            }
            // Array to store all selected time objects
            selectedTimes = [];
            // Always show the table section and clear previous content
            timeSlotSection.classList.remove("d-none");
            slotHeader.innerHTML = "<th></th>";
            slotBody.innerHTML = `
            <tr>
                <td colspan="100%" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 text-muted small">Loading available time slots...</div>
                </td>
            </tr>
        `;

            fetch(`/api/packages/${packageId}/available-slots?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    // Clear old content again after loading
                    slotHeader.innerHTML = "<th></th>";
                    slotBody.innerHTML = "";

                    // Handle no slots
                    if (!data.slots || data.slots.length === 0) {
                        slotBody.innerHTML = `
                        <tr>
                            <td colspan="100%" class="text-muted py-3">
                                No available time slots for this date.
                            </td>
                        </tr>`;
                        return;
                    }

                    // Add time header
                    const timeLabels = data.slots[0].times;
                    timeLabels.forEach(time => {
                        const [hourPart, ampm] = time.split(" "); // e.g. "9:00 AM" -> ["9:00", "AM"]
                        const th = document.createElement("th");
                        th.innerHTML = `<div style="font-size: 15px;" class="text-muted">${hourPart}</div><div style="font-size: 15px;" class="text-muted">${ampm || ""}</div>`;
                        th.style.lineHeight = "1.1";
                        th.style.padding = "10px";
                        slotHeader.appendChild(th);
                    });


                    // Create table body rows
                    // Build each slot row
                    data.slots
                        .filter(slot => {
                            if (!selectedSlotIds.length) return true;
                            return selectedSlotIds.includes(slot.id);
                        })

                        .forEach(slot => {
                        const tr = document.createElement("tr");

                        // Court / Slot Name
                        const courtCell = document.createElement("td");
                        courtCell.textContent = slot.court;
                        
                        courtCell.style.fontWeight = "600";
                        tr.appendChild(courtCell);

                        // Each time column
                        slot.times.forEach(time => {
                            const td = document.createElement("td");
                            td.classList.add("p-2", "text-center");

                            const checkbox = document.createElement("input");
                            checkbox.type = "checkbox";
                            checkbox.classList.add("form-check-input");
                            checkbox.value = `${slot.id}|${time}`;
                            
                            // Convert 12h time (e.g., "10:00 AM") to 24h format
                            const [hourMin, ampm] = time.split(" "); // "10:00 AM" -> ["10:00", "AM"]
                            let [hours, minutes] = hourMin.split(":").map(Number);
                            if (ampm === "PM" && hours !== 12) hours += 12;
                            if (ampm === "AM" && hours === 12) hours = 0;
                            const currentTime = `${hours.toString().padStart(2,"0")}:${minutes.toString().padStart(2,"0")}:00`;

                            // Check if slot is booked
                            let isDisabled = slot.bookedTimes && slot.bookedTimes.includes(time);

                            // Check if slot falls in vendor off time
                            if (data.vendorOffTimes) {
                                data.vendorOffTimes.forEach(off => {
                                    if (currentTime >= off.start_time && currentTime < off.end_time) {
                                        isDisabled = true;
                                    }
                                });
                            }

                            if (isDisabled) {
                                checkbox.disabled = true;
                                td.classList.add("bg-success", "bg-opacity-50"); // highlight disabled
                                td.title = "Unavailable (Off Time)";
                            }

                            // Mark booked slots (disable + green highlight)
                            if (slot.bookedTimes && slot.bookedTimes.includes(time)) {
                                checkbox.disabled = true;
                                td.classList.add("bg-success", "bg-opacity-50");
                            }

                            // On user selection
                            checkbox.addEventListener("change", () => {
                                const slotObj = { date, id: slot.id, time, slot_name: slot.court };

                                if (checkbox.checked) {
                                    // Kalau is_theme_first = true, disable semua checkbox lain
                                    // if (slot.is_theme_first) {
                                    //     // tandakan selected checkbox
                                    //     td.classList.add("bg-primary", "text-white");
                                    //     selectedTimes = [slotObj];

                                    //     // disable semua other checkboxes
                                    //     document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                    //         if (cb !== checkbox) cb.disabled = true;
                                    //     });
                                    // } else {
                                        td.classList.add("bg-primary", "text-white");
                                        selectedTimes.push(slotObj);
                                    // }
                                } else {
                                    // uncheck logic
                                    td.classList.remove("bg-primary", "text-white");
                                    selectedTimes = selectedTimes.filter(
                                        s => !(s.date === slotObj.date && s.id === slotObj.id && s.time === slotObj.time)
                                    );

                                    // Kalau is_theme_first = true, enable semua checkboxes semula
                                    // if (slot.is_theme_first) {
                                    //     document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                    //         cb.disabled = false;

                                    //         // disabled asal untuk booked / off time
                                    //         const [slotId, slotTime] = cb.value.split("|");
                                    //         const originalSlot = data.slots.find(s => s.id == slotId);
                                    //         const originalDisabled = 
                                    //             (originalSlot.bookedTimes && originalSlot.bookedTimes.includes(slotTime)) ||
                                    //             (data.vendorOffTimes && data.vendorOffTimes.some(off => slotTime >= off.start_time && slotTime < off.end_time));

                                    //         if (originalDisabled) cb.disabled = true;
                                    //     });
                                    // }
                                }
                                selectedTimeInput.value = JSON.stringify(selectedTimes);
                                checkTimeSlotSelected(currentLoopDate);
                                updateSelectedSlotInput();
                            });

                            td.appendChild(checkbox);
                            tr.appendChild(td);
                        });

                        slotBody.appendChild(tr);
                    });
                })
                .catch(err => {
                    console.error("Error loading time slots:", err);
                    slotBody.innerHTML = `
                    <tr>
                        <td colspan="100%" class="text-danger py-3">
                            Failed to load time slots. Please try again.
                        </td>
                    </tr>`;
                });
        }

        function timeTo24h(timeStr) {
            const [time, modifier] = timeStr.split(" ");
            let [hours, minutes] = time.split(":").map(Number);
            if (modifier === "PM" && hours !== 12) hours += 12;
            if (modifier === "AM" && hours === 12) hours = 0;
            return `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:00`;
        }

    </script>

    <script>

        // document.addEventListener('DOMContentLoaded', function () {
        //     const modal = document.getElementById('galleryModal');
        //     modal.addEventListener('show.bs.modal', function (event) {
        //         const trigger = event.relatedTarget;
        //         const index = trigger.getAttribute('data-img-index');

        //         const carousel = bootstrap.Carousel.getInstance(document.getElementById('galleryCarousel')) ||
        //             new bootstrap.Carousel(document.getElementById('galleryCarousel'));

        //         carousel.to(parseInt(index));
        //     });
        // });
    </script>
    <script>
        // function toggleDesc(id, device) {
        //     let preview = document.getElementById(`desc-preview-${device}-${id}`);
        //     let full = document.getElementById(`desc-full-${device}-${id}`);

        //     if (preview.style.display === "none") {
        //         preview.style.display = "inline";
        //         full.style.display = "none";
        //     } else {
        //         preview.style.display = "none";
        //         full.style.display = "inline";
        //     }
        // }
    </script>

    <script>
        // document.addEventListener("DOMContentLoaded", function() {

        //     const organizerId = @json($organizer->id);
        //     const container = document.getElementById("carouselInner");

        //     fetch(`/organizer/${organizerId}/banners`)
        //     .then(res => res.json())
        //     .then(data => {

        //         if (!data.banners.length) {
        //             container.innerHTML = `
        //                 <div class="carousel-item active">
        //                     <div class="text-center p-5">No banner available</div>
        //                 </div>`;
        //             return;
        //         }

        //         let html = "";

        //         data.banners.forEach((image, index) => {
        //             html += `
        //             <div class="carousel-item ${index === 0 ? 'active' : ''}">
        //                 <img 
        //                     src="/images/organizers/${data.id}/${image}" 
        //                     class="d-block w-100 carousel-image"
        //                     alt="banner">
        //             </div>`;
        //         });

        //         container.innerHTML = html;

        //         // reinitialize bootstrap carousel
        //         const carousel = new bootstrap.Carousel(document.getElementById('organizerCarousel'));

        //     })
        //     .catch(err => {
        //         container.innerHTML = `
        //         <div class="carousel-item active">
        //             <div class="text-danger text-center p-5">Failed to load images</div>
        //         </div>`;
        //     });

        // });
    </script>
    {{-- Package & slot images are now server-rendered, no JS fetch needed --}}
@endpush