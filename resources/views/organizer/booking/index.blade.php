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
                        <h4 class="card-title">Bookings List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example3" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Code</th>
                                        <th>Event</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $index => $booking)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $booking->booking_code }}</td>
                                            <td>{{ $booking->event->title }}</td>
                                            <td>{{ $booking->participant->name }}</td>
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
                                                <!-- Verify payment button (only if payment_method is gform and not completed yet) -->
                                                @if($booking->payment_method === 'gform' && $booking->status == 'pending' && !is_null($booking->resit_path))
                                                    <form action="{{ route('organizer.booking.verify', $booking->id) }}"
                                                        method="POST" class="verify-payment-form d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button"
                                                            class="btn btn-success shadow btn-xs sharp btn-verify-payment"
                                                            data-booking-code="{{ $booking->booking_code }}">
                                                            <i class="fas fa-check"></i><span style="padding-left: 5px;">Verify Payment</span>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- <div class="d-flex">
                                                    <a href="{{ route('organizer.booking.edit', $booking->id) }}"
                                                        class="btn btn-primary shadow btn-xs sharp me-1"
                                                        data-id="{{ $booking->id }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </div> -->

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            const verifyButtons = document.querySelectorAll('.btn-verify-payment');

            verifyButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const form = this.closest('form');
                    const bookingCode = this.getAttribute('data-booking-code');

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
            });
        });

        @if(session('success'))
                    < script >
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
            </script>
        @endif

    </script>
@endpush