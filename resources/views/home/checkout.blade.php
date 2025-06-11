@extends('home.homeLayout')

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif, Arial, sans-serif;
            background: #fff;
            color: #000;
        }

        .header-checkout {
            position: relative;
            height: 320px;
            overflow: hidden;
        }

        .header-checkout img.bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.6);
        }

        .header-checkout .overlay {
            position: absolute;
            inset: 0;
            background: rgba(54, 54, 175, 0.7);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 15px;
        }

        .header-checkout h1 {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .header-checkout nav {
            font-weight: 600;
            font-size: 0.75rem;
        }

        .header-checkout nav span:not(:last-child)::after {
            content: " /";
            margin-left: 0.25rem;
        }

        .header-checkout img.lightning {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        textarea.form-control {
            height: 72px;
            resize: none;
        }

        .section-ticket {
            font-weight: 600;
            /* font-size: 1rem; */
            border-top: 1px solid #00ada3;
            padding-top: 1rem;
            margin-top: 1.5rem;
        }

        .ticket-info {
            font-weight: 600;
            /* font-size: 0.75rem; */
            margin-bottom: 0.75rem;
        }

        .order-summary p {
            /* font-size: 0.75rem; */
            margin-bottom: 0.25rem;
        }

        .order-summary .red-text {
            color: #b91c1c;
        }

        .order-summary .total-row {
            font-weight: 600;
            border-top: 1px solid #000;
            padding-top: 0.25rem;
            margin-top: 0.25rem;
            /* font-size: 0.75rem; */
        }

        .coupon-input-group .btn {
            background-color: #3736af;
            color: white;
            font-weight: 600;
            /* font-size: 0.75rem; */
            border: none;
        }

        .payment-method label {
            font-weight: 600;
            /* font-size: 0.75rem; */
            cursor: pointer;
            user-select: none;
        }

        .payment-method input[type="radio"] {
            accent-color: #3736af;
            margin-right: 0.5rem;
        }

        .payment-method i {
            /* font-size: 1rem; */
            margin-right: 0.25rem;
        }

        .btn-pay {
            background-color: #3736af;
            color: white;
            font-weight: 600;
            /* font-size: 0.75rem; */
            border: none;
            width: 100%;
            padding: 0.5rem 0;
            margin-top: 1rem;
        }


        @media (min-width: 992px) {
            .header-checkout {
                height: 288px;
            }
        }
    </style>

    <header class="header-checkout">
        <img src="https://storage.googleapis.com/a1aa/image/5a7e8f99-e964-4937-0010-c05e2911d9ea.jpg"
            alt="Man with blue sunglasses shouting on red background" class="bg-img" loading="lazy" decoding="async"
            width="1920" height="320" />
        <div class="overlay">
            <h1 style="color: #fff;">Checkout</h1>
            <nav aria-label="breadcrumb">
                <span><a href="{{ route('index') }}">Home</span></a>
                <span><a href="{{ route('index') }}">Events</span></a>
                <span>Checkout</span>
            </nav>
            <img src="https://storage.googleapis.com/a1aa/image/f488668f-a0d8-48dd-b03d-ad1c625a1134.jpg"
                alt="Red lightning bolt icon" class="lightning" loading="lazy" decoding="async" width="40" height="40" />
        </div>
    </header>

    <main class="container my-4 mt-5">
        <div class="row gx-4">
            <section class="col-lg-8">
                <form aria-label="Billing Details Form" class="mb-5" method="post" action="{{ route('webform.booking') }}">
                    @csrf
                    <h2 class="section-title">Billing Details</h2>
                    <div class="row gx-3 gy-3 section-ticket">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Full Name *</label>
                            <input type="text" id="firstName" name="name" placeholder="Enter Your First Name"
                                class="form-control" value="{{ old('name') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="phone" class="form-label">No IC *</label>
                            <input type="tel" id="phone" name="no_ic" placeholder="IC Number"
                                class="form-control" value="{{ old('no_ic') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" id="email" name="email" placeholder="Enter Your Email"
                                class="form-control" value="{{ old('email') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="emailConfirm" class="form-label">Email Confirmation *</label>
                            <input type="email" id="emailConfirm" name="emailConfirm" placeholder="Reconfirm your email"
                                class="form-control" value="{{ old('emailConfirm') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="tel" id="phone" name="phone" placeholder="Phone Number"
                                class="form-control" value="{{ old('phone') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="country" class="form-label">Country *</label>
                            <select id="country" name="country" class="default-select form-control" required>
                                @foreach (config('value.countries') as $code => $name)
                                    <option value="{{ $code }}" {{ old('country', 'MY') == $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="state" class="form-label">State *</label>
                            <select id="state" name="state" class="default-select form-control" required>
                                <option disabled {{ old('state') ? '' : 'selected' }}>Select State</option>
                                @foreach (config('value.states') as $state)
                                    <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" id="city" name="city" placeholder="City"
                                class="form-control" value="{{ old('city') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="zip" class="form-label">Zip/Postcode *</label>
                            <input type="text" id="zip" name="zip" placeholder="Zip/Postcode"
                                class="form-control" value="{{ old('zip') }}" />
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" name="address" placeholder="Address" class="form-control" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="col-sm-6">
                            <label for="shirt_size" class="form-label">Shirt Size</label>
                            <select name="shirt_size" class="form-control">
                                <option value="">Pilih saiz baju</option>
                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                    <option value="{{ $size }}" {{ old('shirt_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    @php
                        $totalSelected = collect($tickets)->sum('quantity');
                        $globalIndex = 1;
                    @endphp

                    <h2 class="section-title mt-10">Ticket Details ({{ $totalSelected }}
                        {{ Str::plural('ticket', $totalSelected) }})
                    </h2>

                    <!-- @foreach ($tickets as $ticket)
                            @for ($i = 1; $i <= $ticket['quantity']; $i++)
                                <div aria-label="Ticket Details Form" class="section-ticket">
                                    <p class="ticket-info">
                                        {{ $globalIndex }}) {{ $ticket['name'] }} - #{{ $i }}
                                    </p>
                                    <div class="row gx-3 gy-3">
                                        <div class="col-sm-6">
                                            <label for="fullName_{{ $globalIndex }}" class="form-label">Full Name *</label>
                                            <input type="text" id="fullName_{{ $globalIndex }}" name="fullName[]" class="form-control"
                                                required />
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="age_{{ $globalIndex }}" class="form-label">Age *</label>
                                            <input type="number" id="age_{{ $globalIndex }}" name="age[]" class="form-control"
                                                required />
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="ticketState_{{ $globalIndex }}" class="form-label">State *</label>
                                            <select id="ticketState_{{ $globalIndex }}" name="ticketState[]"
                                                class="default-select form-control" required>
                                                <option selected disabled>Select State</option>
                                                @foreach (config('value.states') as $state)
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="ticketCountry_{{ $globalIndex }}" class="form-label">Country *</label>
                                            <select id="ticketCountry_{{ $globalIndex }}" name="ticketCountry[]"
                                                class="default-select form-control" required>
                                                @foreach (config('value.countries') as $code => $name)
                                                    <option value="{{ $code }}" {{ $code == 'MY' ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="passportNumber_{{ $globalIndex }}" class="form-label">IC / Passport Number
                                                *</label>
                                            <input type="text" id="passportNumber_{{ $globalIndex }}" name="passportNumber[]"
                                                class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                @php $globalIndex++; @endphp
                            @endfor
                        @endforeach -->


            </section>

            <aside class="col-lg-4 mt-4 mt-lg-0 p-3">
                <div class="d-flex align-items-center mb-3">
                    @if (!empty($event->images) && is_array($event->images))
                        <img src="{{ asset('images/events/' . $event->id . '/' . $event->images[0]) }}" alt="Event Image"
                            width="200" class="me-3" loading="lazy" decoding="async" style="border-radius: 20px;" />
                    @endif

                    <div style="line-height: 1.2;">
                        <h2 class="mb-1 fw-bold text-uppercase text-primary" style="">
                            {{ $event->title }}
                        </h2>
                        <p class="mb-0 text-secondary d-flex align-items-center gap-1">
                            <i class="far fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('D, d M Y') }} <i
                                class="far fa-clock ml-2"></i>
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $event->start_time)->format('g:i A') }}
                        </p>
                        <p class="mb-0 text-secondary d-flex align-items-center gap-1">
                            @php
                                $locationParts = array_filter([
                                    $event->district,
                                    $event->state,
                                    $event->country
                                ]);
                            @endphp
                            <i class="fas fa-map-marker-alt"></i>
                            @if (!empty($locationParts))
                                {{ implode(', ', $locationParts) }}
                            @endif
                        </p>
                    </div>
                </div>

                <h3 class="fw-semibold" style="font-size: 0.875rem; margin-bottom: 0.5rem;">Order Summary</h3>
                <div class="order-summary mb-3">
                    <p class="fw-semibold mb-1">Tickets Info</p>
                    @php
                        $totalQuantity = 0;
                        $subtotal = 0;

                        foreach ($tickets as $ticket) {
                            $totalQuantity += $ticket['quantity'];
                            $subtotal += $ticket['quantity'] * floatval($ticket['price']);
                        }

                        $fixed = $event->service_charge_fixed ?? null;
                        $percentage = $event->service_charge_percentage ?? null;

                        if (!is_null($percentage) && $percentage != 0) {
                            $serviceCharge = $subtotal * ($percentage / 100);
                            $serviceChargeLabel = 'Service Charge (' . $percentage . '%)';
                        } elseif (!is_null($fixed) && $fixed != 0) {
                            $serviceCharge = $fixed;
                            $serviceChargeLabel = 'Service Charge (MYR' . number_format($fixed, 2) . ')';
                        } else {
                            $serviceCharge = 0;
                            $serviceChargeLabel = null; // no label to display
                        }

                        $total = $subtotal + $serviceCharge;
                    @endphp

                    @foreach ($tickets as $ticket)
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ $ticket['name'] }}</span>
                            <span>{{ $ticket['quantity'] }}x</span>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Tickets</span>
                        <span>{{ $totalQuantity }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Ticket Price</span>
                        <span>MYR{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span>MYR{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-primary">
                        <span>{{ $serviceChargeLabel }}</span>
                        <span>+ MYR{{ number_format($serviceCharge, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between total-row">
                        <span>Total</span>
                        <span>MYR{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="coupon" class="form-label">Coupon</label>
                    <div class="input-group coupon-input-group">
                        <input type="text" id="coupon" name="coupon" class="form-control" />
                        <button type="button" class="btn">Apply</button>
                    </div>
                </div>

                <div class="payment-method mb-3">
                    <p class="fw-semibold mb-1" style="">Payment Method</p>
                    <p class="mb-2" style="">Select a payment method</p>
                    <label>
                        <input type="radio" name="paymentMethod" value="online" checked />
                        <i class="fas fa-credit-card"></i> ONLINE PAYMENT
                    </label>
                </div>

                <button type="submit" class="btn-pay">Proceed to Pay</button>
            </aside>
            </form>
        </div>
    </main>
@endsection