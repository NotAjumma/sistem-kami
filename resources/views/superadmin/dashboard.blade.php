@extends('superadmin.layout')
@section('title', 'Dashboard')
@section('content')

<div class="row">

    {{-- ── Stat Cards ────────────────────────────────────────────────── --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-primary bg-opacity-10">
                    <i class="material-symbols-outlined text-primary">business</i>
                </div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['organizers']) }}</div>
                    <div class="text-muted small">Organizers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-success bg-opacity-10">
                    <i class="material-symbols-outlined text-success">calendar_month</i>
                </div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['bookings']) }}</div>
                    <div class="text-muted small">Total Bookings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-info bg-opacity-10">
                    <i class="material-symbols-outlined text-info">table_chart</i>
                </div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['packages']) }}</div>
                    <div class="text-muted small">Packages</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-warning bg-opacity-10">
                    <i class="material-symbols-outlined text-warning">payments</i>
                </div>
                <div>
                    <div class="fs-4 fw-bold">RM {{ number_format($stats['revenue'], 2) }}</div>
                    <div class="text-muted small">Total Revenue (Paid + Upcoming)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Chart: Organizer Bookings & Revenue ───────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Organizer Performance — Bookings & Revenue (Active Only)</h6>
                <span class="text-muted small">Sorted by highest bookings</span>
            </div>
            <div class="card-body">
                <canvas id="salesChart" style="height:320px;max-height:320px;"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Recent Organizers ──────────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Recent Organizers</h6>
                <a href="{{ route('superadmin.organizers') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrganizers as $org)
                        <tr>
                            <td>{{ $org->name }}</td>
                            <td>{{ $org->email ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $org->is_active ? 'success' : 'secondary' }}">
                                    {{ $org->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $org->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('superadmin.organizer.detail', $org->id) }}"
                                   class="btn btn-xs btn-outline-secondary btn-sm">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No organizers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels   = @json($chartLabels->values());
    const bookings = @json($chartBookings->values());
    const revenue  = @json($chartRevenue->values());

    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Total Bookings',
                    data: bookings,
                    backgroundColor: 'rgba(108, 99, 255, 0.75)',
                    borderColor: 'rgba(108, 99, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'yBookings',
                },
                {
                    label: 'Revenue (RM)',
                    data: revenue,
                    backgroundColor: 'rgba(22, 196, 127, 0.75)',
                    borderColor: 'rgba(22, 196, 127, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'yRevenue',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            if (ctx.dataset.label === 'Revenue (RM)') {
                                return ' Revenue: RM ' + Number(ctx.parsed.y).toLocaleString('en-MY', { minimumFractionDigits: 2 });
                            }
                            return ' Bookings: ' + ctx.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 35,
                        minRotation: 0,
                        font: { size: 11 },
                    }
                },
                yBookings: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 },
                    title: { display: true, text: 'Bookings' },
                },
                yRevenue: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Revenue (RM)' },
                    ticks: {
                        callback: v => 'RM ' + v.toLocaleString('en-MY')
                    }
                },
            },
        },
    });
})();
</script>
@endpush
