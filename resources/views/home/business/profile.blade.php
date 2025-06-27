@extends('home.homeLayout')
@push('styles')

    <style>
        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgb(0 0 0 / 0.1);
            padding: 1.5rem;
            margin-top: -5rem;
            position: relative;
        }

        .profile-pic {
            width: 96px;
            height: 96px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            position: absolute;
            top: 30px;
            left: 1.5rem;
            background-color: #e9ecef;
            z-index: 100;
        }

        @media (min-width: 768px) {
            .profile-pic {
                width: 112px;
                height: 112px;
                top: 30px;
                left: 2rem;
            }
        }

        .profile-intro {
            margin-left: 130px;
        }

        .profile-intro h2 {
            font-weight: 700;
            margin-bottom: 0;
            font-size: 2rem;
        }

        .profile-intro .location {
            font-size: 0.9rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .profile-intro .location .material-icons {
            font-size: 1rem;
            color: #6c757d;
        }

        .service-card {
            border-radius: 12px;
            background: white;
            padding: 1rem;
            box-shadow: 0 1px 6px rgb(0 0 0 / 0.05);
            transition: box-shadow 0.3s ease;
            cursor: default;
        }

        .service-card:hover {
            box-shadow: 0 6px 18px rgb(0 0 0 / 0.12);
        }

        .service-card h5 {
            font-weight: 600;
        }

        .package-carousel {
            background: white;
            overflow: hidden;
            box-shadow: 0 2px 12px rgb(0 0 0 / 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .package-carousel:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .package-carousel img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }


        .portfolio-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgb(0 0 0 / 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .portfolio-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .portfolio-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        @media (min-width: 601px) and (max-width: 1440px) {
            .portfolio-image,
            .package-carousel img {
                height: 250px;
            }
        }

        @media (max-width: 600px) {

            .portfolio-image,
            .package-carousel img {
                height: 200px;
                /* 50% of desktop height on mobile */
            }

            .profile-intro {
                margin-left: 0px;
            }

            .profile-intro-name {
                margin-left: 130px;
            }
        }


        .portfolio-content {
            padding: 1rem 1rem 1.25rem;
        }

        .portfolio-title {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .portfolio-date {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .carousel-image {
            height: 350px;
            /* You can adjust this value (e.g. 300px or 400px) */
            object-fit: cover;
            object-position: center;
        }

        h1,
        h2,
        h3,
        h6 {
            font-family: 'Playfair Display', serif !important;
        }

        @media (max-width: 991.98px) { /* tablets and below */
            .carousel-caption {
                bottom: 200px !important;
            }

            .caption_alt_text{
                font-size: 1rem !important;
            }
        }

        @media (min-width: 992px) { /* tablets and below */
            .carousel-caption {
                bottom: 50px !important;
            }

            .caption_alt_text{
                font-size: 1rem !important;
            }
        }

        .carousel-caption {
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }

        #galleryModal .modal-content {
            height: 80vh;
            max-height: 80vh;
            background-color: #d3cccc85;
            border: none;
            box-shadow: none;
        }

        #galleryModal .modal-body {
            padding: 0;
            background-color: transparent;
        }

        .gallery-img {
            height: 80vh;
            object-fit: contain;
            background-color: transparent;
        }

        .carousel-control-prev{
            background-color: transparent;
        }

        .job-count {
            font-size: 0.9rem;
            color: #001f4d;
            font-weight: 600;
            border: 2px solid #1a3e85;
            padding: 0.2rem 0.9rem;
            border-radius: 50px;
            background: white;
            user-select: none;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        {{-- Carousel --}}
        <!-- Size banner 1500px x 350px -->
        <div id="organizerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($organizer->banner_path ?? [] as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('images/organizers/' . $organizer->id . '/' . $image) }}"
                            class="d-block w-100 carousel-image" alt="Slide {{ $index + 1 }}">
                    </div>
                @endforeach
            </div>
            @if(!empty($organizer->banner_path) && count($organizer->banner_path) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#organizerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#organizerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>

        <!-- Profile Section -->
        <section id="profile" class="position-relative mb-5 mt-5">
            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5d987ede-c9f0-4c63-a837-e2fef063a538.png"
                alt="Portrait of Emma Williams, wedding planner smiling" class="profile-pic shadow" loading="lazy" />
            <div class="profile-card">
                <div class="profile-intro">
                    <div class="profile-intro-name d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h2 class="mb-0">{{ $organizer->name }}</h2>
                            <p class="mb-1 fst-italic text-muted">{{ $organizer->category }}</p>
                        </div>

                        @if (!empty($organizer->social_links))
                            @php
                                $socials = is_array($organizer->social_links)
                                    ? $organizer->social_links
                                    : json_decode($organizer->social_links, true);
                            @endphp

                            @if (!empty($socials))
                                <div class="social-links d-flex gap-3 align-items-center">
                                    @foreach ($socials as $platform => $url)
                                        @if ($url)
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="">
                                                <i class="bi bi-{{ strtolower($platform) }}"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>

                    @php
                        $maxLength = 200;
                        $isLong = strlen($organizer->description) > $maxLength;
                        $excerpt = Str::limit($organizer->description, $maxLength);
                    @endphp

                    <p class="profile-intro-desc mb-2">
                        <span id="desc-preview-{{ $organizer->id }}">
                            {{ $excerpt }}
                            @if($isLong)
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }})">Read more</a>
                            @endif
                        </span>

                        @if($isLong)
                            <span id="desc-full-{{ $organizer->id }}" style="display: none;">
                                {{ $organizer->description }}
                                <a href="javascript:void(0);" onclick="toggleDesc({{ $organizer->id }})">Show less</a>
                            </span>
                        @endif
                    </p>

                    @php
                        $addressParts = array_filter([
                            $organizer->address_line1,
                            $organizer->address_line2,
                            $organizer->postal_code,
                            $organizer->city,
                            $organizer->state,
                            $organizer->country
                        ]);
                    @endphp

                    @if (!empty($addressParts))
                        <div class="location d-flex align-items-start text-muted">
                            <span>{{ implode(', ', $addressParts) }}</span>
                        </div>
                    @endif
                </div>

            </div>
        </section>

        <!-- Services Offered Section -->
        <!-- <section id="services" class="mb-5">
                                                                                                    <h3 class="mb-4 fw-bold">Services Offered</h3>
                                                                                                    <div class="row g-3">
                                                                                                        <div class="col-12 col-md-4">
                                                                                                            <div class="service-card h-100">
                                                                                                                <h5>Full Wedding Planning</h5>
                                                                                                                <p class="mb-0">Handle all of all ealing detatis in step</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-12 col-md-4">
                                                                                                            <div class="service-card h-100">
                                                                                                                <h5>Partial Planning</h5>
                                                                                                                <p class="mb-0">Assistance with selected wedding planning</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-12 col-md-4">
                                                                                                            <div class="service-card h-100">
                                                                                                                <h5>Day-of Coordination</h5>
                                                                                                                <p class="mb-0">Management of wedding day to ensure furns</p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </section> -->

        <!-- Filter Search -->
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-6">
                <input type="text" name="keyword" class="form-control"
                    placeholder="Search packages..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-4">
                <select name="package_category" class="default-select w-100">
                    <option value="">All Categories</option>
                    @foreach ($packageCategories as $category)
                        <option value="{{ $category->slug }}" {{ request('package_category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Packages Section -->
        <!-- Size package img 1024px x 1024px -->
        <section id="portfolio" class="mb-5">
            @if(request('package_category') || request('keyword'))
                <span class="job-count mb-4 mt-3" aria-live="polite" aria-atomic="true">
                    {{ trans_choice(':count result|:count results', $packages->count(), ['count' => $packages->count()]) }}
                </span>
                <div class="row g-4 mt-2">
            @else
                <h3 class="mb-4 fw-bold mt-3" style="font-size: 1.3rem;">Packages</h3>
                <div class="row g-4">
            @endif
                @foreach($packages as $package)
                    <div class="col-12 col-md-6 col-xl-6">
                        <article class="portfolio-item">
                            {{-- Package image --}}
                            @php
                                $validDiscount = $package->discounts[0] ?? null;
                            @endphp
                            <div id="packageCarousel_{{ $package->id }}" class="carousel slide mb-4 package-carousel"
                                data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($package->images ?? [] as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('images/organizers/' . $organizer->id . '/packages/' . $package->id . '/' . $image->url) }}"
                                                class="d-block w-100" alt="{{ $image->alt_text ?? 'Slide ' . ($index + 1) }}">
                                        </div>
                                    @endforeach
                                </div>
                                @if(!empty($package->images) && count($package->images) > 1)
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#packageCarousel_{{ $package->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#packageCarousel_{{ $package->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>

                            <a href="{{ route('business.package', ['organizerSlug' => $organizer->slug, 'packageSlug' => $package->slug]) }}">
                                <div class="portfolio-content">
                                    {{-- Package Name --}}
                                    <h4 class="portfolio-title">{{ $package->name }}</h4>
                                    <h6>
                                        <span class="badge mb-2"
                                            style="background-color: #3736af; margin-right: 8px; padding: 0.5em 1em; letter-spacing: 0.2em;">
                                            {{ $package->category->name }}
                                        </span>
                                    </h6>
                                    {{-- Discount Info --}}
                                    @if($validDiscount && $validDiscount->is_active)
                                        <p class="text-danger mb-1">
                                            @if($validDiscount->type === 'percentage')
                                                Save {{ $validDiscount->amount }}%!
                                            @else
                                                Save RM {{ number_format($validDiscount->amount, 2) }}!
                                            @endif
                                        </p>
                                    @endif

                                    {{-- Pricing --}}
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

                                    <p class="portfolio-price">
                                        @if($finalPrice < $originalPrice)
                                            <del class="text-muted">RM {{ number_format($originalPrice, 2) }}</del><br>
                                        @endif
                                        @if($validDiscount && $validDiscount->is_active)
                                            <strong>Now: RM {{ number_format($finalPrice, 2) }}</strong>
                                        @else
                                            <strong>RM {{ number_format($finalPrice, 2) }}</strong>

                                        @endif
                                    </p>

                                    {{-- Validity --}}
                                    @if($validDiscount && $validDiscount->is_active)
                                        <time datetime="{{ \Carbon\Carbon::parse($package->valid_from)->format('Y-m-d') }}"
                                            class="portfolio-date">
                                            Valid Until: {{ \Carbon\Carbon::parse($package->valid_until)->format('F d, Y') }}
                                        </time>
                                    @endif

                                    {{-- Quick Booking Button --}}
                                    <div class="mt-3">
                                        <a href="{{ route('business.package', ['organizerSlug' => $organizer->slug, 'packageSlug' => $package->slug]) }}"
                                            class="btn btn-primary w-100">
                                            Details
                                        </a>
                                    </div>

                                </div>
                            </a>

                        </article>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Gallery Section -->
        <!-- Size gallery img 1024px x 1024px -->
        @if(!request('package_category') && !request('keyword'))
        <section id="portfolio" class="mb-5">
            <h3 class="mb-4 fw-bold" style="font-size: 1.3rem;">Gallery</h3>
            <div class="row g-4">
                @foreach($organizer->gallery as $index => $gallery)
                    @php
                        $imgUrl = asset('images/organizers/' . $organizer->id . '/gallery/' . $gallery->file_name);
                    @endphp
                    <div class="col-6 col-md-4 col-xl-4">
                        <article class="portfolio-item">
                            <img src="{{ $imgUrl }}" alt="{{ $gallery->alt_text ?? 'Gallery Image' }}" class="portfolio-image"
                                loading="lazy" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#galleryModal"
                                data-img-index="{{ $index }}">
                            <div class="portfolio-content">
                                <h4 class="portfolio-title">{{ $gallery->alt_text ?? 'Gallery Photo' }}</h4>
                                <time datetime="{{ $gallery->created_at ? $gallery->created_at->format('Y-m') : '2025-01' }}"
                                    class="portfolio-date">
                                    {{ $gallery->created_at ? $gallery->created_at->format('F Y') : 'N/A' }}
                                </time>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Google Map --}}
        @if($organizer->latitude && $organizer->longitude)
            <div style="margin-top: 50px;">
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode($organizer->office_name) }}%20{{ $organizer->latitude }},{{ $organizer->longitude }}&output=embed"
                    width="100%" height="500" frameborder="0" style="border:0" allowfullscreen loading="lazy">
                </iframe>
            </div>
        @endif

    </div>
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content" style="max-height: 100vh; overflow: hidden;">
                <div class="modal-body p-0">
                    <button type="button" class="btn position-absolute top-0 end-0 z-3" style="height: 2.5rem; margin: 1rem !important;" data-bs-dismiss="modal"><i class="fa-solid fa-xmark fa-2xl"></i></button>

                    <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($organizer->gallery as $index => $gallery)
                                @php $imgUrl = asset('images/organizers/' . $organizer->id . '/gallery/' . $gallery->file_name); @endphp
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $imgUrl }}" class="d-block w-100 gallery-img"
                                        alt="{{ $gallery->alt_text ?? 'Gallery Image' }}">

                                    {{-- Caption Text Inside Modal --}}
                                    <div class="mx-auto w-100 d-none d-md-block">
                                        <div class="carousel-caption w-50 mx-auto text-center bg-dark bg-opacity-75 p-3 rounded">
                                            <h5 class="caption_alt_text">{{ $gallery->alt_text ?? 'Gallery Photo' }}</h5>
                                            @if($gallery->created_at)
                                                <p class="mb-0">{{ $gallery->created_at->format('F Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="prev">
                            <i class="fa-solid fa-square-caret-left text-primary d-none d-sm-inline" style="font-size: 5rem;"></i>
                            <i class="fa-solid fa-square-caret-left text-primary d-inline d-sm-none" style="font-size: 3rem;"></i>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="next">
                            <i class="fa-solid fa-square-caret-right text-primary d-none d-sm-inline" style="font-size: 5rem;"></i>
                            <i class="fa-solid fa-square-caret-right text-primary d-inline d-sm-none" style="font-size: 3rem;"></i>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('galleryModal');
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

@endpush