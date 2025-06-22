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
            padding: 2.5rem;
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
            <div class="col-lg-7 col-md-7">
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
                </div>

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
                                    <li class="mb-3">
                                        <i class="fa-solid fa-bars-staggered"></i>
                                        <span class="text-muted d-block small ml-1">{{ $item['description'] }}</span>
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
                                    <li>
                                        <i class="fa-solid fa-bars-staggered"></i>
                                        <span class="text-muted d-block small ml-1">{{ $addon['description'] }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                @endif

                <!-- About Package -->
                <section>
                    <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Description</h6>
                    <hr class="mb-4" />
                    <p class="about-property">
                        {{ $package->description }}
                    </p>
                </section>


            </div>
            <div class="col-lg-5 col-md-5">
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
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a58ec3c2-1752-4c8a-9f9c-9ef970723935.png"
                                alt=" logo" class="agent-avatar" />
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
                        <table class="calendar table table-bordered">
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
                        <form action="{{ route('business.select_package') }}" method="post">
                            @csrf

                            <input type="hidden" name="package_id" value="{{ $package->id }}">

                            <input type="hidden" name="selected_date" id="selected_date">

                            <button type="submit" class="btn btn-primary mt-5 w-100" id="bookNowBtn" disabled>Book
                                Now</button>
                        </form>
                </section>
            </div>
        </aside>

        <!-- Modal Gallery -->
        <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-dark">
                    <div class="modal-body p-0">
                        <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($package->images as $index => $gallery)
                                    @php $imgUrl = asset('images/organizers/' . $organizer->id . '/packages/' . $package->id . '/' . $gallery->url); @endphp
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $imgUrl }}" class="d-block w-100"
                                            alt="{{ $gallery->alt_text ?? 'Gallery Image' }}">

                                        {{-- Caption Text Inside Modal --}}
                                        @if($gallery->alt_text)
                                            <div class="carousel-caption d-block d-md-block bg-dark bg-opacity-75 p-3 rounded">
                                                <h5 class="fs-6 fs-md-5">{{ $gallery->alt_text ?? 'Gallery Photo' }}</h5>
                                                @if($gallery->created_at)
                                                    <p class="mb-0">{{ $gallery->created_at->format('F Y') }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
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


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('imageGalleryModal');
            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                const index = trigger.getAttribute('data-img-index');

                const carousel = bootstrap.Carousel.getInstance(document.getElementById('galleryCarousel')) ||
                    new bootstrap.Carousel(document.getElementById('galleryCarousel'));

                carousel.to(parseInt(index));
            });
        });
    </script>
    <script>
        function toggleDesc(id) {
            const preview = document.getElementById(`desc-preview-${id}`);
            const full = document.getElementById(`desc-full-${id}`);

            if (preview.style.display === "none") {
                preview.style.display = "inline";
                full.style.display = "none";
            } else {
                preview.style.display = "none";
                full.style.display = "inline";
            }
        }
    </script>
    <script>
        function copyCurrentUrl() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                const alertEl = document.getElementById('copyAlert');
                alertEl.classList.remove('d-none');
                setTimeout(() => {
                    hideCopyAlert();
                }, 3000);
            }).catch(err => {
                console.error("Failed to copy URL:", err);
            });
        }

        function hideCopyAlert() {
            const alertEl = document.getElementById('copyAlert');
            alertEl.classList.add('d-none');
        }
    </script>

    <!-- calendar script -->
    <script>
        const vendorTimeSlots = @json($timeSlots);
        const vendorOffDays = @json($offDays);
        const bookedVendorDates = @json($bookedDates);
        const limitReachedDays = @json($limitReachedDays);
        const bookedDatesFormatted = @json($bookedDatesFormatted);
        const weekRangeBlock = @json($weekRangeBlock);
        const calendarBody = document.getElementById("calendarBody");
        const currentMonthDisplay = document.getElementById("currentMonth");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");
        const maxBookingOffset = @json($package->max_booking_year_offset ?? 2);
        // console.log(vendorOffDays);
        let currentDate = new Date();

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-indexed (0 = January)
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const firstWeekday = (firstDay.getDay() + 6) % 7; // Make Monday = 0
            const totalDays = lastDay.getDate();
            const offDaysFormatted = vendorOffDays.map(off => off.off_date);
            const bookedDatesFormatted = bookedVendorDates.map(date => String(date));

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
                } else if (offDaysFormatted.includes(formattedDate)) {
                    td.classList.add("off-day");
                    td.title = "Unavailable (Off Day)";
                } else if (currentLoopDate.getTime() < today.getTime()) {
                    td.classList.add("past-day");
                    td.title = "Cannot book past date";
                } else if (bookedDatesFormatted.includes(formattedDate)) {
                    td.classList.add("booked-day");
                    td.title = "Unavailable (Fully Booked)";
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
                    console.log(formattedDate);

                    // Set the hidden input value
                    document.getElementById("selected_date").value = formattedDate;
                    checkDateSelected();
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
        const bookNowBtn = document.getElementById('bookNowBtn');

        function checkDateSelected() {
            if (selectedDateInput.value) {
                bookNowBtn.removeAttribute('disabled');
            } else {
                bookNowBtn.setAttribute('disabled', true);
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

    <!-- Calendar script -->
@endpush