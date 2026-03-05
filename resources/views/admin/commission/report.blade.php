@extends('layouts.admin.default')

@section('content')
<div class="container-fluid">
    <h3>Commission Report</h3>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-2">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}" />
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}" />
        </div>
        <!-- <div class="col-md-2">
            <select name="worker_id" class="form-control">
                <option value="">All workers</option>
                @foreach($workers as $w)
                    <option value="{{ $w->id }}" {{ request('worker_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                @endforeach
            </select>
        </div> -->
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
        </div>
        <div class="col-md-2">
            <a href="?{{ http_build_query(array_merge(request()->all(), ['export'=>'excel'])) }}" class="btn btn-outline-secondary">Export Excel</a>
        </div>
    </form>

    <div class="card">
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
                        <tr>
                            <th colspan="4">Total</th>
                            <th>RM{{ number_format($totalRevenue,2) }}</th>

                            @foreach($workers as $w)
                                @php $key = $w->id ?? 'organizer'; @endphp
                                <th>RM{{ number_format($workerTotals[$key],2) }}</th>
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
