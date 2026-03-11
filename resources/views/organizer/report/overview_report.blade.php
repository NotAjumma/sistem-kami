@extends('layouts.admin.default')
@section('content')
<div class="container-fluid">

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Overview</h4>
        </div>
        <div class="card-body py-2">
            <form method="GET" id="overviewFilterForm" class="row g-2">
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" />
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" />
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('organizer.business.report.overview') }}" class="btn btn-outline-info">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Income overview --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white"><strong>Income Summary</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr style="background-color:#d1e7dd; color:#0a3622;">
                                <td><i class="bi bi-check-circle-fill me-1"></i> Full Payment Received</td>
                                <td class="text-end fw-bold">RM{{ number_format($paidIncome, 2) }}</td>
                            </tr>
                            <tr style="background-color:#cfe2ff; color:#052c65;">
                                <td><i class="bi bi-clock-fill me-1"></i> Deposit Received</td>
                                <td class="text-end fw-bold">RM{{ number_format($depositPaid, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="background-color:#0a3622 !important; color:#fff !important; font-size:1.05rem;">Total Income Received</td>
                                <td class="text-end fw-bold" style="background-color:#0a3622 !important; color:#fff !important; font-size:1.05rem;">RM{{ number_format($totalReceived, 2) }}</td>
                            </tr>
                            <tr style="background-color:#fff3cd; color:#664d03;">
                                <td><i class="bi bi-hourglass-split me-1"></i> Pending Balance (Deposit Customers)</td>
                                <td class="text-end fw-bold">RM{{ number_format($pendingIncome, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header bg-dark text-white"><strong>Income by Package &amp; Addon</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Package / Addon</th>
                                <th class="text-center">Bookings / Qty</th>
                                <th class="text-end">Income (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-secondary"><td colspan="3" class="fw-bold small text-uppercase text-muted">Packages</td></tr>
                            @forelse($packageBreakdown as $pkg)
                                <tr style="background-color:#e9ecef;">
                                    <td class="fw-bold">{{ $pkg['name'] }}</td>
                                    <td class="text-center fw-bold">{{ $pkg['bookings'] }} bookings</td>
                                    <td class="text-end fw-bold">RM{{ number_format($pkg['income'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                            @endforelse
                            @if(count($addonBreakdown))
                                <tr class="table-secondary"><td colspan="3" class="fw-bold small text-uppercase text-muted">Add-ons</td></tr>
                                @foreach($addonBreakdown as $name => $addon)
                                    <tr>
                                        <td class="ps-3"><i class="bi bi-plus-circle me-1 text-primary"></i>{{ $name }}</td>
                                        <td class="text-center">{{ $addon['qty'] }}x</td>
                                        <td class="text-end">RM{{ number_format($addon['income'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 col-lg-12">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 d-block pb-0">
                            <h2 class="card-title mb-3">Package Chart</h2>
                        </div>

                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div style="position: relative; height: 400px;">
                                <canvas id="package-horizontal-bar-chart"></canvas>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 d-block pb-0">
                            <h2 class="card-title mb-3">Slot Chart</h2>
                        </div>

                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="slot-chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div id="slot-horizontal-bar-chart"
                                class="ct-chart"
                                style="height:400px; display:none;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-12">
            <div class="row">
                <div class="col-xl-12 col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h2 class="card-title mb-2">Add On Chart</h2>
                        </div>
                        <div class="card-body position-relative">

                            <!-- Spinner -->
                            <div id="addon-chart-spinner" class="text-center py-5">
                                <div class="spinner-border text-primary"></div>
                                <div class="mt-2">Loading data...</div>
                            </div>

                            <!-- Chart -->
                            <div id="addon-horizontal-bar-chart"
                                class="ct-chart"
                                style="height:400px; display:none;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush