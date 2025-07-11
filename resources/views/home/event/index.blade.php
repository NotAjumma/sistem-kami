@extends('home.homeLayout')
@push('styles')
    <style>
        .ticket-box {
            background: rgb(255, 255, 255);
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 1.5rem;
        }

        iframe {
            border-radius: 8px;
            width: 100%;
            height: 450px;
            border: 0;
        }

        /* event title */
        .date-box {
            width: 80px;
            height: 80px;
            border: 1px solid #d1d5db;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        /* Full width on small screens */
        @media (max-width: 576px) {
            .date-box {
                width: 100%;
            }

            .event-title {
                margin-bottom: 20px !important;
            }

            .date-month {
                font-size: 1em !important;
            }
        }

        .date-month {
            background-color: #3736af;
            color: white;
            font-weight: 700;
            font-size: 0.75rem;
            width: 100%;
            text-align: center;
            padding: 0.25rem 0;
        }

        .date-day {
            color: #1f2937;
            /* font-weight: 400; */
            font-size: 1.125rem;
            padding-top: 0.25rem;
            font-weight: bold;

        }

        .event-title {
            font-weight: 900;
            font-size: 1.5rem;
            color: black;
            margin-bottom: 0.25rem;
        }

        .event-info i {
            color: #3736af;
            margin-right: 0.25rem;
        }

        .event-info span {
            font-size: 15px;
            color: #374151;
        }

        .divider {
            border-left: 1px solid #d1d5db;
            height: 1rem;
            margin: 0 0.75rem;
        }

        @media (min-width: 992px) {

            /* lg breakpoint for Bootstrap */
            .ticket-box.sticky-on-lg {
                position: sticky;
                top: 100px;
                /* adjust as needed, space from top */
            }
        }

        /* Organized by */
        .event-ended {
            background-color: #f3f4f6;
            color: #9ca3af;
            font-size: 0.75rem;
            padding: 0.5rem 0;
            border-radius: 0.25rem;
            text-align: center;
            user-select: none;
        }

        .event-started {
            background-color: #3736af;
            color: #fff;
            font-size: 0.75rem;
            padding: 0.5rem 0;
            border-radius: 0.25rem;
            text-align: center;
            cursor: pointer;
            border: 1px solid transparent;
            /* Ensures border transition works */
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .event-started:hover {
            background-color: #fff;
            color: #3736af;
            border-color: #3736af;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .link-red {
            color: #dc2626;
            font-weight: 600;
            text-decoration: none;
        }

        .link-red:hover {
            text-decoration: underline;
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

        .btn-qty {
            width: 38px;
            height: 38px;
            padding: 0;
            font-size: 1.25rem;
            line-height: 1;
            font-weight: 600;
            user-select: none;
        }

        .price-current {
            font-weight: 600;
            color: #4b5563;
            /* gray-700 */
        }

        .price-current-bold {
            font-weight: 600;
            color: #1f2937;
            /* gray-900 */
        }

        .price-old {
            text-decoration: line-through;
            color: #9ca3af;
            /* gray-400 */
            margin-left: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center py-5">
            <div class="date-box mb-3 mb-lg-0">
                <div class="date-month">{{ $event->date_month }}</div>
                <div class="date-day">{{ $event->date_days }}</div>
            </div>

            <div class="ms-lg-3">
                <h2 class="event-title">{{ $event->title }}</h2>

                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center event-info mt-2">

                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <i class="far fa-calendar-alt me-2"></i>
                        <span>
                            @if ($event->formatted_start_date === $event->formatted_end_date)
                                {{ $event->formatted_start_date }}
                            @else
                                {{ $event->formatted_start_date }} – {{ $event->formatted_end_date }}
                            @endif
                        </span>
                    </div>

                    <div class="divider d-none d-md-block mx-3"></div>

                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <i class="far fa-clock me-2"></i>
                        <span>
                            @if ($event->formatted_start_time === $event->formatted_end_time)
                                {{ $event->formatted_start_time }}
                            @else
                                {{ $event->formatted_start_time }} – {{ $event->formatted_end_time }}
                            @endif
                        </span>
                    </div>

                    <div class="divider d-none d-md-block mx-3"></div>

                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        @php
                            $parts = array_filter([
                                $event->district,
                                $event->state,
                                $event->country,
                            ]);
                        @endphp

                        <span>{{ implode(', ', $parts) }}</span>

                    </div>
                </div>
            </div>
        </div>

        {{-- Carousel --}}
        <div id="eventCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($event->images ?? [] as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('images/events/' . $event->id . '/' . $image) }}" class="d-block w-100"
                            alt="Slide {{ $index + 1 }}">
                    </div>
                @endforeach
            </div>
            @if(!empty($event->images) && count($event->images) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="row">
            {{-- Left Column: Description and Map --}}
            <div class="col-lg-8 col-md-7">
                <h6>
                    <span class="badge"
                        style="background-color: #3736af; margin-right: 8px; padding: 0.5em 1em; letter-spacing: 0.2em;">
                        {{ $event->category->name }}
                    </span>
                </h6>


                <h4 class="pb-4">SYARAT-SYARAT PERTANDINGAN</h4>
                {!! $event->description !!}

                {{-- Google Map --}}
                @if($event->latitude && $event->longitude)
                    <div style="margin-top: 50px;">
                        <iframe
                            src="https://www.google.com/maps?q={{ urlencode($event->venue_name) }}%20{{ $event->latitude }},{{ $event->longitude }}&output=embed"
                            width="100%" height="500" frameborder="0" style="border:0" allowfullscreen loading="lazy">
                        </iframe>
                    </div>
                @endif

            </div>

            {{-- Right Column: Ticket Price --}}
            <div class="col-lg-4 col-md-5">
                <div class="ticket-box sticky-on-lg">
                    <h6 class="fw-semibold mb-2" style="font-size: 0.8rem;">Organised By</h6>
                    <hr class="mb-4" />
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $event->organizer->logo_url }}" alt="{{ $event->organizer->name }} logo"
                            class="logo-img me-3" />
                        <div>
                            <p class="fw-semibold mb-1" style="font-size: 1rem; line-height: 1.2;">
                                {{ $event->organizer->name }}
                            </p>
                            <p class="mb-0" style="font-size: 0.875rem; color: #dc2626; font-weight: 600;">
                                Email: <a href="mailto:{{ $event->organizer->email }}"
                                    class="link-red">{{ $event->organizer->email }}</a>
                            </p>
                            <p class="mb-1" style="font-size: 0.875rem; color: #3736af; font-weight: 600;">
                                Phone: {{ $event->organizer->phone }}
                            </p>
                            <!-- <a href="#" class="link-red" style="font-size: 0.875rem;">View Profile</a> -->
                        </div>
                    </div>
                    <hr class="my-4" />
                    @php
                        $place = array_filter([
                            $event->venue_name,
                            $event->district,
                            $event->state,
                        ]);
                    @endphp
                    <p class="fw-semibold d-flex align-items-center mb-4" style="font-size: 0.875rem;">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ implode(', ', $place) }}
                    </p>
                    <!-- <hr class="mb-4" />
                                                                                        <p class="fw-semibold d-flex align-items-center mb-4" style="font-size: 0.875rem; cursor: pointer;">
                                                                                            <i class="fas fa-calendar-alt me-2"></i>
                                                                                            Add to Calendar
                                                                                            <i class="fas fa-chevron-down ms-1" style="font-size: 0.75rem;"></i>
                                                                                        </p> -->
                    <hr class="mb-4" />
                    <form method="POST" action="{{ route('tickets.select') }}">
                        @csrf

                        <h6 class="fw-semibold mb-2" style="font-size: 0.7rem;">Select Tickets</h6>
                        <hr class="mb-4" />

                        <!-- Check event register deadline or event_date end -->
                        @if ($event->status == 1)
                            @if($event->id == 1)
                                @foreach ($filteredTickets as $ticket)
                                    <div class="mb-4 p-3 border rounded shadow-sm bg-light" style="background-color: #fff !important;">
                                        <p class="fw-bold mb-1">
                                            {{ $ticket->name }} -
                                            <span class="text-primary">{{ $event->currency }}
                                                {{ number_format($ticket->price, 2) }}</span>
                                        </p>

                                        @if ($ticket->children->isNotEmpty())
                                            <ul class="list-group mt-2">
                                                @foreach ($ticket->children as $subTicket)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $subTicket->name }}
                                                        <span class="badge bg-primary">RM {{ number_format($subTicket->price, 2) }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                                <a href="{{ $event->buy_link }}" target="_blank" rel="noopener noreferrer"
                                    style="text-decoration: none;">
                                    <h6 class="event-started" style="font-size: 0.75em;">
                                        {{ $event->status_label }}
                                    </h6>
                                </a>
                            @else
                                <input type="hidden" name="event_id" value="{{ $event->id }}" />

                                @foreach ($filteredTickets as $ticket)
                                    <div class="p-4 mb-1" style="border: 2px rgb(235, 237, 241) dashed;">
                                        <h6 class="text-pink fw-semibold mb-1" style="font-size: 1rem;">
                                            {{ strtoupper($ticket->name) }}
                                        </h6>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-3" style="font-size: 0.85rem;">
                                                    <span class="price-current-bold">{{ $event->currency }}
                                                        {{ number_format($ticket->price, 2) }}</span>
                                                </h6>
                                            </div>
                                            <h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <button type="button" class="btn btn-outline-secondary btn-qty"
                                                        data-ticket="{{ $ticket->id }}" data-action="minus">−</button>
                                                    <input type="text" name="tickets[{{ $ticket->id }}][quantity]"
                                                        id="qty-input-{{ $ticket->id }}" value="0" readonly
                                                        class="form-control text-center mx-2"
                                                        style="max-width: 50px; font-weight: 600;" />
                                                    <input type="hidden" name="tickets[{{ $ticket->id }}][id]"
                                                        value="{{ $ticket->id }}">
                                                    <button type="button" class="btn btn-outline-secondary btn-qty"
                                                        data-ticket="{{ $ticket->id }}" data-action="plus">+</button>
                                                </div>
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <p class="fw-semibold mb-0" style="font-size: 1rem;">Total Price</p>
                                    <p class="fw-bold mb-0" style="font-size: 1.5rem; color: #111827;">{{ $event->currency }} <span
                                            id="total-price">0.00</span>
                                    </p>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-3">Book Now</button>
                            @endif
                        @else
                            <h6 class="event-ended" style="font-size: 0.75em;">
                                {{ $event->status_label }}
                            </h6>
                        @endif
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-qty').forEach(button => {
            button.addEventListener('click', () => {
                const ticketId = button.getAttribute('data-ticket');
                const action = button.getAttribute('data-action');
                const input = document.getElementById(`qty-input-${ticketId}`);
                const price = parseFloat(button.closest('.p-4').querySelector('.price-current-bold').textContent.replace(/[^\d.]/g, '')) || 0;

                let currentValue = parseInt(input.value) || 0;

                if (action === 'plus' && currentValue < 10) currentValue++;
                if (action === 'minus' && currentValue > 0) currentValue--;

                input.value = currentValue;

                calculateTotal();
            });
        });

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('input[id^="qty-input-"]').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const container = input.closest('.p-4');
                const price = parseFloat(container.querySelector('.price-current-bold').textContent.replace(/[^\d.]/g, '')) || 0;
                total += qty * price;
            });
            document.getElementById('total-price').textContent = total.toFixed(2);
        }
    </script>

@endpush