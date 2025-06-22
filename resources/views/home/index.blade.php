@extends('home.homeLayout')
@push('styles')
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap");

        body {
            font-family: "Poppins", sans-serif;
        }

        /* Modern ticket shape with perforated edges */
        .ticket {
            position: relative;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            border-radius: 1rem;
            color: white;
            width: 320px;
            height: 160px;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
            overflow: hidden;
            
        }

        .ticket::before,
        .ticket::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 20px;
            height: 40px;
            background: white;
            border-radius: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .ticket::before {
            left: -10px;
        }

        .ticket::after {
            right: -10px;
        }

        .ticket-perforation {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            background: repeating-linear-gradient(to bottom,
                    white,
                    white 6px,
                    transparent 6px,
                    transparent 12px);
            transform: translateX(-50%);
            z-index: 5;
        }

        .ticket-content {
            position: relative;
            height: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-header {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .ticket-subheader {
            font-weight: 600;
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        .ticket-stars {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .ticket-stars i {
            color: #facc15;
        }

        .ticket-footer {
            font-weight: 600;
            font-size: 1.125rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-align: center;
            background: rgba(255 255 255 / 0.15);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            user-select: none;
        }

        @media (max-width: 640px) {
            .ticket {
                width: 100%;
                height: 140px;
            }
        }

        .card {
            border-radius: 12px;
            border: 1.5px solid var(--primary);
            overflow: hidden;
            box-shadow: 0 6px 14px rgb(55 54 175 / 0.45);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgb(55 54 175 / 0.75);
        }

        .bookmark-icon {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 28px;
            height: 28px;
            background-color: rgba(211, 34, 34, 0.95);
            border-radius: 0.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s ease;
        }

        .bookmark-icon:hover {
            background-color: #a31313;
        }

        .bookmark-icon svg {
            fill: white;
            width: 16px;
            height: 16px;
        }

        .card-img-top {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            object-fit: contain;
            max-height: 250px;
            width: 100%;
        }

        .event-date-time {
            font-size: 0.85rem;
            background-color: white;
            border-radius: 0.45rem;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.08);
            padding: 6px 12px;
            display: flex;
            justify-content: space-around;
            gap: 8px;
            margin-top: -20px;
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
        }

        .event-date-time div {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
        }

        .event-date-time div svg {
            width: 16px;
            height: 16px;
            fill: #d32222;
        }

        .event-organizer {
            font-size: 0.75rem;
            font-weight: 600;
            color: #d32222;
            margin-bottom: 0.2rem;
            user-select: text;
        }

        .event-title {
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            user-select: text;
        }

        .event-desc {
            font-size: 0.9rem;
            color: #545454;
            overflow: hidden;
            text-overflow: ellipsis;
            user-select: text;
        }

        .event-footer {
            font-size: 0.85rem;
            color: #545454;
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: text;
        }

        .event-footer .location {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .event-footer .location svg {
            width: 16px;
            height: 16px;
            fill: #d32222;
        }

        .price {
            font-weight: 600;
            color: #d32222;
            user-select: text;
        }

        @media (max-width: 576px) {
            .card-img-top {
                max-height: 180px;
            }

            .event-desc {
                height: auto;
                white-space: normal;
            }

            .container-search {
                padding-top: 50px !important;
            }
        }

        /* Search */
        .search-section {
            background: #001f4d;
            padding: 1rem 1rem 0.7rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.35rem;
        }

        .btn-seek {
            background-color: var(--secondary);
            border: none;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.04em;
            user-select: none;
        }

        .btn-seek:hover {
            background-color: rgb(103, 173, 253);
        }

        .filters-scroll {
            /* overflow-x: auto; */
            white-space: nowrap;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            scrollbar-width: thin;
            scrollbar-color: #f50057 transparent;
        }

        .filters-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .filters-scroll::-webkit-scrollbar-thumb {
            background-color: #f50057;
            border-radius: 10px;
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            margin-right: 0.5rem;
            border: 1.5px solid var(--primary);
            background-color: var(--primary);
            color: #d1d9ff;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            user-select: none;
            padding: 0.25rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            white-space: nowrap;
            transition: background-color 0.25s, color 0.25s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            border-color: var(--secondary);
            background-color: var(--secondary);
            color: white;
        }

        .filter-btn .badge {
            background-color: var(--secondary);
            color: white;
            font-weight: 700;
            font-size: 0.7rem;
            margin-left: 0.3rem;
            padding: 0.13em 0.5em;
            border-radius: 12px;
        }

        .container-search {
            padding-top: 100px;
        }

        .dropdown-menu {
            overflow-y: visible !important;
            scrollbar-width: none;
            /* Firefox */
        }

        /* End search */

        /*** Courses Start ***/
        .courses {
            position: relative;
            overflow: hidden;
            background: rgba(0, 31, 77, 1);
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .courses .courses-item {
            position: relative;
            overflow: hidden;
        }

        .courses .courses-item p {
            color: var(--bs-black);
            transition: 0.5s;
        }

        .courses .courses-item:hover p {
            color: var(--bs-body);
        }

        .courses-item .courses-item-inner {
            position: relative;
            transform: skew(4deg);
            margin: 0 15px;
            background: #fff;
            transition: 0.5s;
        }

        .courses-item:hover .courses-item-inner {
            background: none;
        }

        .courses-item .courses-item-inner:hover {
            transform: skew(-4deg);
        }

        .courses-item .courses-item-inner .data-info .courses-trainer {
            color: var(--bs-white);
            transition: 0.5s;
        }

        .courses-item:hover .courses-item-inner .data-info .courses-trainer {
            color: var(--bs-body);
        }

        .courses-item .courses-item-inner .data-info .courses-date {
            font-size: 13px;
        }

        .courses-item .courses-item-inner .courses-icon-img {
            width: 80px;
            height: 80px;
            background: var(--bs-primary);
            transition: 0.5s;
        }

        .courses-item .courses-item-inner .courses-icon-img img {
            filter: grayscale(100%);
            transition: 0.5s;
        }

        .courses-item:hover .courses-item-inner .courses-icon-img img {
            filter: grayscale(0%);
        }

        .courses-item-inner:hover a {
            color: var(--bs-white);
            transition: 0.5s;
        }

        .courses-item-inner:hover a:hover {
            color: var(--bs-primary);
        }

        .courses-item-inner::after {
            content: "";
            position: absolute;
            overflow: hidden;
            width: 100%;
            height: 4px;
            bottom: 0;
            left: 0;
            background: var(--secondary);
            transition: 0.5s;
            z-index: -1;
        }

        .courses-item-inner:hover:after {
            height: 100%;
        }

        /*** Courses End ***/

        .section-title {
            font-size: 2rem;
        }

        @media (min-width: 768px) {
            .section-title {
                font-size: 3rem;
            }
        }
    </style>
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
                <nav class="filters-scroll mt-3" aria-label="Event filters">
                    <!-- Event Category Dropdown -->
                    <div class="dropdown d-inline-block me-2">
                        <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Event Category
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($eventCategories as $category)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('search', array_merge(request()->query(), ['category' => $category->slug])) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Package Type Dropdown -->
                    {{-- <div class="dropdown d-inline-block me-2">
                        <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Package Type
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($packageTypes as $package)
                            <li>
                                <a class="dropdown-item" href="?package={{ $package->slug }}">
                                    {{ $package->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div> --}}

                    <!-- Price Range -->
                    <!-- Price Range Dropdown -->
                    <!-- <div class="dropdown d-inline-block">
                                            <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Price
                                            </button>
                                            <div class="dropdown-menu p-3"
                                                style="min-width: 250px; overflow: visible; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);">
                                                <div style="width: 260px;">
                                                    <label for="priceRangeMin" class="form-label">Min Price</label>
                                                    <input type="range" class="form-range w-100" id="priceRangeMin" min="0" max="1000" step="10"
                                                        value="100" oninput="document.getElementById('minPriceOutput').value = this.value">
                                                    <output id="minPriceOutput">100</output>

                                                    <label for="priceRangeMax" class="form-label mt-3">Max Price</label>
                                                    <input type="range" class="form-range w-100" id="priceRangeMax" min="0" max="1000" step="10"
                                                        value="800" oninput="document.getElementById('maxPriceOutput').value = this.value">
                                                    <output id="maxPriceOutput">800</output>

                                                    <div class="mt-3 text-end">
                                                        <button type="button" class="btn btn-sm btn-primary">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                </nav>
            </div>
        </section>

        <!-- Events Custom Section -->
        <section class="container pt-10 pb-16 md:pb-20 lg:pb-24">
            <div>
                <h2 class="text-center mb-4 fw-bold" style="user-select: text; font-size: 1.2rem;">Events</h2>
                <div class="row g-4 justify-content-center">
                    <!-- Card 1 -->
                    @foreach ($events as $event)

                        <div class="col-12 col-sm-6 col-xl-3 col-lg-4 col-md-6">
                            <a href="{{ route('event.slug', ['slug' => $event->slug]) }}">
                                <div class="card position-relative">
                                    <!-- <div class="bookmark-icon" title="Bookmark">
                                                                                                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                                                                                                                <path d="M6 4a2 2 0 0 0-2 2v16l7-5 7 5V6a2 2 0 0 0-2-2H6z" />
                                                                                                                                                            </svg>
                                                                                                                                                        </div> -->
                                    @if (!empty($event->image_cover))
                                        <img src="{{ asset('images/events/' . $event->id . '/' . $event->image_cover) }}"
                                            class="d-block w-100" alt="Event Image">
                                    @endif
                                    <div class="event-date-time mx-3">
                                        <div class="text-primary">
                                            <i
                                                class="far fa-calendar-alt me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->start_date)->format('d M') }}
                                        </div>
                                        <!-- <div>
                                                                                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                                                                                                                            <path
                                                                                                                                                                                d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm0 18a7.985 7.985 0 0 1-6.33-13.054l9.402 9.401A7.957 7.957 0 0 1 12 20zm6.33-2.946-9.402-9.4A7.963 7.963 0 0 1 18.33 17.054z" />
                                                                                                                                                                        </svg> 1d 11h 59m
                                                                                                                                                                    </div> -->
                                        <div class="text-primary">
                                            <i
                                                class="far fa-clock me-2 text-primary"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                        </div>
                                    </div>
                                    <div class="card-body px-3 pb-3 pt-0">
                                        <div class="event-organizer text-primary" title="Organizer">By
                                            {{ $event->organizer->name }}
                                        </div>
                                        <div class="event-title mb-2" title="{{ $event->title }}">
                                            {{ Str::limit(($event->title), 25) }}
                                        </div>
                                        <div class="event-desc">
                                            {{ Str::limit(strip_tags($event->description), 80) }}
                                        </div>
                                        <hr class="dashed-hr mb-2 mt-4" />
                                        @php
                                            $location = collect([
                                                $event->district,
                                                $event->state,
                                                $event->country
                                            ])->filter()->implode(', ');

                                            $lowestPrice = $event->tickets->min('price');
                                        @endphp

                                        @if ($location)
                                            <div class="event-footer">
                                                <div class="location" title="Location"><i
                                                        class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $location }}</div>
                                                @if (!is_null($lowestPrice))
                                                    <div class="event-price">MYR{{ number_format($lowestPrice, 2) }} <sup>*</sup></div>
                                                @else
                                                    <div class="event-price text-muted">Free*</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Business Custom Section -->
        <!-- TODO BUSINESS CARD -->

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
                                    event—concerts, festivals, workshops, and more.
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
                    <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="courses-item">
                            <div class="courses-item-inner p-4">
                                <div class="d-inline-block h4 mb-3">Centralized Platform for All</div>
                                <p class="mb-4">Whether you're planning an event or offering services, Sistem Kami brings
                                    everything under one roof—simple, scalable, and powerful.
                                </p>
                            </div>
                        </div>
                    </div>
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
                                    <i class="fa-solid fa-briefcase fa-2x text-success"></i>
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
                                    <i class="fa-solid fa-user-check fa-2x text-warning"></i>
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
@endsection

@push('scripts')
    <script>
    </script>
@endpush