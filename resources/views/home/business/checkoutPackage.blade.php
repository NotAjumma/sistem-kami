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

        .parent-ticket {
            border-top: 2px dashed rgb(224, 230, 229);
            padding-top: 1rem;
            width: 90%;
            margin: 1.5rem 0.5rem 0.5rem;
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

        h1,
        h2,
        h3,
        h6 {
            font-family: 'Playfair Display', serif !important;
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
                <span>
                    <a href="{{ route('business.profile', ['slug' => $package->organizer->slug]) }}">
                        {{ $package->organizer->name }}
                    </a>
                </span>

                <span>
                    <a href="{{ route('business.package', [
        'organizerSlug' => $package->organizer->slug,
        'packageSlug' => $package->slug
    ]) }}">
                        {{ $package->name }}
                    </a>
                </span>

                <span>Checkout</span>
            </nav>
            <img src="https://storage.googleapis.com/a1aa/image/f488668f-a0d8-48dd-b03d-ad1c625a1134.jpg"
                alt="Red lightning bolt icon" class="lightning" loading="lazy" decoding="async" width="40" height="40" />
        </div>
    </header>

    <main class="container my-4 mt-5">
        <div class="row gx-4">
            <section class="col-lg-8">
                <form aria-label="Billing Details Form" class="mb-5" method="post" action="{{ route('webform.booking_package') }}">
                    @csrf
                    <h2 class="section-title" style="font-size: 1.5rem;">Billing Details</h2>
                    <div class="row gx-3 gy-3 section-ticket">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">Full Name *</label>
                            <input type="text" id="firstName" name="name" placeholder="Enter Your Full Name"
                                class="form-control" value="{{ old('name') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="phone" class="form-label">No IC *</label>
                            <input type="tel" id="phone" name="no_ic" placeholder="IC Number" class="form-control"
                                value="{{ old('no_ic') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" id="email" name="email" placeholder="Enter Your Email" class="form-control"
                                value="{{ old('email') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="emailConfirm" class="form-label">Email Confirmation *</label>
                            <input type="email" id="emailConfirm" name="emailConfirm" placeholder="Reconfirm your email"
                                class="form-control" value="{{ old('emailConfirm') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="tel" id="phone" name="phone" placeholder="Phone Number" class="form-control"
                                value="{{ old('phone') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="ws" class="form-label">Whatsapp Number *</label>
                            <input type="tel" id="ws" name="whatsapp_number" placeholder="Whatsapp Number"
                                class="form-control" value="{{ old('whatsapp_number') }}" required />
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
                            <input type="text" id="city" name="city" placeholder="City" class="form-control"
                                value="{{ old('city') }}" required />
                        </div>

                        <div class="col-sm-6">
                            <label for="postcode" class="form-label">Zip/Postcode *</label>
                            <input type="text" id="postcode" name="postcode" placeholder="Zip/Postcode" class="form-control"
                                value="{{ old('postcode') }}" />
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address *</label>
                            <textarea id="address" name="address" placeholder="Address" class="form-control"
                                required>{{ old('address') }}</textarea>
                        </div>
                    </div>


                    {{-- @php
                    $totalSelected = collect($tickets)->sum('quantity');
                    $globalIndex = 1;
                    @endphp

                    <h2 class="section-title mt-10">Ticket Details ({{ $totalSelected }}
                        {{ Str::plural('ticket', $totalSelected) }})
                    </h2>

                    @foreach ($tickets as $ticket)
                    @for ($i = 1; $i <= $ticket['quantity']; $i++) <div class="section-ticket">
                        <p class="ticket-info" style="font-size: 1rem;">
                            {{ $globalIndex }}. {{ strtoupper($ticket['name']) }} - #{{ $i }}
                        </p>

                        @php
                        $ticketInputs = session('selected_ticket_inputs')[$ticket['ticket_id']] ?? [];
                        @endphp

                        <div class="row gx-3 gy-3">
                            @foreach ($ticketInputs as $parent => $inputs)
                            @if ($parent !== 'General')
                            <div class="col-12 mb-2 parent-ticket">
                                <h6 class="text-primary" style="font-size: 0.8rem;">{{ $parent }}</h6>
                            </div>
                            @endif


                            @foreach ($inputs as $input)
                            <div class="col-sm-6">
                                <label for="input_{{ $input['id'] }}_{{ $globalIndex }}" class="form-label">
                                    {{ $input['label'] }}{{ $input['is_required'] ? ' *' : '' }}
                                </label>

                                @switch($input['input_type'])
                                @case('text')
                                <input type="text" id="input_{{ $input['id'] }}_{{ $globalIndex }}"
                                    name="ticket_inputs[{{ $globalIndex }}][{{ $input['id'] }}]" class="form-control"
                                    value="{{ old(" ticket_inputs.$globalIndex." . $input['id']) }}"
                                    placeholder="{{ $input['placeholder'] ?? '' }}" {{ $input['is_required'] ? 'required'
                                    : '' }} />
                                @break

                                @case('textarea')
                                <textarea id="input_{{ $input['id'] }}_{{ $globalIndex }}"
                                    name="ticket_inputs[{{ $globalIndex }}][{{ $input['id'] }}]" class="form-control"
                                    placeholder="{{ $input['placeholder'] ?? '' }}" {{ $input['is_required'] ? 'required'
                                    : '' }}>{{ old("ticket_inputs.$globalIndex." . $input['id']) }}</textarea>
                                @break

                                @case('select')
                                <select id="input_{{ $input['id'] }}_{{ $globalIndex }}"
                                    name="ticket_inputs[{{ $globalIndex }}][{{ $input['id'] }}]" class="form-control" {{
                                    $input['is_required'] ? 'required' : '' }}>
                                    <option disabled {{ old("ticket_inputs.$globalIndex." . $input['id']) ? '' : 'selected'
                                        }}>Select {{ $input['label'] }}</option>
                                    @foreach ($input['options'] as $option)
                                    <option value="{{ $option }}" {{ old("ticket_inputs.$globalIndex." .
                                        $input['id'])==$option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                    @endforeach
                                </select>
                                @break

                                @case('radio')
                                <div id="input_{{ $input['id'] }}_{{ $globalIndex }}">
                                    @foreach ($input['options'] as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="ticket_inputs[{{ $globalIndex }}][{{ $input['id'] }}]"
                                            id="radio_{{ $input['id'] }}_{{ $globalIndex }}_{{ $loop->index }}"
                                            value="{{ $option }}" {{ old("ticket_inputs.$globalIndex." .
                                            $input['id'])==$option ? 'checked' : '' }} {{ $input['is_required'] ? 'required'
                                            : '' }} />
                                        <label class="form-check-label"
                                            for="radio_{{ $input['id'] }}_{{ $globalIndex }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @break

                                @case('checkbox')
                                <div id="input_{{ $input['id'] }}_{{ $globalIndex }}">
                                    @foreach ($input['options'] as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="ticket_inputs[{{ $globalIndex }}][{{ $input['id'] }}][]"
                                            id="checkbox_{{ $input['id'] }}_{{ $globalIndex }}_{{ $loop->index }}"
                                            value="{{ $option }}" {{ is_array(old("ticket_inputs.$globalIndex." .
                                            $input['id'])) && in_array($option, old("ticket_inputs.$globalIndex." .
                                            $input['id'])) ? 'checked' : '' }} {{ $input['is_required'] ? 'required' : ''
                                            }} />
                                        <label class="form-check-label"
                                            for="checkbox_{{ $input['id'] }}_{{ $globalIndex }}_{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @break
                                @endswitch
                            </div>
                            @endforeach

                            @endforeach
                        </div>
        </div>

        @php $globalIndex++; @endphp
        @endfor
        @endforeach --}}

        </section>

        <aside class="col-lg-4 mt-4 mt-lg-0 p-3">
            <div class="d-flex align-items-center mb-3">
                @if (!empty($package->images) && count($package->images) > 0)
                    @php
                        $coverImage = collect($package->images)->firstWhere('is_cover', true) ?? $package->images[0];
                    @endphp
                    <img src="{{ asset('images/organizers/' . $package->organizer->id . '/packages/' . $package->id . '/' . $coverImage['url']) }}"
                        alt="{{ $coverImage['alt_text'] }}" width="200" class="me-3 object-fit-cover" loading="lazy" decoding="async"
                        style="border-radius: 20px; height: 150px; " />
                @endif


                <div style="line-height: 1.2;">
                    <h2 class="mb-1 fw-bold text-uppercase text-primary" style="font-size: 1.5rem;">
                        {{ $package->name }}
                    </h2>
                    <span class="badge"
                        style="background-color: #3736af; padding: 0.5em 1em; letter-spacing: 0.2em;">
                        {{ $package->category->name }}
                    </span>
                    <p class="mb-0 text-secondary d-flex align-items-center gap-1" style="font-style: italic;">
                        by {{ $package->organizer->name }}
                    </p>
                </div>
            </div>

            <h3 class="fw-semibold" style="font-size: 0.875rem; margin-bottom: 0.5rem;">Booking Summary</h3>
            <div class="order-summary mb-3">
                <p class="mb-1"><strong>Package Info</strong></p>

                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-semibold ">Selected Date</span>
                    <span class="fw-semibold ">{{ \Carbon\Carbon::parse($selected_date)->format('d F Y') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Package Price</span>
                    <span>RM{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Subtotal</span>
                    <span>RM{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-primary">
                    <span>{{ $serviceChargeLabel }}</span>
                    <span>+ RM{{ number_format($serviceCharge, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between total-row">
                    <span>Total</span>
                    <span>RM{{ number_format($total, 2) }}</span>
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