@extends('superadmin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="row">

    {{-- ── Stat Cards (Swiper) ─────────────────────────────────────────── --}}
    <div class="col-xl-12">
        <div class="row main-card">
            <div class="swiper mySwiper-counter position-relative overflow-hidden">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="card card-box bg-primary">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p><i class="fa-solid fa-sort-down me-2"></i>Organizers</p>
                                    <h2 class="font-w600 mb-0">{{ number_format($stats['organizers']) }}</h2>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(4,4) scale(0.65)">
                                            <path d="M3 21h18M3 7v1m0 4v1m0 4v1M21 7v1m0 4v1m0 4v1M6 21V3h12v18" fill="none" stroke="#3693ff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart1" class="chart-primary"></div></div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card card-box bg-warning">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p><i class="fa-solid fa-sort-down me-2"></i>Total Bookings</p>
                                    <h2 class="font-w600 mb-0">{{ number_format($stats['bookings']) }}</h2>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(4.8,4.8) scale(0.6)">
                                            <path d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0" fill="none" stroke="#FFAB2D" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart2" class="chart-primary"></div></div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card card-box bg-secondary">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p>Total Revenue</p>
                                    <h2 class="font-w600 mb-0">RM {{ number_format($stats['revenue'], 2) }}</h2>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(3.6,3.6) scale(0.7)">
                                            <line x1="12" y1="1" x2="12" y2="23" fill="none" stroke="#3693ff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" fill="none" stroke="#3693ff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart4" class="chart-primary"></div></div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card card-box bg-dark">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p>Landing Page Visits</p>
                                    <h2 class="font-w600 mb-1">{{ number_format($stats['home_visits']) }}</h2>
                                    <p class="mb-0 small opacity-75">All time: {{ number_format($stats['home_visits_all']) }}</p>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(3.6,3.6) scale(0.7)">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" fill="none" stroke="#5b5e81" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            <polyline points="9 22 9 12 15 12 15 22" fill="none" stroke="#5b5e81" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart5" class="chart-primary"></div></div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card card-box bg-pink">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p>Organizer Profile Visits</p>
                                    <h2 class="font-w600 mb-1">{{ number_format($stats['profile_visits']) }}</h2>
                                    <p class="mb-0 small opacity-75">All time: {{ number_format($stats['profile_visits_all']) }}</p>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(3.6,3.6) scale(0.7)">
                                            <circle cx="12" cy="8" r="4" fill="#ac4cbc"/>
                                            <path d="M6 20c0-3.3137 2.6863-6 6-6s6 2.6863 6 6" fill="none" stroke="#ac4cbc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart6" class="chart-primary"></div></div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card card-box bg-pink">
                            <div class="card-header border-0 pb-0">
                                <div class="chart-num">
                                    <p><i class="fa-solid fa-sort-down me-2"></i>Total Packages</p>
                                    <h2 class="font-w600 mb-0">{{ number_format($stats['packages']) }}</h2>
                                </div>
                                <div class="dlab-swiper-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#ffffff"/>
                                        <g transform="translate(3.6,3.6) scale(0.7)">
                                            <path d="M15 14V17.6C15 18.4401 15 18.8601 14.8365 19.181C14.6927 19.4632 14.4632 19.6927 14.181 19.8365C13.8601 20 13.4401 20 12.6 20H7.40001C6.55994 20 6.1399 20 5.81903 19.8365C5.53679 19.6927 5.30731 19.4632 5.1635 19.181C5.00001 18.8601 5.00001 18.4401 5.00001 17.6V10M19 10V20M5.00001 16H15M5.55778 4.88446L3.5789 8.84223C3.38722 9.22559 3.29138 9.41727 3.3144 9.57308C3.3345 9.70914 3.40976 9.8309 3.52246 9.90973C3.65153 10 3.86583 10 4.29444 10H19.7056C20.1342 10 20.3485 10 20.4776 9.90973C20.5903 9.8309 20.6655 9.70914 20.6856 9.57308C20.7086 9.41727 20.6128 9.22559 20.4211 8.84223L18.4422 4.88446C18.2817 4.5634 18.2014 4.40287 18.0817 4.28558C17.9758 4.18187 17.8482 4.10299 17.7081 4.05465C17.5496 4 17.3701 4 17.0112 4H6.98887C6.62991 4 6.45043 4 6.29198 4.05465C6.15185 4.10299 6.02422 4.18187 5.91833 4.28558C5.79858 4.40287 5.71832 4.5634 5.55778 4.88446Z" fill="none" stroke="#ac4cbc" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body p-0"><div id="widgetChart3" class="chart-primary"></div></div>
                        </div>
                    </div>

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
                            <th></th>
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
                            <td style="width:44px">
                                <img src="{{ $org->logo_url }}" alt=""
                                     style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                            </td>
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
                        <tr><td colspan="6" class="text-center text-muted py-3">No organizers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/swiper/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>
<script>
(function () {
    function sparkline(id, color, data) {
        var el = document.querySelector('#' + id);
        if (!el) return;
        new ApexCharts(el, {
            series: [{ name: 'Value', data: data }],
            chart: { type: 'line', height: 70, width: 500, toolbar: { show: false }, zoom: { enabled: false }, sparkline: { enabled: true } },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { show: true, width: 6, curve: 'smooth', colors: [color] },
            grid: { show: false, padding: { top: 0, right: 0, bottom: 0, left: -1 } },
            states: { normal: { filter: { type: 'none' } }, hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } },
            xaxis: { axisBorder: { show: false }, axisTicks: { show: false }, labels: { show: false }, crosshairs: { show: false }, tooltip: { enabled: false } },
            yaxis: { show: false },
            tooltip: { enabled: false },
        }).render();
    }

    window.addEventListener('load', function () {
        sparkline('widgetChart1', 'rgba(163, 199, 241, 1)', [200, 310, 50, 250, 50, 300, 100, 200, 100, 400]);
        sparkline('widgetChart2', 'rgba(148, 150, 176, 1)', [200, 300, 200, 250, 200, 240, 180, 230, 200, 250, 200]);
        sparkline('widgetChart3', 'rgba(247, 215, 168, 1)', [100, 300, 200, 250, 200, 240, 180, 230, 200, 250, 300]);
        sparkline('widgetChart4', 'rgba(229, 159, 241, 1)', [200, 310, 50, 250, 50, 300, 100, 200]);
        sparkline('widgetChart5', 'rgba(148, 150, 176, 1)', [200, 310, 50, 250, 50, 300, 100, 200, 100, 400]);
        sparkline('widgetChart6', 'rgba(229, 159, 241, 1)', [200, 310, 50, 250, 50, 300, 100, 200]);

        if (document.querySelector('.mySwiper-counter')) {
            new Swiper('.mySwiper-counter', {
                speed: 1500,
                slidesPerView: 4,
                spaceBetween: 40,
                parallax: true,
                loop: false,
                autoplay: { delay: 5000 },
                breakpoints: {
                    300:  { slidesPerView: 1.2, spaceBetween: 30 },
                    480:  { slidesPerView: 2.2, spaceBetween: 30 },
                    768:  { slidesPerView: 2.2, spaceBetween: 30 },
                    991:  { slidesPerView: 2.3, spaceBetween: 30 },
                    1200: { slidesPerView: 3.3, spaceBetween: 30 },
                    1500: { slidesPerView: 4.2, spaceBetween: 30 },
                },
            });
        }
    });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
(function () {
    const labels   = @json($chartLabels->values());
    const bookings = @json($chartBookings->values());
    const revenue  = @json($chartRevenue->values());

    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        plugins: [ChartDataLabels],
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
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'rgba(108, 99, 255, 1)',
                        font: { size: 11, weight: 'bold' },
                        formatter: v => v > 0 ? v : '',
                    },
                },
                {
                    label: 'Revenue (RM)',
                    data: revenue,
                    backgroundColor: 'rgba(22, 196, 127, 0.75)',
                    borderColor: 'rgba(22, 196, 127, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    yAxisID: 'yRevenue',
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'rgba(22, 196, 127, 1)',
                        font: { size: 10, weight: 'bold' },
                        formatter: v => v > 0 ? 'RM ' + Number(v).toLocaleString('en-MY', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) : '',
                    },
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            layout: { padding: { top: 24 } },
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
                },
                datalabels: {},
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
