@extends('layouts.admin.default')

@push('styles')
<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .detail-value {
        font-size: 1rem;
        color: #212529;
    }
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
    .slot-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid var(--primary);
    }
    .addon-pill {
        display: inline-block;
        background: #e9ecef;
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
        margin: 0.2rem;
    }
    .receipt-thumb {
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }

    .card {
        height: auto;
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
                <div class="d-flex gap-2">
                    @if ($booking->package_id)
                        <a href="{{ route('organizer.business.booking.edit', $booking->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Booking
                        </a>
                        <a href="{{ route('organizer.business.bookings') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    @else
                        <a href="{{ route('organizer.booking.edit', $booking->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit Booking
                        </a>
                        <a href="{{ route('organizer.bookings') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Left Column --}}
        <div class="col-lg-8">

            {{-- Booking Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Booking Information</p>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Booking Code</div>
                            <div class="detail-value fw-bold">{{ $booking->booking_code }}</div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                @php
                                    $statusClass = match ($booking->status) {
                                        'pending'   => 'warning',
                                        'paid'      => 'secondary',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} fs-6">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Payment Type</div>
                            <div class="detail-value">
                                @if ($booking->payment_type)
                                    @php
                                        $ptClass = $booking->payment_type === 'full_payment' ? 'success' : 'warning';
                                    @endphp
                                    <span class="badge bg-{{ $ptClass }}">{{ ucwords(str_replace('_', ' ', $booking->payment_type)) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value">{{ ucwords(str_replace('_', ' ', $booking->payment_method ?? '-')) }}</div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value">{{ $booking->created_at->format('j M Y, H:iA') }}</div>
                        </div>
                        @if ($booking->promoter)
                        <div class="col-sm-6 col-md-4">
                            <div class="detail-label">Promoter</div>
                            <div class="detail-value">{{ $booking->promoter->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Customer Information</p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="detail-label">Name</div>
                            <div class="detail-value">{{ $booking->participant->name }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value">
                                <a href="tel:{{ $booking->participant->phone }}">{{ $booking->participant->phone ?? '-' }}</a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">
                                {{ ($booking->participant->email && $booking->participant->email !== $authUser->email) ? $booking->participant->email : '-' }}
                            </div>
                        </div>
                        @if ($booking->participant->no_ic)
                        <div class="col-sm-6">
                            <div class="detail-label">IC Number</div>
                            <div class="detail-value">{{ $booking->participant->no_ic }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Package / Event Info --}}
            @if ($booking->package)
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Package</p>
                    <div class="detail-value fw-semibold">{{ $booking->package->name }}</div>
                </div>
            </div>
            @elseif ($booking->event)
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Event</p>
                    <div class="detail-value fw-semibold">{{ $booking->event->title }}</div>
                </div>
            </div>
            @endif

            {{-- Time Slots --}}
            @if ($booking->vendorTimeSlots && $booking->vendorTimeSlots->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Booked Slots</p>
                    @foreach ($booking->vendorTimeSlots as $slot)
                    <div class="slot-card">
                        <div class="d-flex justify-content-between flex-wrap gap-1">
                            <div>
                                <strong>{{ $slot->vendorTimeSlot?->slot_name ?? 'Slot' }}</strong>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::parse($slot->booked_date_start)->format('j M Y') }}
                                    @if ($slot->booked_date_end && $slot->booked_date_end !== $slot->booked_date_start)
                                        &mdash; {{ \Carbon\Carbon::parse($slot->booked_date_end)->format('j M Y') }}
                                    @endif
                                </div>
                            </div>
                            @if ($slot->booked_time_start)
                            <div class="text-end text-muted small">
                                {{ \Carbon\Carbon::parse($slot->booked_time_start)->format('H:i') }}
                                @if ($slot->booked_time_end)
                                    &ndash; {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('H:i') }}
                                @endif
                            </div>
                            @endif
                        </div>
                        @if ($slot->notes)
                        <div class="mt-1 text-muted small"><i class="fas fa-sticky-note me-1"></i>{{ $slot->notes }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Add-ons --}}
            @if ($booking->bookingAddons && $booking->bookingAddons->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Add-ons</p>
                    @foreach ($booking->bookingAddons as $bookingAddon)
                        @if ($bookingAddon->addon)
                        <span class="addon-pill">
                            {{ $bookingAddon->addon->name }} &times; {{ $bookingAddon->qty }}
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Booking Tickets (event) --}}
            @if ($booking->bookingTickets && $booking->bookingTickets->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Tickets</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table">
                                <tr>
                                    <th>Ticket Code</th>
                                    <th>Participant Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->bookingTickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_code }}</td>
                                    <td>{{ $ticket->participant_name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $tClass = match ($ticket->status) {
                                                'printed' => 'info',
                                                'checkin' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $tClass }}">{{ ucfirst($ticket->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Custom Form Fields --}}
            @if ($booking->details && $booking->details->isNotEmpty())
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Additional Information</p>
                    <div class="row g-3">
                        @foreach ($booking->details as $detail)
                        <div class="col-sm-6">
                            <div class="detail-label">{{ ucwords(str_replace('_', ' ', $detail->field_key)) }}</div>
                            <div class="detail-value">{{ $detail->field_value ?? '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Payment Summary --}}
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Payment Summary</p>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>RM{{ number_format($booking->total_price ?? 0, 2) }}</span>
                    </div>
                    @if ($booking->discount)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Discount</span>
                        <span class="text-danger">&minus; RM{{ number_format($booking->discount, 2) }}</span>
                    </div>
                    @endif
                    @if ($booking->service_charge)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Service Charge</span>
                        <span>RM{{ number_format($booking->service_charge, 2) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-2">
                        <span>Total</span>
                        <span>RM{{ number_format($booking->final_price ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Paid</span>
                        <span class="text-success">RM{{ number_format($booking->paid_amount ?? 0, 2) }}</span>
                    </div>
                    @if ($booking->payment_type === 'deposit')
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Balance</span>
                        <span class="text-warning fw-semibold">RM{{ number_format(($booking->final_price ?? 0) - ($booking->paid_amount ?? 0), 2) }}</span>
                    </div>
                    @endif
                    @if ($booking->coupon_code)
                    <div class="mt-2 text-muted small"><i class="fas fa-tag me-1"></i>Coupon: {{ $booking->coupon_code }}</div>
                    @endif
                </div>
            </div>

            {{-- Receipt --}}
            <div class="card mb-4">
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

                    @if ($isPdf)
                        <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-file-pdf me-1"></i> View PDF Receipt
                        </a>
                    @elseif ($url)
                        <img src="{{ $url }}" alt="Receipt" class="receipt-thumb w-100"
                            data-bs-toggle="modal" data-bs-target="#receiptImageModal"
                            data-img-url="{{ $url }}" />
                    @else
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                            No receipt uploaded
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card mb-4">
                <div class="card-body">
                    <p class="section-title">Quick Actions</p>
                    <div class="d-grid gap-2">
                        @if ($booking->package_id)
                            <a href="{{ route('organizer.business.booking.edit', $booking->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit Booking
                            </a>
                            @if ($booking->status !== 'cancelled')
                            <form action="{{ route('organizer.business.booking.cancel', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-cancel"
                                    data-booking-code="{{ $booking->booking_code }}">
                                    <i class="fas fa-ban me-1"></i> Cancel Booking
                                </button>
                            </form>
                            @endif
                            @if ($booking->status === 'paid' && $booking->payment_type !== 'full_payment')
                            <form action="{{ route('organizer.business.booking.full_payment', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="button" class="btn btn-outline-success btn-sm w-100 btn-full-payment"
                                    data-booking-code="{{ $booking->booking_code }}"
                                    data-customer-name="{{ $booking->participant->name }}">
                                    <i class="fas fa-dollar-sign me-1"></i> Mark Full Payment
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('booking.receipt.package', $booking->booking_code) }}" target="_blank"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-file-alt me-1"></i> View Receipt Page
                            </a>
                        @else
                            <a href="{{ route('organizer.booking.edit', $booking->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit Booking
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Receipt Image Modal --}}
<div class="modal fade" id="receiptImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="modalReceiptImage" src="" style="width:100%;" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Success', text: @json(session('success')), timer: 2000, showConfirmButton: false });
</script>
@endif
@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Error', text: @json(session('error')) });
</script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageModal = document.getElementById('receiptImageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function (event) {
                const img = event.relatedTarget.getAttribute('data-img-url');
                imageModal.querySelector('#modalReceiptImage').src = img;
            });
        }

        document.querySelectorAll('.btn-cancel').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('form');
                const code = btn.dataset.bookingCode;
                Swal.fire({
                    title: 'Cancel Booking?',
                    text: `Cancel booking "${code}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, cancel it!'
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });

        document.querySelectorAll('.btn-full-payment').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('form');
                const name = btn.dataset.customerName;
                Swal.fire({
                    title: 'Confirm Full Payment?',
                    html: `Mark booking for <strong>${name.toUpperCase()}</strong> as Full Payment?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, confirm!'
                }).then(result => { if (result.isConfirmed) form.submit(); });
            });
        });
    });
</script>
@endpush
