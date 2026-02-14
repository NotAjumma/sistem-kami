@extends('home.homeLayout')
@push('styles')

    <style>
        /* Custom styles */

        a.breadcrumb-link {
            color: #374151;
            text-decoration: none;
        }

        a.breadcrumb-link:hover {
            text-decoration: underline;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '/';
            color: #6b7280;
        }

        .property-title {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.25rem;
        }

        .property-price {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--bs-primary);
            /* red-600 */
        }

        .negotiable-badge {
            font-size: 0.8rem;
            background-color: #e0e7ff;
            color: #3730a3;
            border-radius: 0.5rem;
            padding: 0.1rem 0.5rem;
            margin-left: 0.5rem;
        }

        .property-features .material-icons {
            vertical-align: middle;
            color: #6b7280;
            font-size: 1.25rem;
        }

        .property-feature-text {
            font-weight: 600;
            color: #374151;
            margin-left: 0.3rem;
        }

        /* Image grid custom */
        .property-images-main {
            border-radius: 0.75rem;
            overflow: hidden;
            max-height: 450px;
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .property-images-secondary img {
            border-radius: 0.75rem;
            object-fit: cover;
            width: 100%;
            height: 110px;
        }

        .show-media-btn {
            font-weight: 600;
            background-color: #f3f4f6;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            color: #374151;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .show-media-btn:hover {
            background-color: #e5e7eb;
        }

        /* Agent card */
        .agent-card {
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            background-color: white;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        }

        .agent-avatar {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .agent-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #dc2626;
            margin-bottom: 0.25rem;
        }

        .agent-company {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .btn-whatsapp {
            background-color: #25d366;
            border-color: #25d366;
            color: white;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-whatsapp:hover {
            background-color: #1da851;
            border-color: #1da851;
            color: white;
        }

        /* Package details lines */
        .property-details-list i.material-icons {
            font-size: 20px;
            color: #6b7280;
            vertical-align: middle;
            margin-right: 0.4rem;
        }

        .property-details-list li {
            color: #374151;
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        /* About section */
        .about-property {
            color: #4b5563;
            line-height: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .property-images-secondary img {
                height: 90px;
            }

            .property-images-main {
                max-height: 300px;
            }

            .property-title {
                font-size: 1.5rem;
            }

            .property-price {
                font-size: 1.25rem;
            }
        }

        @media (min-width: 1440px) {
            .container-lg {
                max-width: 1280px !important;
            }
        }

        .ticket-box {
            background: rgb(255, 255, 255);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 0.5rem;
        }

        @media (min-width: 992px) {

            /* lg breakpoint for Bootstrap */
            .ticket-box.sticky-on-lg {
                position: sticky;
                top: 100px;
                /* adjust as needed, space from top */
            }
        }

        h1,
        h2,
        h3,
        h6 {
            font-family: 'Playfair Display', serif !important;
        }
    </style>

    <style>
        .calendar {
            border: 1px solid #ccc;
        }

        .calendar td,
        .calendar th {
            height: 60px;
            text-align: center !important;
            vertical-align: middle;
        }

        table.calendar thead tr th {
            text-align: center !important;
        }

        .today {
            background-color: rgba(var(--bs-secondary-rgb)) !important;
            color: #fff !important;
        }

        .selected {
            background-color: var(--primary) !important;
            color: #fff !important;
        }

        .off-day {
            background-color: rgb(205, 205, 207) !important;
            color: #6c757d !important;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .booked-day {
            background-color: rgba(var(--bs-success-rgb)) !important;
            color: #fff !important;
            cursor: not-allowed;
        }

        .past-day {
            background-color: rgb(205, 205, 207) !important;
            color: #6c757d !important;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .limit-reached {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            cursor: not-allowed !important;
        }

        .calendar .highlight {
            background-color: #f0f0f0;
        }

        .time-slot {
            border: 1px solid #ddd;
            padding: 8px;
            margin-bottom: 5px;
            cursor: pointer;
        }

        .time-slot.selected {
            background-color: #000;
            color: #fff;
        }

        .calendar-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        button:disabled {
            background-color: rgb(205, 205, 207) !important;
            color: #6c757d !important;
            border-color: rgb(205, 205, 207) !important;
            cursor: not-allowed !important;
        }

        .legend-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 4px;
        }
        .about-property ul {
            margin-left: 20px;
            list-style: disc;
        }
        .about-property p {
            margin-bottom: 10px;
        }

         ol {
            display: block !important;
            list-style-type: lower-roman !important;
            margin: 1em 0 !important;
            padding-left: 40px !important;
        }

        li {
            list-style-type: inherit !important;
        }

    </style>
@endpush

@section('content')
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <!-- <nav aria-label="breadcrumb" class="py-2">
                                                                                                                                                                                                                                                                                    <div class="container">
                                                                                                                                                                                                                                                                                        <ol class="breadcrumb mb-0 px-0">
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Home</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Condominium</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Kuala Lumpur</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">KL City Centre</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">KLCC</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Desa Kudalari</a></li>
                                                                                                                                                                                                                                                                                            <li class="breadcrumb-item active" aria-current="page">
                                                                                                                                                                                                                                                                                                <a href="#" class="breadcrumb-link text-decoration-underline text-muted">For Sale</a>
                                                                                                                                                                                                                                                                                            </li>
                                                                                                                                                                                                                                                                                        </ol>
                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                </nav> -->

        <!-- Images Section -->
        @php
            $mainImage = $package->images->firstWhere('is_cover', true) ?? $package->images->first();
            $secondaryImages = $package->images->filter(fn($img) => $img->id !== optional($mainImage)->id)->take(3);
            $count = $secondaryImages->count();
            $heightClass = match ($count) {
                1 => '440px',
                2 => '220px',
                default => '440px', // You’ll define this custom class
            };
        @endphp

        <section class="col-12 col-lg-12">
            <div class="row g-2">
                {{-- Main image - Always visible --}}
                <div class="col-12 col-md-7">
                    @if($mainImage)
                        <img src="{{ asset('images/organizers/' . $organizer->id . '/packages/' . $package->id . '/' . $mainImage->url) }}"
                            alt="{{ $mainImage->alt_text ?? 'Main image' }}"
                            class="property-images-main w-100 h-100 object-fit-cover"
                            style="aspect-ratio: 4/3; cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal" data-img-index="0" />
                    @endif
                </div>

                {{-- Secondary images - Only visible on md and up --}}
                <div class="col-12 col-md-5 d-none d-md-flex flex-column gap-2 h-100">
                    @foreach($secondaryImages->take(2) as $index => $image)
                        <div class="flex-fill overflow-hidden rounded">
                            <img src="{{ asset('images/organizers/' . $organizer->id . '/packages/' . $package->id . '/' . $image->url) }}"
                                alt="{{ $image->alt_text ?? 'Gallery image' }}" class="w-100 object-fit-cover"
                                data-bs-toggle="modal" data-bs-target="#imageGalleryModal" data-img-index="{{ $index }}"
                                style="height:{{ $heightClass }}; cursor: pointer;" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Sidebar Section -->
        <aside class="row">
            <div class="col-lg-7 col-md-12">
                <!-- Package Title & Price -->
                @php
                    $basePrice = (float) $package->base_price;
                    $discount = collect($package->discounts)->first(function ($d) {
                        return $d['is_active'] &&
                            \Carbon\Carbon::parse($d['starts_at'])->isPast() &&
                            \Carbon\Carbon::parse($d['ends_at'])->isFuture();
                    });

                    $finalPrice = $basePrice;

                    if ($discount) {
                        if ($discount['type'] === 'fixed') {
                            $finalPrice -= (float) $discount['amount'];
                        } elseif ($discount['type'] === 'percentage') {
                            $finalPrice -= ($basePrice * ((float) $discount['amount'] / 100));
                        }
                    }
                @endphp

                <div class="mt-4">
                    <h6>
                        <span class="badge"
                            style="background-color: #3736af; margin-right: 8px; padding: 0.5em 1em; letter-spacing: 0.2em;">
                            {{ $package->category->name }}
                        </span>
                    </h6>
                    <h1 class="property-title">{{ $package->name }}</h1>
                    @if($package->is_manual != 2)
                    <p>
                        <span class="property-price">RM {{ number_format($finalPrice, 2) }}</span>
                        @if($discount)
                            <span class="text-muted text-decoration-line-through ms-2">RM
                                {{ number_format($basePrice, 2) }}</span>
                            <span class="negotiable-badge">Discount Applied</span>
                        @else
                            <!-- <span class="negotiable-badge">Negotiable</span> -->
                        @endif
                        <hr class="mb-4" />
                    </p>
                    @endif
                </div>

                 <!-- About Package -->
                @if($package->description)
                <section>
                    <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Description</h6>
                    <hr class="mb-4" />
                    <div class="about-property">
                       {!! $package->description !!}
                    </div>
                </section>
                @endif

                <!-- Package Details -->
                @if(!empty($package->items) && count($package->items) > 0)
                    <section class="mb-4">
                        <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Package Item List</h6>
                        <hr class="mb-4" />
                        <ul class="list-unstyled property-details-list">
                            @foreach ($package->items ?? [] as $item)
                                <li class="mb-0">
                                    <i class="fa-solid fa-check text-success me-2"></i>
                                    <strong class="mr-1">{{ $item['title'] }}</strong>
                                </li>
                                @if(!empty($item['description']))
                                    <li class="d-flex align-items-start gap-2 mb-4">
                                        <i class="fa-solid fa-bars-staggered mt-1"></i>
                                        <span class="text-muted small">{{ $item['description'] }}</span>
                                    </li>
                                @endif

                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(!empty($package->addons) && count($package->addons) > 0)
                    <section class="mb-4">
                        <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Optional Add-ons</h6>
                        <hr class="mb-4" />
                        <ul class="list-unstyled property-details-list">
                            @foreach ($package->addons as $addon)
                                <li>
                                    <i class="fa-solid fa-plus text-primary me-2"></i>
                                    <strong class="mr-1">{{ $addon['name'] }}</strong>
                                    <span class="badge bg-light text-dark mt-1 ml-1">RM
                                        {{ number_format($addon['price'], 2) }}</span>
                                </li>
                                @if(!empty($addon['description']))
                                    <li class="d-flex align-items-start gap-2 mb-4">
                                        <i class="fa-solid fa-bars-staggered mt-1"></i>
                                        <span class="text-muted small">{{ $addon['description'] }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                @endif

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
                @php
                    $line1 = $organizer->address_line1;
                    $line2 = $organizer->address_line2;
                    $line3 = trim(collect([
                        $organizer->postal_code,
                        $organizer->city,
                        $organizer->state
                    ])->filter()->implode(', '));
                @endphp

                @if($line1 || $line2 || $line3)
                    <div class="event-organizer text-dark fw-bold">
                        <div class="mb-1">🏠</div>

                        @if($line1)
                            <div>{{ $line1 }}</div>
                        @endif

                        @if($line2)
                            <div>{{ $line2 }}</div>
                        @endif

                        @if($line3)
                            <div>{{ $line3 }}</div>
                        @endif
                    </div>
                @endif




            @endif
            </div>
            <div class="col-lg-5 col-md-12">
                <!-- Shortlist and Share Buttons -->
                <!-- <div class="d-flex justify-content-between gap-3 flex-wrap position-relative">
                                                                <button type="button"
                                                                    class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2 mt-3"
                                                                    onclick="copyCurrentUrl()" aria-label="Share property">
                                                                    <i class="fa-solid fa-share"></i> Share
                                                                </button>

                                                                {{-- Bootstrap alert (hidden by default) --}}
                                                                {{-- Top-right fixed alert --}}
                                                                <div id="copyAlert"
                                                                    class="alert alert-primary alert-dismissible fade show position-fixed top-0 end-0 mt-3 me-3 shadow d-none"
                                                                    role="alert" style="z-index: 1060; min-width: 200px;">
                                                                    Link copied!
                                                                    <button type="button" class="btn-close" onclick="hideCopyAlert()" aria-label="Close"></button>
                                                                </div>
                                                            </div> -->

                <!-- Organizer Contact Card -->
                <section class="col-12 mt-5 mb-5 ticket-box ">
                    <div class="">
                        <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Service Provider</h6>
                        <hr class="mb-4" />
                        <div class="d-flex align-items-center mb-2 gap-3">
                            <img src="{{ $package->organizer->logo_url }}" alt="{{ $package->organizer->name }} logo"
                                class="agent-avatar" />
                            <div>
                                <p class="fw-semibold mb-1" style="font-size: 1rem; line-height: 1.2;">
                                    {{ $organizer->name }}
                                </p>
                                <!-- <p class="agent-company">{{ $organizer->category }}</small></p> -->

                                <p class="mb-0" style="font-size: 0.875rem; color: #3736af; ">
                                    Email: <a href="mailto:{{ $organizer->email }}" class="link-red"
                                        style="font-weight: 600;">{{ $organizer->email }}</a>
                                </p>
                                <p class="mb-1" style="font-size: 0.875rem; color: #3736af;">
                                    Phone: <span style="font-weight: 600;">{{ $organizer->phone }}</span>
                                </p>
                                <a href="{{ route('business.profile', ['slug' => $organizer->slug]) }}"
                                    style="font-size: 0.875rem; color: #3736af; font-weight: 600;">
                                    View Profile
                                </a>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select a Date</h6>
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
                        @if($timeSlots && $timeSlots->isNotEmpty())
                            <div id="timeSlots" class="d-none">
                                <hr class="my-4" />
                                <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select Time Slots</h6>
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
                        @endif

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

                </section>
            </div>
        </aside>

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

                            @if($package->is_manual == 2)
                            <div class="mb-3">
                                <label for="customerWhatsApp" class="form-label">Package Price (RM)</label>
                                <input type="text" id="slotPriceDisplay" value="0" class="form-control" readonly>
                            </div>
                            @else
                            <div class="mb-3">
                                <label for="customerWhatsApp" class="form-label">Package Price (RM)</label>
                                <input type="text" value="{{ $package->final_price }}" class="form-control" readonly>
                            </div>
                            @endif

                            <hr>

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

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="nextToTNC" class="btn btn-primary">Next</button>
                        </div>
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

    </div>
@endsection

@push('scripts')
    @if(session('error'))
        <script>
            toastr.info("{{ session('error') }}", "Error", {
                timeOut: 5000,
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                newestOnTop: true,
                preventDuplicates: true,
                tapToDismiss: false,
                showDuration: "300",
                hideDuration: "1000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            });
        </script>
    @endif


    <!-- calendar script -->
    <script>
        const vendorTimeSlots = @json($timeSlots);
        const vendorOffDays = @json($offDays);
        const bookedVendorDates = @json($bookedDates);
        const fullyBookedDates = @json($fullyBookedDates);
        const limitReachedDays = @json($limitReachedDays);
        const weekRangeBlock = @json($weekRangeBlock);
        const calendarBody = document.getElementById("calendarBody");
        const currentMonthDisplay = document.getElementById("currentMonth");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");
        const maxBookingOffset = @json($package->max_booking_year_offset ?? 2);
        // console.log(vendorOffDays);
        let currentDate = new Date();
        const timeSlotSection = document.getElementById("timeSlots");

        function renderCalendar(date) {
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
                        td.classList.add("off-day");
                        td.title = "Unavailable (Off Day)";
                    }
                }  else if (currentLoopDate.getTime() < today.getTime()) {
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
                    document.querySelectorAll("#calendarBody td").forEach(cell => cell.classList.remove("selected"));
                    td.classList.add("selected");

                    // Log selected date in yyyy-mm-dd format
                    const selectedDate = new Date(year, month, day);
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    const formattedDate = `${yyyy}-${mm}-${dd}`;

                    // Log it (for debug)
                    // console.log(formattedDate);

                    // Set the hidden input value
                    document.getElementById("selected_date").value = formattedDate;
                    applySpecialDateAddons();
                    if (vendorTimeSlots.length > 0) {
                        const shouldHide =
                            currentLoopDate.getTime() < today.getTime() ||
                            offDaysFormatted.includes(formattedDate) ||
                            limitReachedDays.includes(formattedDate);

                        if (shouldHide) {
                            timeSlotSection.classList.add("d-none");
                        } else {
                            renderTimeSlot(formattedDate, currentLoopDate);
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
            currentDate = new Date(); // Reset to today
            renderCalendar(currentDate);
            document.getElementById("selected_date").value = "";
            document.getElementById("bookNowBtn").setAttribute("disabled", true);
        });

        prevMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextMonthBtn.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        // Initial render
        renderCalendar(currentDate);

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

            renderCalendar(currentDate);
        });

        // Event listener for month change
        monthSelect.addEventListener("change", () => {
            const selectedMonth = parseInt(monthSelect.value);
            currentDate.setMonth(selectedMonth);
            renderCalendar(currentDate);
        });
    </script>
    <!-- END Calendar script -->

    <!-- Time Slot script -->
    <script>
        function renderTimeSlot(date, currentLoopDate) {
            console.log("Render time slot for:", date);

            const slotHeader = document.getElementById("slotHeader");
            const slotBody = document.getElementById("slotBody");

            // Array to store all selected time objects
            let selectedTimes = [];

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

            fetch(`/api/packages/{{ $package->id }}/available-slots?date=${date}`)
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
                    data.slots.forEach(slot => {
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
                                const slotObj = { 
                                    date, 
                                    id: slot.court, 
                                    time,
                                    price: slot.slot_price ?? 0
                                };


                                if (checkbox.checked) {
                                    if (slot.is_theme_first) {
                                        td.classList.add("bg-primary", "text-white");
                                        selectedTimes = [slotObj];

                                        // disable semua other checkboxes
                                        document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                            if (cb !== checkbox) cb.disabled = true;
                                        });
                                    } else {
                                        td.classList.add("bg-primary", "text-white");
                                        selectedTimes.push(slotObj);
                                    }
                                } else {
                                    // uncheck logic
                                    td.classList.remove("bg-primary", "text-white");
                                    selectedTimes = selectedTimes.filter(
                                        s => !(s.date === slotObj.date && s.id === slotObj.id && s.time === slotObj.time)
                                    );

                                    // Kalau is_theme_first = true, enable semua checkboxes semula
                                    if (slot.is_theme_first) {
                                        document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                            cb.disabled = false;

                                            // disabled asal untuk booked / off time
                                            const [slotId, slotTime] = cb.value.split("|");
                                            const originalSlot = data.slots.find(s => s.id == slotId);
                                            const originalDisabled = 
                                                (originalSlot.bookedTimes && originalSlot.bookedTimes.includes(slotTime)) ||
                                                (data.vendorOffTimes && data.vendorOffTimes.some(off => slotTime >= off.start_time && slotTime < off.end_time));

                                            if (originalDisabled) cb.disabled = true;
                                        });
                                    }
                                }

                                selectedTimeInput.value = JSON.stringify(selectedTimes);
                                checkTimeSlotSelected(currentLoopDate);
                                updateSlotPriceDisplay(selectedTimes);
                                // console.log("Selected times:", selectedTimes);
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

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappNowBtn = document.getElementById("whatsappNowBtn");
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
            nextToTNC.addEventListener('click', () => {
                const name = document.getElementById('customerName').value.trim();
                const whatsapp = document.getElementById('customerWhatsApp').value.trim();
                if (!name || !whatsapp) return alert("Please fill Name and WhatsApp.");

                step1.classList.add('d-none');
                step2.classList.remove('d-none');
            });

            // Back button
            backToStep1.addEventListener('click', () => {
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
            });

            // Enable confirm button if T&C accepted
            acceptTNC.addEventListener('change', e => {
                confirmBooking.disabled = !e.target.checked;
            });

            const packagePrice = {{ $package->final_price }};
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
                bookingModal.hide();

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

                const name = document.getElementById('customerName').value.trim();
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
                `Payment : Full Payment
                Total   : RM ${grandTotal.toFixed(2)}`;

                }


                // Format date
                const dateObj = new Date(date);
                const formattedDate = dateObj.toLocaleDateString('ms-MY', { day: 'numeric', month:'long', year:'numeric' });

                // Slots text
                let slotsText = selectedSlots.map(s => `${s.id} (${s.time})`).join(",");

                // WhatsApp message
                const packageTitle = "{{ $package->name }}";
                let message = `
                Hai ${studioAdminName}, \n\n saya ${name}. Saya ingin menempah \n\n Pakej : "${packageTitle}" \n Tarikh : ${formattedDate} \n Slot : ${slotsText}.`;
                if (addOns.length) {
                    message += `\n\nAdd-ons:\n${addOns.map(a => `- ${a}`).join("\n")}`;
                }

                message += `\n\n ${paymentText}`;

                // Optional: log click to backend
                fetch('/track/whatsapp', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({
                        organizer_id: "{{ $organizer->id }}",
                        package_id: "{{ $package->id }}",
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

        function applySpecialDateAddons() {

            const selectedDate = document.getElementById('selected_date').value;
            if (!selectedDate) return;

            const selected = new Date(selectedDate);

            document.querySelectorAll('[data-special-start]').forEach(el => {

                const start = el.dataset.specialStart;
                const end   = el.dataset.specialEnd;

                if (!start || !end) return; // normal addon

                const startDate = new Date(start);
                const endDate   = new Date(end);

                const wrapper = el.closest('.border');

                if (selected >= startDate && selected <= endDate) {

                    wrapper.classList.remove('d-none');

                    if (el.type === "checkbox") {
                        el.checked = true;
                        el.disabled = true;
                    }

                    if (el.type === "number") {
                        el.value = 1;
                        el.readOnly = true;
                    }

                } else {

                    wrapper.classList.add('d-none');

                    if (el.type === "checkbox") {
                        el.checked = false;
                        el.disabled = false;
                    }

                    if (el.type === "number") {
                        el.value = 0;
                        el.readOnly = false;
                    }
                }

            });

            calculateSummary();
        }

        function calculateSummary() {

            let addOnTotal           = 0;
            let packageTotal         = 0;
            let addOns               = [];
            const paymentOption      = document.getElementById('paymentOption');
            const depositAmountInput = document.getElementById('depositAmount');

            const isManual = {{ $package->is_manual }} == 2;

            if (isManual) {
                let selectedTimes = [];
                try {
                    selectedTimes = JSON.parse(selectedTimeInput.value || "[]");
                } catch(e) {}

                selectedTimes.forEach(slot => {
                    packageTotal += parseFloat(slot.price || 0);
                });

            } else {
                packageTotal = {{ $package->final_price }};
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

    </script>
@endpush