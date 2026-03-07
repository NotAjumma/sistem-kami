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

        <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data" class="col-12">
            @csrf
            @method('PATCH')

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

                    @if ($booking->package_id && $booking->vendorTimeSlots && $booking->vendorTimeSlots->isNotEmpty())
                    {{-- Booked Slots (read-only) --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">
                                Booked Slots
                                <span class="text-muted fw-normal text-lowercase" style="font-size:0.75rem;">&nbsp;&mdash; Changes are not allowed. Please cancel and recreate the booking if needed.</span>
                            </p>
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
                    </div>
                    @endif

                    @if ($booking->package_id && $booking->package && $booking->package->addons->isNotEmpty())
                    {{-- Add-ons --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Add-ons</p>
                            @foreach ($booking->package->addons as $addon)
                            @php
                                $oldVal      = old('addons.' . $addon->id);
                                $bookedQty   = isset($currentAddons[$addon->id]) ? (int) $currentAddons[$addon->id]->qty : 0;
                                $currentVal  = $oldVal !== null ? (int) $oldVal : $bookedQty;
                                $isChecked   = $currentVal > 0;
                            @endphp
                            <div class="addon-row">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $addon->name }}</div>
                                    @if ($addon->price > 0)
                                        <div class="text-muted small">+RM{{ number_format($addon->price, 2) }}{{ $addon->is_qty ? ' each' : '' }}</div>
                                    @else
                                        <div class="text-muted small">Included</div>
                                    @endif
                                </div>

                                @if ($addon->is_qty)
                                    {{-- QTY stepper --}}
                                    <div class="qty-stepper">
                                        <button type="button" class="qty-minus" data-id="{{ $addon->id }}">&#8722;</button>
                                        <input
                                            type="number"
                                            name="addons[{{ $addon->id }}]"
                                            id="addon_qty_{{ $addon->id }}"
                                            value="{{ $currentVal }}"
                                            min="{{ $addon->is_required ? 1 : 0 }}"
                                            data-price="{{ $addon->price }}"
                                        >
                                        <button type="button" class="qty-plus" data-id="{{ $addon->id }}">&#43;</button>
                                    </div>
                                @else
                                    {{-- Checkbox --}}
                                    {{-- Hidden input sends 0 when unchecked --}}
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
                                <span>{{ $booking->package->name }}</span>
                            </div>
                            @elseif ($booking->event)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Event</span>
                                <span>{{ $booking->event->title }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Base Price</span>
                                <span>RM{{ number_format($booking->total_price ?? 0, 2) }}</span>
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

                    {{-- Receipt Upload --}}
                    <!-- <div class="card mb-4">
                        <div class="card-body">
                            <p class="section-title">Payment Receipt</p>
                            @php
                                $filename = $booking->resit_path;
                                $url = null;
                                if ($filename && file_exists(public_path('images/receipts/' . $filename))) {
                                    $url = asset('images/receipts/' . rawurlencode($filename));
                                }
                                $isPdf = $filename && str_ends_with(strtolower($filename), '.pdf');
                            @endphp

                            @if ($url)
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Current Receipt</label><br>
                                    @if ($isPdf)
                                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-file-pdf me-1"></i> View Current PDF
                                        </a>
                                    @else
                                        <img src="{{ $url }}" alt="Receipt" class="receipt-preview w-100" />
                                    @endif
                                </div>
                            @endif

                            <label class="form-label">{{ $url ? 'Replace Receipt' : 'Upload Receipt' }}</label>
                            <input type="file" name="resit" class="form-control" accept="image/*,.pdf">
                            <div class="form-text">Accepted: JPG, PNG, PDF</div>
                        </div>
                    </div> -->

                    {{-- Save Button --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
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
        const basePrice     = {{ (float) ($booking->total_price ?? 0) }};
        const serviceCharge = {{ (float) ($booking->service_charge ?? 0) }};
        const discount      = {{ (float) ($booking->discount ?? 0) }};

        function calcAddonTotal() {
            let total = 0;

            // qty steppers
            document.querySelectorAll('.addon-qty-input').forEach(function (input) {
                const qty   = parseInt(input.value || 0);
                const price = parseFloat(input.dataset.price || 0);
                total += qty * price;
            });

            // checkboxes
            document.querySelectorAll('.addon-checkbox').forEach(function (cb) {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.price || 0);
                }
            });

            return total;
        }

        function updateSummary() {
            const addonTotal = calcAddonTotal();
            const finalPrice = Math.max(basePrice + addonTotal + serviceCharge - discount, 0);

            const rowAddon = document.getElementById('row-addon-total');
            if (rowAddon) {
                rowAddon.style.display = addonTotal > 0 ? '' : 'none';
            }

            const elAddon = document.getElementById('summary-addon-total');
            if (elAddon) elAddon.textContent = 'RM' + addonTotal.toFixed(2);

            const elFinal = document.getElementById('summary-final-price');
            if (elFinal) elFinal.textContent = 'RM' + finalPrice.toFixed(2);
        }

        // Stepper buttons
        document.querySelectorAll('.qty-plus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById('addon_qty_' + btn.dataset.id);
                input.value = parseInt(input.value || 0) + 1;
                updateSummary();
            });
        });

        document.querySelectorAll('.qty-minus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById('addon_qty_' + btn.dataset.id);
                const min   = parseInt(input.min || 0);
                input.value = Math.max(parseInt(input.value || 0) - 1, min);
                updateSummary();
            });
        });

        // Manual input change on qty inputs
        document.querySelectorAll('.addon-qty-input').forEach(function (input) {
            input.addEventListener('input', updateSummary);
        });

        // Checkbox toggles
        document.querySelectorAll('.addon-checkbox').forEach(function (cb) {
            cb.addEventListener('change', updateSummary);
        });

        // Run once on load to set initial state
        updateSummary();
    });
</script>
@endpush
