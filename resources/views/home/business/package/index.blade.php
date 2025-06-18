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
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 0.5rem;
            border: 2px solid #dc2626;
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
            <div class="col-lg-8 col-md-7">
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
                    </p>
                </div>

                <!-- Package Details -->
                <section class="mb-4">
                    <h5 class="fw-semibold mb-3">Package Item List</h5>
                    <ul class="list-unstyled property-details-list">
                        @foreach ($package->items ?? [] as $item)
                            <li>
                                <i class="fa-solid fa-check text-success me-2"></i>
                                <strong class="mr-1">{{ $item['title'] }}</strong> : <span
                                    class="text-muted d-block small ml-1">{{ $item['description'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                @if(!empty($package->addons) && count($package->addons) > 0)
                    <section class="mb-4">
                        <h5 class="fw-semibold mb-3">Optional Add-ons</h5>
                        <ul class="list-unstyled property-details-list">
                            @foreach ($package->addons as $addon)
                                <li>
                                    <i class="fa-solid fa-plus text-primary me-2"></i>
                                    <strong class="mr-1">{{ $addon['name'] }}</strong>:
                                    <span class="text-muted d-block small mx-1">{{ $addon['description'] }}</span>
                                    <span class="badge bg-light text-dark mt-1 ml-1">RM
                                        {{ number_format($addon['price'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <!-- About Package -->
                <section>
                    <h5 class="fw-semibold mb-3">Description</h5>
                    <p class="about-property">
                        {{ $package->description }}
                    </p>
                </section>
            </div>
            <div class="col-lg-4 col-md-5">
                <!-- Shortlist and Share Buttons -->
                <div class="d-flex justify-content-between gap-3 flex-wrap position-relative">
                    <button type="button"
                        class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2 mt-3"
                        onclick="copyCurrentUrl()" aria-label="Share property">
                        <i class="fa-solid fa-share"></i> Share
                    </button>

                    {{-- Bootstrap alert (hidden by default) --}}
                    {{-- Top-right fixed alert --}}
                    <div id="copyAlert"
                        class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 mt-3 me-3 shadow d-none"
                        role="alert" style="z-index: 1060; min-width: 200px;">
                        Link copied!
                        <button type="button" class="btn-close" onclick="hideCopyAlert()" aria-label="Close"></button>
                    </div>
                </div>

                <!-- Organizer Contact Card -->
                <div class="ticket-box sticky-on-lg">
                    <div class="d-flex gap-3">
                        <img class="agent-avatar"
                            src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a58ec3c2-1752-4c8a-9f9c-9ef970723935.png"
                            alt="Agent Jill Lee profile picture" />
                        <div>
                            <p class="agent-name">{{ $organizer->name }}</p>
                            <p class="agent-company">{{ $organizer->category }}</small></p>
                        </div>

                    </div>

                    @php
                        $maxLength = 150;
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
                    {{-- WhatsApp Button --}}
                    @php
                        $phone = preg_replace('/[^0-9]/', '', $organizer->phone); // remove non-digits
                        $whatsappUrl = 'https://wa.me/' . $phone;
                    @endphp
                    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-whatsapp w-100"
                        aria-label="Contact {{ $organizer->name }} on WhatsApp Web">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp Web
                    </a>
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

                    <details class="mt-3 text-start">
                        <summary class="text-decoration-underline" style="cursor:pointer;">Other ways to enquire
                        </summary>
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

                        <ul class="list-unstyled mt-2" style="font-size: 0.9rem; color:#6b7280;">
                            <li><strong>Email:</strong> {{ $organizer->email }}</li>
                            <li><strong>Phone:</strong> {{ $organizer->phone }}</li>
                            @if (!empty($addressParts))
                                <li><strong>Address:</strong> {{ implode(', ', $addressParts) }}</li>
                            @endif

                        </ul>
                    </details>
                </div>
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
                                        <div class="carousel-caption d-block d-md-block bg-dark bg-opacity-75 p-3 rounded">
                                            <h5 class="fs-6 fs-md-5">{{ $gallery->alt_text ?? 'Gallery Photo' }}</h5>
                                            @if($gallery->created_at)
                                                <p class="mb-0">{{ $gallery->created_at->format('F Y') }}</p>
                                            @endif
                                        </div>
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

@endpush