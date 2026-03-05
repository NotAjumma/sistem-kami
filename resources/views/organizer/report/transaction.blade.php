@extends('layouts.admin.default')

@section('content')
<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card">
<div class="card-header">
<h4 class="card-title">{{ $page_title }}</h4>
</div>

<div class="card-body">

    <div class="d-flex justify-content-between mb-3 align-items-center">

        <form method="GET" id="filterForm" class="d-flex gap-2 flex-wrap">
            <select name="type" onchange="filterForm.submit()" class="form-select" style="width:200px;">
                <option value="">All Types</option>
                <option value="booking_payment" {{ request('type')=='booking_payment'?'selected':'' }}>Booking Payment</option>
                <option value="withdrawal" {{ request('type')=='withdrawal'?'selected':'' }}>Withdrawal</option>
                <option value="refund" {{ request('type')=='refund'?'selected':'' }}>Refund</option>
                <option value="worker_payout" {{ request('type')=='worker_payout'?'selected':'' }}>Worker Payout</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Booking code..." style="width:250px;">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-outline-info">Clear</a>
        </form>

        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                Withdraw / Payout
            </button>
        </div>

    </div>
<div class="table-responsive">

<table id="example3" class="display min-w850">

<thead>
<tr>
<th>#</th>
<th>Trx ID</th>
<th>Date</th>
<th>Booking Code</th>
<th>Type</th>
<th>Amount</th>
<th>Description</th>
<th>Balance After</th>
</tr>
</thead>

<tbody>

@foreach ($transactions as $index => $trx)

<tr>

<td>{{ $transactions->firstItem() + $index }}</td>
<td>TRX-{{ $trx->id }}</td>

<td>{{ $trx->created_at->format('j M Y, H:iA') }}</td>

<td>
@if($trx->reference)
{{ $trx->reference->booking_code }}
@else
-
@endif
</td>

<td>
@php
$typeColor = match($trx->type){
'booking_payment' => 'success',
'withdrawal' => 'danger',
'income' => 'secondary',
default => 'danger'
};
@endphp

<span class="badge bg-{{ $typeColor }}">
{{ ucfirst(str_replace('_',' ',$trx->type)) }}
</span>

</td>

<td>

@if($trx->amount > 0)
    <span class="text-success fw-bold">
        + RM{{ number_format($trx->amount,2) }}
    </span>
@else
    <span class="text-danger fw-bold">
        - RM{{ number_format(abs($trx->amount),2) }}
    </span>
@endif

</td>

<td>{{ $trx->description }}</td>

<td>
<strong>
RM{{ number_format($trx->balance_after,2) }}
</strong>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

{{ $transactions->withQueryString()->links('vendor.pagination.custom') }}

</div>
</div>

</div>
</div>
<!-- Withdraw / Payout Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('organizer.withdraw.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="withdrawModalLabel">Withdraw / Worker Payout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-select" id="transactionType" required>
                    <option value="withdrawal">Withdraw (Organizer)</option>
                    <option value="worker_payout">Worker Payout</option>
                </select>
            </div>

            <div class="mb-3" id="workerSelectContainer" style="display:none;">
                <label>Worker</label>
                <select name="worker_id" class="form-select">
                    <option value="">Select Worker</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>

            <!-- <div class="mb-3">
                <label>Booking Reference (optional for worker payout)</label>
                <input type="text" name="reference_booking" class="form-control" placeholder="Booking code">
            </div> -->

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Optional description"></textarea>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('transactionType');
    const workerContainer = document.getElementById('workerSelectContainer');

    function toggleWorker() {
        if(typeSelect.value === 'worker_payout') {
            workerContainer.style.display = 'block';
        } else {
            workerContainer.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleWorker);

    // initialize on page load
    toggleWorker();
});
</script>
@endpush