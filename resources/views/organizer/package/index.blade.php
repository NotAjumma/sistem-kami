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
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>

                            <select name="category_search" onchange="document.getElementById('filterForm').submit()"
                                class="form-select" style="width: 250px;">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category_search') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>


                            {{-- Search input --}}
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search package..." style="width: 250px;">

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
                                        <th>Category</th>
                                        <th>Package</th>
                                        <th>Base Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                        <th>Deposit</th>
                                        <th>Last Paid</th>
                                        <th>Status</th>
                                        <th>Date/Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($packages as $index => $package)
                                        <tr >
                                            <td>{{ $packages->firstItem() + $index }}</td>
                                            <td>{{ $package->category->name }}</td>
                                            <td>{{ $package->name }}</td>
                                            <td>RM{{ $package->base_price }}</td>
                                            <td>
                                                @if ($package->discount_percentage)
                                                {{ $package->discount_percentage  }}%
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>RM{{ $package->final_price }}</td>
                                            <td>
                                                @if ($package->deposit_percentage)
                                                {{ (int) $package->deposit_percentage }}%
                                                @elseif($package->deposit_fixed)
                                                RM{{ (int) $package->deposit_fixed }}
                                                @else
                                                -
                                                @endif
                                                
                                            </td>
                                            <td>{{ $package->last_paid_date }}</td>

                                            <td>
                                                @php
                                                    $statusClass = match ($package->status) {
                                                        'pending' => 'warning',
                                                        'active' => 'success',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary',
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst($package->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $package->created_at->format('j M Y, H:iA') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($package->payment_method === 'gform' && $package->status == 'pending' && !is_null($package->resit_path))
                                                            <li>
                                                                <form action="{{ route('organizer.booking.verify', $package->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button"
                                                                        class="dropdown-item text-success btn-verify-payment"
                                                                        data-booking-code="{{ $package->booking_code }}">
                                                                        <i class="fas fa-check"></i> Verify Payment
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($package->payment_method === 'gform' && $package->status !== 'cancelled')
                                                            <li>
                                                                <form action="{{ route('organizer.booking.cancel', $package->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger btn-cancel"
                                                                        data-booking-code="{{ $package->booking_code }}">
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
                        {{ $packages->withQueryString()->links('vendor.pagination.custom') }}
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