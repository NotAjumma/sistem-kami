@extends('layouts.admin.default')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Commission Report</h4>
            <a href="?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}" class="btn btn-light border btn-sm">Export Excel</a>
        </div>
        <div class="card-body border-bottom py-2">
            <form method="GET" id="filterForm" class="row g-2">
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" />
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" />
                </div>
                <div class="col-md-2">
                    <select name="package_id" class="form-control">
                        <option value="">All packages</option>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}" {{ request('package_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('commission.report') }}" class="btn btn-outline-info">Clear</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Package</th>
                            <th>Addons</th>
                            <th>Total</th>
                            @foreach($workers as $w)
                                <th>{{ $w->name }}</th>
                            @endforeach
                            <th>Promoter Commission</th>
                            <th>Company Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report as $r)
                            <tr>
                                <td>{{ $r['id'] }}</td>
                                <td>{{ $r['created_at'] }}</td>
                                <td>{{ $r['package_name'] }}</td>
                                <td>
                                    @if(!empty($r['addons']))
                                        <ul>
                                            @foreach($r['addons'] as $a)
                                                <li>{{ $a['name'] }} (RM{{ number_format($a['total_price'],2) }})</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>RM{{ number_format($r['amount'],2) }}</td>

                                @foreach($workers as $w)
                                    @php
                                        $wb = collect($r['worker_breakdown'])->firstWhere('worker_id', $w->id);
                                        $commission = $wb['commission'] ?? 0;
                                    @endphp
                                    <td>RM{{ number_format($commission,2) }}</td>
                                @endforeach

                                <td>RM{{ number_format($r['promoter_commission'],2) }}</td>
                                <td>RM{{ number_format($r['company_net'],2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="text-success">
                            <th colspan="4">Total Commission</th>
                            <th>RM{{ number_format($totalRevenue,2) }}</th>
                            @foreach($workers as $w)
                                <th>RM{{ number_format($workerTotals[$w->id]['commission'] ?? 0,2) }}</th>
                            @endforeach
                            <th>RM{{ number_format($totalPromoterCommission,2) }}</th>
                            <th>RM{{ number_format($totalCompanyNet,2) }}</th>
                        </tr>

                        @php $totalPayouts = collect($workerTotals)->sum('payout'); @endphp
                        <tr class="text-danger">
                            <th colspan="4" style="white-space: nowrap;">Worker Payouts</th>
                            <th style="white-space: nowrap;">-RM{{ number_format($totalPayouts, 2) }}</th>
                            @foreach($workers as $w)
                                <th style="white-space: nowrap;">
                                    {{ isset($workerTotals[$w->id]['payout']) ? '-RM' . number_format($workerTotals[$w->id]['payout'], 2) : '-' }}
                                </th>
                            @endforeach
                            <th style="white-space: nowrap;">-</th>
                            <th style="white-space: nowrap;">-</th>
                        </tr>

                        <tr>
                            <th colspan="4">Final Total</th>
                            <th>RM{{ number_format($totalRevenue - $totalPayouts, 2) }}</th>
                            @foreach($workers as $w)
                                <th>RM{{ number_format(($workerTotals[$w->id]['commission'] ?? 0) - ($workerTotals[$w->id]['payout'] ?? 0),2) }}</th>
                            @endforeach
                            <th>RM{{ number_format($totalPromoterCommission,2) }}</th>
                            <th>RM{{ number_format($totalCompanyNet,2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
