@extends('layouts.admin.default')
@push('styles')
    <style>
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $page_title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="filterForm" class="mb-3 d-flex gap-2 flex-wrap">
                            {{-- Status dropdown --}}
                            <select name="status" onchange="document.getElementById('filterForm').submit()"
                                class="form-select" style="width: 250px;">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>

                            <select name="event_search" onchange="document.getElementById('filterForm').submit()"
                                class="form-select" style="width: 250px;">
                                <option value="">All Events</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->title }}" {{ request('event_search') == $event->title ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>


                            {{-- Search input --}}
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search bookings..." style="width: 250px;">

                            {{-- Buttons wrapper --}}
                            <div class="d-flex gap-2 flex-grow-1 flex-wrap flex-md-nowrap"
                                style="width: 100%; max-width: 200px;">
                                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                                <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-outline-info flex-fill">Clear</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="example3" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Code</th>
                                        <th>Event</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>No IC</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Receipt</th>
                                        <th>Date/Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $index => $booking)
                                        <tr data-status="{{ $booking->status }}">
                                            <td>{{ $bookings->firstItem() + $index }}</td>
                                            <td>{{ $booking->booking_code }}</td>
                                            <td>{{ $booking->event->title }}</td>
                                            <td>{{ $booking->participant->name }}</td>
                                            <td>{{ $booking->participant->email }}</td>
                                            <td>{{ $booking->participant->no_ic }}</td>
                                            <td>{{ $booking->participant->phone }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($booking->status) {
                                                        'pending' => 'warning',
                                                        'confirmed' => 'success',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>

                                            <td>RM{{ number_format($booking->total_price, 2) }}</td>

                                            <td>
                                                @php
                                                    $filename = $booking->resit_path; // e.g., 'resit.pdf' or 'resit.png'
                                                    $encoded = rawurlencode($filename);
                                                    $url = asset('images/receipts/' . $encoded);

                                                    $isPdf = Str::endsWith(strtolower($filename), '.pdf');
                                                @endphp

                                                @if ($isPdf)
                                                    <!-- PDF Button -->
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#receiptPdfModal" data-pdf-url="{{ $url }}">
                                                        View PDF Receipt
                                                    </button>
                                                @else
                                                    <!-- Image Thumbnail -->
                                                    <img src="{{ $url }}" alt="Receipt" style="width: 100px; cursor: pointer;"
                                                        data-bs-toggle="modal" data-bs-target="#receiptImageModal"
                                                        data-img-url="{{ $url }}" />
                                                @endif
                                            </td>
                                            <td>{{ $booking->created_at->format('j M Y, H:iA') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($booking->payment_method === 'gform' && $booking->status == 'pending' && !is_null($booking->resit_path))
                                                            <li>
                                                                <form action="{{ route('organizer.booking.verify', $booking->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button"
                                                                        class="dropdown-item text-success btn-verify-payment"
                                                                        data-booking-code="{{ $booking->booking_code }}">
                                                                        <i class="fas fa-check"></i> Verify Payment
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($booking->payment_method === 'gform' && $booking->status !== 'cancelled')
                                                            <li>
                                                                <form action="{{ route('organizer.booking.cancel', $booking->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger btn-cancel"
                                                                        data-booking-code="{{ $booking->booking_code }}">
                                                                        <i class="fas fa-times"></i> Cancel
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $bookings->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="receiptImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modalReceiptImage" src="" style="width:100%;" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="receiptPdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PDF Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="height: 80vh;">
                    <iframe id="modalReceiptPdf" src="" style="width:100%; height:100%;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>

        $('#example3 tbody').on('click', '.btn-verify-payment', function () {
            const form = $(this).closest('form')[0]; // get the form element
            const bookingCode = $(this).data('booking-code'); // jQuery .data auto converts

            Swal.fire({
                title: 'Are you sure?',
                text: `Mark payment for booking code "${bookingCode}" as confirmed?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#example3 tbody').on('click', '.btn-cancel', function () {
            const form = $(this).closest('form')[0]; // get the form element
            const bookingCode = $(this).data('booking-code'); // jQuery .data auto converts

            Swal.fire({
                title: 'Are you sure?',
                text: `Cancel payment for booking code "${bookingCode}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Image modal
            const imageModal = document.getElementById('receiptImageModal');
            imageModal.addEventListener('show.bs.modal', function (event) {
                const img = event.relatedTarget.getAttribute('data-img-url');
                imageModal.querySelector('#modalReceiptImage').src = img;
            });

            // PDF modal
            const pdfModal = document.getElementById('receiptPdfModal');
            pdfModal.addEventListener('show.bs.modal', function (event) {
                const pdf = event.relatedTarget.getAttribute('data-pdf-url');
                pdfModal.querySelector('#modalReceiptPdf').src = pdf;
            });
        });
    </script>

@endpush