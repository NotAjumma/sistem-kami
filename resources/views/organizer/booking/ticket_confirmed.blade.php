@extends('layouts.admin.default')
@push('styles')
    <style>
        .btn-verify-payment {
            gap: 5px;
            width: auto !important;
            padding: 0 10px !important;
        }
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
                                        <th>Ticket Code</th>
                                        <th>Event</th>
                                        <th>Name</th>
                                        <th>No IC</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Ticket Status</th>
                                        <th>Booking Status</th>
                                        <th>Booking Final Price</th>
                                        <th>Receipt</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookingTickets as $index => $eachBookingTicket)
                                        <tr>
                                            <td>{{ $bookingTickets->firstItem() + $index }}</td>
                                            <td>{{ $eachBookingTicket->booking->booking_code }}</td>
                                            <td>{{ $eachBookingTicket->ticket_code }}</td>
                                            <td>{{ $eachBookingTicket->ticket->event?->title ?? '-' }}</td>
                                            <td>{{ $eachBookingTicket->participant_name }}</td>
                                            <td>{{ $eachBookingTicket->participant_no_ic }}</td>
                                            <td>{{ $eachBookingTicket->participant_email }}</td>
                                            <td>{{ $eachBookingTicket->participant_phone }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match ($eachBookingTicket->status) {
                                                        'printed', 'success' => 'success',
                                                        'checkin' => 'primary',
                                                        'pending' => 'warning',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($eachBookingTicket->status) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-success">{{ ucfirst($eachBookingTicket->booking->status) }}</span>
                                            </td>
                                            <td>RM{{ number_format($eachBookingTicket->booking->final_price, 2) }}</td>
                                            <td>
                                                @php
                                                    $filename = $eachBookingTicket->booking->resit_path;
                                                    $encoded = rawurlencode($filename);
                                                    $url = asset('images/receipts/' . $encoded);
                                                    $isPdf = Str::endsWith(strtolower($filename), '.pdf');
                                                @endphp

                                                @if ($isPdf)
                                                    <button class="btn btn-primary btn-xs" data-bs-toggle="modal"
                                                        data-bs-target="#receiptPdfModal" data-pdf-url="{{ $url }}">
                                                        View PDF Receipt
                                                    </button>
                                                @else
                                                    <img src="{{ $url }}" alt="Receipt" style="width: 100px; cursor: pointer;"
                                                        data-bs-toggle="modal" data-bs-target="#receiptImageModal"
                                                        data-img-url="{{ $url }}" />
                                                @endif
                                            </td>
                                            <td>
                                                @if($eachBookingTicket->booking->payment_method === 'gform' && !is_null($eachBookingTicket->booking->resit_path) && $eachBookingTicket->status !== 'checkin')
                                                    <form action="{{ route('organizer.ticket.checkin', $eachBookingTicket->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button"
                                                            class="btn btn-secondary shadow btn-xs sharp btn-verify-payment"
                                                            data-ticket-code="{{ $eachBookingTicket->ticket_code }}">
                                                            <span style="padding-left: 5px;">Check In</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{ $bookingTickets->withQueryString()->links('vendor.pagination.custom') }}
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
        // document.addEventListener('DOMContentLoaded', function () {
        //     const verifyButtons = document.querySelectorAll('.btn-verify-payment');

        //     verifyButtons.forEach(button => {
        //         button.addEventListener('click', function (e) {
        //             const form = this.closest('form');
        //             const ticketCode = this.getAttribute('data-ticket-code');
        //             console.log('Form submitting for ticket:', ticketCode);
        //             Swal.fire({
        //                 title: 'Are you sure?',
        //                 text: `Mark ticket with code "${ticketCode}" as checked in?`,
        //                 icon: 'warning',
        //                 showCancelButton: true,
        //                 confirmButtonColor: '#28a745',
        //                 cancelButtonColor: '#d33',
        //                 confirmButtonText: 'Yes!'
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     form.submit();
        //                 }
        //             });
        //         });
        //     });
        // });

        $('#example3').on('click', '.btn-verify-payment', function () {
            const button = $(this);
            const form = button.closest('form')[0];
            const ticketCode = button.data('ticket-code');

            Swal.fire({
                title: 'Are you sure?',
                text: `Mark ticket with code "${ticketCode}" as checked in?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
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