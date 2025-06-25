@extends('home.homeLayout')
@push('styles')
    <style>
        .card {
            /* background-color: rgba(0, 31, 77, 1); */
            border-radius: 20px;
            max-width: 480px;
            width: 90%;
            color: #bfc3cc;
            position: relative;
            padding: 2rem 2.5rem 3rem;
            box-shadow: 0 8px 25px rgb(0 0 0 / 0.5);
        }

        .check-circle {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            /* background-color: rgba(0, 31, 77, 1); */
            border: 4px solid rgba(0, 31, 77, 1);
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 15px 4px #1abc9c;
        }

        .check-circle svg {
            width: 36px;
            height: 36px;
            fill: #1abc9c;
        }

        h3 {
            color: #dee1e6;
            font-weight: 600;
        }

        hr {
            border-color: #333740;
            margin: 1.8rem 0;
        }

        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.7rem;
            font-size: 0.9rem;
        }

        .payment-detail-row strong {
            color: #dee1e6;
        }

        .btn-pdf {
            margin-top: 2rem;
            color: #a1a4ae;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.25s;
            user-select: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-pdf:hover {
            color: #1abc9c;
        }

        .btn-pdf svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        @media (max-width: 400px) {
            .card {
                padding: 1.5rem 1.8rem 2.5rem;
            }

            .check-circle {
                width: 60px;
                height: 60px;
            }

            .check-circle svg {
                width: 28px;
                height: 28px;
            }
        }

        .dashed-line {
            border: none;
            border-top: 2px dashed white;
        }
    </style>
@endpush

@section('content')
    @if ($booking->status === 'paid')
        {{-- Payment Success Card --}}
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-sm bg-primary-dark">
                <div class="check-circle bg-primary-dark" aria-label="Success">
                    <i class="fa-regular fa-circle-check fa-beat text-success" style="font-size: 4rem;"></i>
                </div>
                <div class="text-center mt-4 pt-3">
                    <h3>Payment Success!</h3>
                    <p class="mb-3">Your payment has been successfully done.</p>
                    <hr class="dashed-line mb-2" />
                    <p class="mb-1" style="font-size:1rem;">Total Payment</p>
                    <h4 class="text-white fw-bold mb-4">RM {{ $booking->paid_amount }}</h4>
                    @if ($booking->package_id && $booking->package)
                        <div class="mt-4 mb-4 row align-items-stretch justify-content-center text-start border rounded p-2">
                            @if (!empty($booking->package->images) && count($booking->package->images) > 0)
                                @php
                                    $coverImage = collect($booking->package->images)->firstWhere('is_cover', true) ?? $booking->package->images[0];
                                @endphp
                                <div class="col-auto h-100 d-flex align-items-center">
                                    <img src="{{ asset('images/organizers/' . $booking->package->organizer->id . '/packages/' . $booking->package->id . '/' . $coverImage['url']) }}"
                                        alt="{{ $coverImage['alt_text'] }}" width="50" height="50"
                                        class="object-fit-cover rounded shadow-sm border" loading="lazy" decoding="async" />
                                </div>
                            @endif

                            <div class="col h-100 d-flex flex-column justify-content-center">
                                <p class="mb-1 small" style="color: #7cb6cc; !important">Package Purchased</p>
                                <h5 class="text-white mb-0 medium">{{ $booking->package->name }}</h5>
                                <small class="text-white mb-0">by {{ $booking->package->organizer->name }}</small>
                            </div>

                            @if ($booking->vendorTimeSlot && $booking->vendorTimeSlot->booked_date_start)
                                <div class="col-auto h-100 d-flex flex-column justify-content-center text-end">
                                    <p class="mb-1 small" style="color: #7cb6cc; !important">Your Event Date</p>
                                    <small class="text-white">
                                        {{ \Carbon\Carbon::parse($booking->vendorTimeSlot->booked_date_start)->format('d M Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>


                    @elseif ($booking->event_id && $booking->event)
                        <div class="mt-4 mb-4 row align-items-center justify-content-center text-start">
                            <div class="col-auto">
                                <img src="{{ asset('storage/' . $booking->event->image) }}" alt="{{ $booking->event->name }}"
                                    class="rounded object-fit-cover" style="width: 70px; height: 70px;" loading="lazy"
                                    decoding="async" />
                            </div>
                            <div class="col">
                                <p class="mb-1 text-muted small">Event Registered</p>
                                <h5 class="text-white mb-0">{{ $booking->event->name }}</h5>
                            </div>
                        </div>
                    @endif

                    {{-- Details --}}
                    <div class="payment-detail-row"><span>Receipt Number</span><strong>{{ $booking->booking_code }}</strong>
                    </div>
                    <div class="payment-detail-row"><span>Payment Date &
                            Time</span><strong>{{ format_payment_datetime($booking->created_at) }}</strong></div>
                    <div class="payment-detail-row"><span>Payment
                            Type</span><strong>{{ get_payment_type_label($booking->payment_type) }}</strong></div>
                    <div class="payment-detail-row"><span>Payment
                            Method</span><strong>{{ get_payment_method_label($booking->payment_method) }}</strong></div>
                    <div class="payment-detail-row"><span>Sender Name</span><strong>{{ $booking->participant->name }}</strong>
                    </div>
                    <div class="payment-detail-row"><span>Sender
                            Phone</span><strong>{{ format_my_phone($booking->participant->phone, $booking->participant->country) }}</strong>
                    </div>

                    <hr class="dashed-line" />

                    <button class="btn-pdf" aria-label="Get PDF Receipt" type="button">
                        {{-- Icon --}}
                        Get PDF Receipt
                    </button>

                    <div class="row mt-5">
                        <small class="text-small">
                            Thank you for choosing us <a href="{{ config('app.url') }}"
                                class="text-info">{{ config('app.url') }}</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Payment Failed Card --}}
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-sm bg-danger text-white text-center">
                <div class="check-circle bg-danger" aria-label="Failed">
                    <i class="fa-regular fa-circle-xmark fa-beat text-white" style="font-size: 4rem;"></i>
                </div>
                <div class="text-center mt-4 pt-3">
                    <h3>Payment Failed</h3>
                    <p class="mb-3">Unfortunately, your payment was not successful.</p>
                    <hr class="dashed-line" />
                    <div class="payment-detail-row">
                        <span>Booking Code</span>
                        <strong>{{ $booking->booking_code }}</strong>
                    </div>
                    <div class="payment-detail-row">
                        <span>Status</span>
                        <strong class="text-uppercase">{{ $booking->status }}</strong>
                    </div>

                    <div class="mt-4">
                        Contact Support
                        <a href="mailto:{{ env('SISTEM_EMAIL') }}" class="underline">
                            {{ env('SISTEM_EMAIL') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection