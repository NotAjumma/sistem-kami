@extends('layouts.admin.default')

@push('styles')
<style>
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6c757d;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .receipt-preview {
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        margin-top: 0.5rem;
    }
    .card {
        height: auto;
    }
    .slot-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid #6c757d;
    }
    .addon-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .addon-row:last-child { border-bottom: none; }
    .qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid #ced4da;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .qty-stepper button {
        width: 34px;
        height: 34px;
        padding: 0;
        border: none;
        background: #f8f9fa;
        font-size: 1.1rem;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
        color: #495057;
        transition: background 0.15s;
    }
    .qty-stepper button:hover { background: #e9ecef; }
    .qty-stepper input[type="number"] {
        width: 48px;
        height: 34px;
        border: none;
        border-left: 1px solid #ced4da;
        border-right: 1px solid #ced4da;
        text-align: center;
        font-size: 0.95rem;
        padding: 0;
        -moz-appearance: textfield;
    }
    .qty-stepper input[type="number"]::-webkit-inner-spin-button,
    .qty-stepper input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; }

    /* Calendar styles */
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
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">{{ $page_title }}</h4>
                    <small class="text-muted">{{ $booking->booking_code }}</small>
                </div>
                <div>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="col-12 mb-3">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @php
            $updateRoute = $booking->package_id
                ? route('organizer.business.booking.update', $booking->id)
                : route('organizer.booking.update', $booking->id);
        @endphp

        <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data" class="col-12" id="editBookingForm">
            @csrf
            @method('PATCH')

            <input type="hidden" name="selected_date" id="selected_date">
            <input type="hidden" name="selected_time" id="selected_time">

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">

                    {{-- Customer Info --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Customer Information</p>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $booking->participant->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $booking->participant->phone) }}">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $booking->participant->email !== $authUser->email ? $booking->participant->email : '') }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @if (!$booking->package_id)
                                <div class="col-sm-6">
                                    <label class="form-label">IC Number</label>
                                    <input type="text" name="no_ic" class="form-control @error('no_ic') is-invalid @enderror"
                                        value="{{ old('no_ic', $booking->participant->no_ic) }}">
                                    @error('no_ic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Booking Status --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Booking Status</p>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        @if ($booking->package_id)
                                            <option value="pending"      {{ old('status', $booking->status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                                            <option value="paid"         {{ old('status', $booking->status) === 'paid'      ? 'selected' : '' }}>Paid</option>
                                            <option value="cancelled"    {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        @else
                                            <option value="pending"      {{ old('status', $booking->status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed"    {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="cancelled"    {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        @endif
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                @if ($booking->package_id)
                                <div class="col-sm-6">
                                    <label class="form-label">Payment Type</label>
                                    <select name="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                                        <option value="deposit"      {{ old('payment_type', $booking->payment_type) === 'deposit'      ? 'selected' : '' }}>Deposit</option>
                                        <option value="full_payment" {{ old('payment_type', $booking->payment_type) === 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                                    </select>
                                    @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Paid Amount (RM)</label>
                                    <input type="number" step="0.01" name="paid_amount"
                                        class="form-control @error('paid_amount') is-invalid @enderror"
                                        value="{{ old('paid_amount', $booking->paid_amount) }}" min="0" required>
                                    @error('paid_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($booking->package_id)
                    {{-- Package Selection --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Package</p>
                            <select class="form-select" name="package_id" id="packageSelect">
                                @foreach ($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ $booking->package_id == $pkg->id ? 'selected' : '' }}>
                                        {{ $pkg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Date & Slot Selection --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">
                                Date & Time Slots
                                <span class="text-muted fw-normal text-lowercase" style="font-size:0.75rem;">&nbsp;&mdash; Select a new date/time to change, or leave as-is to keep current.</span>
                            </p>

                            {{-- Current Booked Slots Display --}}
                            @if ($booking->vendorTimeSlots && $booking->vendorTimeSlots->isNotEmpty())
                            <div id="currentSlotsDisplay" class="mb-3">
                                <label class="form-label text-muted small">Current Booking:</label>
                                @foreach ($booking->vendorTimeSlots as $slot)
                                <div class="slot-card">
                                    <div class="d-flex justify-content-between flex-wrap gap-1">
                                        <div>
                                            <strong class="text-muted">{{ $slot->vendorTimeSlot?->slot_name ?? 'Slot' }}</strong>
                                            <div class="small mt-1">
                                                <i class="fas fa-calendar-alt me-1 text-secondary"></i>
                                                {{ \Carbon\Carbon::parse($slot->booked_date_start)->format('j M Y') }}
                                                @if ($slot->booked_date_end && $slot->booked_date_end !== $slot->booked_date_start)
                                                    &mdash; {{ \Carbon\Carbon::parse($slot->booked_date_end)->format('j M Y') }}
                                                @endif
                                            </div>
                                        </div>
                                        @if ($slot->booked_time_start)
                                        <div class="text-muted small text-end">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($slot->booked_time_start)->format('H:i') }}
                                            @if ($slot->booked_time_end)
                                                &ndash; {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('H:i') }}
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Calendar Loading --}}
                            <div id="calendarLoading" class="d-none d-flex justify-content-center align-items-center" style="height: 150px;">
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2 text-muted small">Loading calendar time slots...</div>
                                </div>
                            </div>

                            {{-- Calendar Content --}}
                            <div id="calendarContent" class="d-none">
                                <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select a Date</h6>
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
                                            <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Not Available
                                        </li>
                                        <li class="list-inline-item col-6 col-md-auto">
                                            <span class="legend-box bg-primary"></span> Selected Date
                                        </li>
                                    </ul>
                                </div>

                                <!-- Calendar Navigation Controls -->
                                <div class="calendar-navigation mt-3">
                                    <div class="row g-2 col-12">
                                        <div class="col-12">
                                            <button type="button" id="prevMonth" class="btn btn-primary w-100" style="text-align: center; display: flex; justify-content: center;">
                                                <div><i class="fa-regular fa-square-caret-left me-2"></i></div>
                                                <div>Previous</div>
                                            </button>
                                        </div>
                                        <div class="col-12 d-flex gap-2">
                                            <select id="monthSelect" class="form-select w-50" style="font-size: 1rem;"></select>
                                            <select id="yearSelect" class="form-select w-50" style="font-size: 1rem;"></select>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex flex-column flex-md-row gap-2">
                                                <button type="button" id="todayBtn" class="btn btn-secondary w-100">Today</button>
                                                <button type="button" id="nextMonth" class="btn btn-primary w-100" style="text-align: center; display: flex; justify-content: center;">
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
                                            <th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th><th style="text-align:center !important;">Su</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendarBody"></tbody>
                                </table>

                                <!-- Time Slots -->
                                <div id="timeSlots" class="d-none">
                                    <h6 class="fw-semibold mb-2" style="font-size: 1.2rem;">Select Time Slots</h6>
                                    <div class="mt-0">
                                        <h5 class="fw-semibold mb-1">Time Slots Indicator:</h5>
                                        <ul class="list-inline row gx-2 gy-2">
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box bg-success"></span> Booked Time
                                            </li>
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box" style="background-color: rgb(205, 205, 207);"></span> Available
                                            </li>
                                            <li class="list-inline-item col-6 col-md-auto">
                                                <span class="legend-box bg-primary"></span> Selected Date
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="table-responsive mt-2" style="overflow-x: auto;">
                                        <table class="table table-bordered text-center align-middle" id="slotTable">
                                            <thead>
                                                <tr id="slotHeader"><th></th></tr>
                                            </thead>
                                            <tbody id="slotBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Add-ons --}}
                    @if ($booking->package_id)
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Add-ons</p>
                            <div id="addonsContainer">
                                @if ($booking->package && $booking->package->addons->isNotEmpty())
                                    @foreach ($booking->package->addons as $addon)
                                    @php
                                        $oldVal      = old('addons.' . $addon->id);
                                        $bookedQty   = isset($currentAddons[$addon->id]) ? (int) $currentAddons[$addon->id]->qty : 0;
                                        $currentVal  = $oldVal !== null ? (int) $oldVal : $bookedQty;
                                        $isChecked   = $currentVal > 0;
                                    @endphp
                                    <div class="addon-row" data-special-start="{{ $addon->special_date_start ?? '' }}" data-special-end="{{ $addon->special_date_end ?? '' }}">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $addon->name }}</div>
                                            @if ($addon->price > 0)
                                                <div class="text-muted small">+RM{{ number_format($addon->price, 2) }}{{ $addon->is_qty ? ' each' : '' }}</div>
                                            @else
                                                <div class="text-muted small">Included</div>
                                            @endif
                                        </div>

                                        @if ($addon->is_qty)
                                            <div class="qty-stepper">
                                                <button type="button" class="qty-minus" data-id="{{ $addon->id }}">&#8722;</button>
                                                <input
                                                    type="number"
                                                    class="addon-qty-input"
                                                    name="addons[{{ $addon->id }}]"
                                                    id="addon_qty_{{ $addon->id }}"
                                                    value="{{ $currentVal }}"
                                                    min="{{ $addon->is_required ? 1 : 0 }}"
                                                    data-price="{{ $addon->price }}"
                                                >
                                                <button type="button" class="qty-plus" data-id="{{ $addon->id }}">&#43;</button>
                                            </div>
                                        @else
                                            <input type="hidden" name="addons[{{ $addon->id }}]" value="0">
                                            <div class="form-check form-switch mb-0">
                                                <input
                                                    class="form-check-input addon-checkbox"
                                                    type="checkbox"
                                                    name="addons[{{ $addon->id }}]"
                                                    id="addon_check_{{ $addon->id }}"
                                                    value="1"
                                                    data-price="{{ $addon->price }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    style="width:2.5em; height:1.25em;"
                                                >
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                @else
                                    <small class="text-muted">No add-ons available for this package</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Additional Information --}}
                    @if ($booking->details && $booking->details->isNotEmpty())
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Additional Information</p>
                            <div class="row g-3">
                                @foreach ($booking->details as $detail)
                                <div class="col-sm-6">
                                    <label class="form-label">{{ ucwords(str_replace('_', ' ', $detail->field_key)) }}</label>
                                    <input type="text" name="details[{{ $detail->field_key }}]"
                                        class="form-control"
                                        value="{{ old('details.' . $detail->field_key, $detail->field_value) }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">

                    {{-- Booking Summary --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Booking Summary</p>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Booking Code</span>
                                <span class="fw-semibold">{{ $booking->booking_code }}</span>
                            </div>
                            @if ($booking->package)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Package</span>
                                <span id="summary-package-name">{{ $booking->package->name }}</span>
                            </div>
                            @elseif ($booking->event)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Event</span>
                                <span>{{ $booking->event->title }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Base Price</span>
                                <span id="summary-base-price">RM{{ number_format($booking->total_price ?? 0, 2) }}</span>
                            </div>
                            @if (($booking->service_charge ?? 0) > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Service Charge</span>
                                <span>RM{{ number_format($booking->service_charge, 2) }}</span>
                            </div>
                            @endif
                            @if (($booking->discount ?? 0) > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Discount</span>
                                <span class="text-danger">&minus; RM{{ number_format($booking->discount, 2) }}</span>
                            </div>
                            @endif
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold" id="summary-final-price">RM{{ number_format($booking->final_price ?? 0, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created</span>
                                <span>{{ $booking->created_at->format('j M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Success', text: @json(session('success')), timer: 2000, showConfirmButton: false });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const packages = @json($packages);
    const currentPackageIdInit = {{ $booking->package_id ?? 'null' }};
    const currentSlots = @json($currentSlots ?? []);
    const serviceCharge = {{ (float) ($booking->service_charge ?? 0) }};
    const discount = {{ (float) ($booking->discount ?? 0) }};
    const maxBookingOffset = 2;

    let calendarData = null;
    let currentPackageId = currentPackageIdInit;
    let currentDate = new Date();
    let selectedTimes = [];
    let dateChanged = false;

    const calendarBody = document.getElementById("calendarBody");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");
    const todayBtn = document.getElementById("todayBtn");
    const timeSlotSection = document.getElementById("timeSlots");
    const monthSelect = document.getElementById("monthSelect");
    const yearSelect = document.getElementById("yearSelect");
    const packageSelect = document.getElementById("packageSelect");
    const addonsContainer = document.getElementById("addonsContainer");
    const selectedDateInput = document.getElementById('selected_date');
    const selectedTimeInput = document.getElementById('selected_time');

    if (!calendarBody || !packageSelect) return; // not a package booking

    // Get the current booked date to pre-navigate calendar
    let preSelectedDate = null;
    if (currentSlots.length > 0) {
        preSelectedDate = currentSlots[0].date;
        const parts = preSelectedDate.split('-');
        currentDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
    }

    // ========== MONTH/YEAR SELECTORS ==========
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const today = new Date();
    const currentYear = today.getFullYear();
    for (let y = currentYear; y <= currentYear + maxBookingOffset; y++) {
        const option = document.createElement("option");
        option.value = y;
        option.textContent = y;
        yearSelect.appendChild(option);
    }

    function populateMonths(selectedYear) {
        monthSelect.innerHTML = "";
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

    yearSelect.value = currentDate.getFullYear();
    populateMonths(currentDate.getFullYear());
    monthSelect.value = currentDate.getMonth();

    yearSelect.addEventListener("change", () => {
        const selectedYear = parseInt(yearSelect.value);
        currentDate.setFullYear(selectedYear);
        populateMonths(selectedYear);
        if (selectedYear === today.getFullYear() && parseInt(monthSelect.value) < today.getMonth()) {
            monthSelect.value = today.getMonth();
            currentDate.setMonth(today.getMonth());
        }
        if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
    });

    monthSelect.addEventListener("change", () => {
        currentDate.setMonth(parseInt(monthSelect.value));
        if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
    });

    // ========== CALENDAR RENDERING ==========
    function renderCalendar(date, data, packageId) {
        const vendorTimeSlots = data.timeSlots;
        const vendorOffDays = data.offDays;
        const bookedVendorDates = data.bookedDates;
        const fullyBookedDates = data.fullyBookedDates;
        const limitReachedDays = data.limitReachedDays;
        const weekRangeBlock = data.weekRangeBlock;
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const firstWeekday = (firstDay.getDay() + 6) % 7;
        const totalDays = lastDay.getDate();

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

        monthSelect.value = month;
        yearSelect.value = year;
        calendarBody.innerHTML = "";

        let row = document.createElement("tr");
        let dayCount = 0;

        for (let i = 0; i < firstWeekday; i++) {
            row.appendChild(document.createElement("td"));
            dayCount++;
        }

        const todayDate = new Date();
        const maxDate = new Date(todayDate.getFullYear() + maxBookingOffset, 11);
        todayDate.setHours(0, 0, 0, 0);

        for (let day = 1; day <= totalDays; day++) {
            const td = document.createElement("td");
            td.textContent = day;
            td.style.cursor = "pointer";

            const currentLoopDate = new Date(year, month, day);
            const yyyy = currentLoopDate.getFullYear();
            const mm = String(currentLoopDate.getMonth() + 1).padStart(2, '0');
            const dd = String(currentLoopDate.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;

            const offDayForDate = offDaysFormatted.find(off => off.date === formattedDate);
            currentLoopDate.setHours(0, 0, 0, 0);

            if (day === todayDate.getDate() && month === todayDate.getMonth() && year === todayDate.getFullYear()) {
                td.classList.add("today");
            }

            // Highlight pre-selected date
            if (preSelectedDate === formattedDate && !dateChanged) {
                td.classList.add("selected");
            }

            let isDisabledDay = false;

            if (bookedDatesFormatted.includes(formattedDate)) {
                td.classList.add("booked-day");
                td.title = "Unavailable (Fully Booked)";
                isDisabledDay = true;
            } else if (limitReachedDays.includes(formattedDate)) {
                td.classList.add("past-day");
                td.title = "Booking limit reached for this week";
                isDisabledDay = true;
            } else if (offDayForDate) {
                if ((!offDayForDate.start_time && !offDayForDate.end_time) ||
                    (offDayForDate.start_time === "00:00:00" && offDayForDate.end_time === "23:59:59")) {
                    td.classList.add("off-day");
                    td.title = "Unavailable (Off Day)";
                    isDisabledDay = true;
                }
            } else if (currentLoopDate.getTime() < todayDate.getTime()) {
                td.classList.add("past-day");
                td.title = "Cannot book past date";
                isDisabledDay = true;
            } else {
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
                    isDisabledDay = true;
                }
            }

            const isPrevMonthBeforeToday =
                year < todayDate.getFullYear() ||
                (year === todayDate.getFullYear() && month <= todayDate.getMonth());
            prevMonthBtn.disabled = isPrevMonthBeforeToday;

            const isNextMonthAfterLimit =
                currentDate.getFullYear() > maxDate.getFullYear() ||
                (currentDate.getFullYear() === maxDate.getFullYear() && currentDate.getMonth() >= maxDate.getMonth());
            nextMonthBtn.disabled = isNextMonthAfterLimit;

            td.addEventListener("click", () => {
                if (isDisabledDay) return;

                dateChanged = true;
                document.querySelectorAll("#calendarBody td").forEach(cell => cell.classList.remove("selected"));
                td.classList.add("selected");

                selectedDateInput.value = formattedDate;

                // Hide current slots display since user is changing
                const currentDisplay = document.getElementById('currentSlotsDisplay');
                if (currentDisplay) currentDisplay.style.display = 'none';

                if (vendorTimeSlots.length > 0) {
                    const shouldHide =
                        currentLoopDate.getTime() < todayDate.getTime() ||
                        offDaysFormatted.find(o => o.date === formattedDate) ||
                        limitReachedDays.includes(formattedDate);

                    if (shouldHide) {
                        timeSlotSection.classList.add("d-none");
                    } else {
                        timeSlotSection.classList.remove("d-none");
                        renderTimeSlot(formattedDate, currentLoopDate, packageId, data.package.base_price);
                    }
                } else {
                    // Full-day booking - no time slot selection needed
                    selectedTimeInput.value = '';
                    selectedTimes = [];
                }

                updateSummary();
            });

            row.appendChild(td);
            dayCount++;

            if (dayCount % 7 === 0) {
                calendarBody.appendChild(row);
                row = document.createElement("tr");
            }
        }

        while (dayCount % 7 !== 0) {
            row.appendChild(document.createElement("td"));
            dayCount++;
        }
        calendarBody.appendChild(row);
    }

    // ========== TIME SLOT RENDERING ==========
    function renderTimeSlot(date, currentLoopDate, packageId, base_price) {
        const slotHeader = document.getElementById("slotHeader");
        const slotBody = document.getElementById("slotBody");

        selectedTimes = [];
        selectedTimeInput.value = '';
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
                slotHeader.innerHTML = "<th></th>";
                slotBody.innerHTML = "";

                if (!data.slots || data.slots.length === 0) {
                    slotBody.innerHTML = `
                        <tr>
                            <td colspan="100%" class="text-muted py-3">
                                No available time slots for this date.
                            </td>
                        </tr>`;
                    return;
                }

                const timeLabels = data.slots[0].times;
                timeLabels.forEach(time => {
                    const [hourPart, ampm] = time.split(" ");
                    const th = document.createElement("th");
                    th.innerHTML = `<div style="font-size: 15px;" class="text-muted">${hourPart}</div><div style="font-size: 15px;" class="text-muted">${ampm || ""}</div>`;
                    th.style.lineHeight = "1.1";
                    th.style.padding = "10px";
                    slotHeader.appendChild(th);
                });

                data.slots.forEach(slot => {
                    const tr = document.createElement("tr");
                    const courtCell = document.createElement("td");
                    courtCell.textContent = slot.court;
                    courtCell.style.fontWeight = "600";
                    tr.appendChild(courtCell);

                    slot.times.forEach(time => {
                        const td = document.createElement("td");
                        td.classList.add("p-2", "text-center");

                        const checkbox = document.createElement("input");
                        checkbox.type = "checkbox";
                        checkbox.classList.add("form-check-input");
                        checkbox.value = `${slot.id}|${time}`;

                        const [hourMin, ampm] = time.split(" ");
                        let [hours, minutes] = hourMin.split(":").map(Number);
                        if (ampm === "PM" && hours !== 12) hours += 12;
                        if (ampm === "AM" && hours === 12) hours = 0;
                        const currentTime24 = `${hours.toString().padStart(2,"0")}:${minutes.toString().padStart(2,"0")}:00`;

                        let isSlotDisabled = slot.bookedTimes && slot.bookedTimes.includes(time);

                        if (data.vendorOffTimes) {
                            data.vendorOffTimes.forEach(off => {
                                if (currentTime24 >= off.start_time && currentTime24 < off.end_time) {
                                    isSlotDisabled = true;
                                }
                            });
                        }

                        if (isSlotDisabled) {
                            checkbox.disabled = true;
                            td.classList.add("bg-success", "bg-opacity-50");
                            td.title = "Unavailable";
                        }

                        if (slot.bookedTimes && slot.bookedTimes.includes(time)) {
                            checkbox.disabled = true;
                            td.classList.add("bg-success", "bg-opacity-50");
                        }

                        checkbox.addEventListener("change", () => {
                            const slotObj = { date, id: slot.id, time };

                            if (checkbox.checked) {
                                if (slot.is_theme_first) {
                                    td.classList.add("bg-primary", "text-white");
                                    selectedTimes = [slotObj];
                                    document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                        if (cb !== checkbox) cb.disabled = true;
                                    });
                                } else {
                                    td.classList.add("bg-primary", "text-white");
                                    selectedTimes.push(slotObj);
                                }
                            } else {
                                td.classList.remove("bg-primary", "text-white");
                                selectedTimes = selectedTimes.filter(
                                    s => !(s.date === slotObj.date && s.id === slotObj.id && s.time === slotObj.time)
                                );

                                if (slot.is_theme_first) {
                                    document.querySelectorAll('#slotBody input[type="checkbox"]').forEach(cb => {
                                        cb.disabled = false;
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
                            updateSummary();
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

    // ========== CALENDAR NAV BUTTONS ==========
    todayBtn.addEventListener("click", () => {
        currentDate = new Date();
        if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
    });

    prevMonthBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
    });

    nextMonthBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        if (calendarData) renderCalendar(currentDate, calendarData, currentPackageId);
    });

    // ========== FETCH CALENDAR DATA ==========
    function fetchCalendarData(packageId) {
        const loading = document.getElementById('calendarLoading');
        const content = document.getElementById('calendarContent');

        loading.classList.remove('d-none');
        content.classList.add('d-none');

        fetch(`/organizer/business/packages/${packageId}/calendar-data`)
            .then(res => res.json())
            .then(data => {
                calendarData = data;
                currentPackageId = packageId;
                renderCalendar(currentDate, calendarData, currentPackageId);

                loading.classList.add('d-none');
                content.classList.remove('d-none');
            })
            .catch(err => {
                console.error(err);
                loading.classList.add('d-none');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load calendar.' });
            });
    }

    // Load calendar for current package on page load
    if (currentPackageId) {
        fetchCalendarData(currentPackageId);
    }

    // ========== PACKAGE CHANGE ==========
    packageSelect.addEventListener('change', function () {
        const packageId = this.value;
        if (!packageId) return;

        currentPackageId = packageId;
        dateChanged = false;
        selectedTimes = [];
        selectedDateInput.value = '';
        selectedTimeInput.value = '';
        preSelectedDate = null;

        // Update summary package name
        const selectedPkg = packages.find(p => p.id == packageId);
        const summaryName = document.getElementById('summary-package-name');
        if (summaryName && selectedPkg) summaryName.textContent = selectedPkg.name;

        // Reset calendar to current month
        currentDate = new Date();

        // Show current slots display again
        const currentDisplay = document.getElementById('currentSlotsDisplay');
        if (currentDisplay) currentDisplay.style.display = 'none';

        // Rebuild addons for new package
        rebuildAddons(packageId);

        // Fetch new calendar data
        fetchCalendarData(packageId);
    });

    // ========== REBUILD ADDONS ==========
    function rebuildAddons(packageId) {
        if (!addonsContainer) return;

        const selectedPackage = packages.find(p => p.id == packageId);
        addonsContainer.innerHTML = '';

        if (!selectedPackage || !selectedPackage.addons || selectedPackage.addons.length === 0) {
            addonsContainer.innerHTML = '<small class="text-muted">No add-ons available for this package</small>';
            return;
        }

        selectedPackage.addons.forEach(addon => {
            let html = '';
            if (addon.is_qty == 1) {
                html = `
                <div class="addon-row" data-special-start="${addon.special_date_start ?? ''}" data-special-end="${addon.special_date_end ?? ''}">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${addon.name}</div>
                        <div class="text-muted small">+RM${parseFloat(addon.price).toFixed(2)} each</div>
                    </div>
                    <div class="qty-stepper">
                        <button type="button" class="qty-minus" data-id="${addon.id}">&#8722;</button>
                        <input type="number" class="addon-qty-input" name="addons[${addon.id}]" id="addon_qty_${addon.id}" value="0" min="0" data-price="${addon.price}">
                        <button type="button" class="qty-plus" data-id="${addon.id}">&#43;</button>
                    </div>
                </div>`;
            } else {
                html = `
                <div class="addon-row" data-special-start="${addon.special_date_start ?? ''}" data-special-end="${addon.special_date_end ?? ''}">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${addon.name}</div>
                        ${addon.price > 0 ? `<div class="text-muted small">+RM${parseFloat(addon.price).toFixed(2)}</div>` : '<div class="text-muted small">Included</div>'}
                    </div>
                    <input type="hidden" name="addons[${addon.id}]" value="0">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input addon-checkbox" type="checkbox" name="addons[${addon.id}]"
                            id="addon_check_${addon.id}" value="1" data-price="${addon.price}" style="width:2.5em; height:1.25em;">
                    </div>
                </div>`;
            }
            addonsContainer.insertAdjacentHTML('beforeend', html);
        });

        // Re-bind addon events
        bindAddonEvents();
        updateSummary();
    }

    // ========== SUMMARY CALCULATION ==========
    function getBasePrice() {
        const pkg = packages.find(p => p.id == currentPackageId);
        return pkg ? parseFloat(pkg.base_price) : 0;
    }

    function calcAddonTotal() {
        let total = 0;
        document.querySelectorAll('.addon-qty-input').forEach(input => {
            total += parseInt(input.value || 0) * parseFloat(input.dataset.price || 0);
        });
        document.querySelectorAll('.addon-checkbox').forEach(cb => {
            if (cb.checked) total += parseFloat(cb.dataset.price || 0);
        });
        return total;
    }

    function updateSummary() {
        const pkg = packages.find(p => p.id == currentPackageId);
        const slotQty = pkg ? (pkg.package_slot_quantity || 1) : 1;
        const slotCount = selectedTimes.length > 0 ? selectedTimes.length : Math.max({{ $booking->vendorTimeSlots->count() }}, 1);
        const packageQty = Math.floor(slotCount / slotQty);
        const basePrice = getBasePrice() * packageQty;

        const addonTotal = calcAddonTotal();
        const finalPrice = Math.max(basePrice + addonTotal + serviceCharge - discount, 0);

        const elBase = document.getElementById('summary-base-price');
        if (elBase) elBase.textContent = 'RM' + (basePrice + addonTotal).toFixed(2);

        const elFinal = document.getElementById('summary-final-price');
        if (elFinal) elFinal.textContent = 'RM' + finalPrice.toFixed(2);
    }

    // ========== ADDON EVENT BINDING ==========
    function bindAddonEvents() {
        document.querySelectorAll('.qty-plus').forEach(btn => {
            btn.addEventListener('click', function () {
                const input = document.getElementById('addon_qty_' + btn.dataset.id);
                if (input) {
                    input.value = parseInt(input.value || 0) + 1;
                    updateSummary();
                }
            });
        });

        document.querySelectorAll('.qty-minus').forEach(btn => {
            btn.addEventListener('click', function () {
                const input = document.getElementById('addon_qty_' + btn.dataset.id);
                if (input) {
                    const min = parseInt(input.min || 0);
                    input.value = Math.max(parseInt(input.value || 0) - 1, min);
                    updateSummary();
                }
            });
        });

        document.querySelectorAll('.addon-qty-input').forEach(input => {
            input.addEventListener('input', updateSummary);
        });

        document.querySelectorAll('.addon-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });
    }

    // Initial bind
    bindAddonEvents();
    updateSummary();

    // ========== FORM SUBMIT ==========
    const form = document.getElementById('editBookingForm');
    const saveBtn = document.getElementById('saveBtn');

    form.addEventListener('submit', function (e) {
        if (saveBtn.classList.contains('submitting')) {
            e.preventDefault();
            return false;
        }
        saveBtn.classList.add('submitting');
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Saving...`;
    });
});
</script>
@endpush
