@php
    $slotCount = max($booking->vendorTimeSlots->count(), 1);

    // Total add-ons
    $addonTotal = $booking->addons->sum(function ($addon) {
        return $addon->price * ($addon->pivot->qty ?? 1);
    });

    $slotPrice = ($booking->total_price - $addonTotal) / $slotCount;

    $subtotal = $booking->total_price;
    $discount = $booking->discount ?? 0;
    $serviceCharge = $booking->service_charge ?? 0;
    $grandTotal = $subtotal + $serviceCharge - $discount;

    $paidAmount = $booking->paid_amount ?? 0;

    if ($booking->payment_type === 'deposit') {
        $balance = max($grandTotal - $paidAmount, 0);
    } else {
        // full payment
        $paidAmount = $grandTotal;
        $balance = 0;
    }
@endphp


<style>
body {
    margin: 20px;
    font-family: Arial, sans-serif;
    color: #2e323c;
}

.container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
}

.invoice-header, .invoice-body, .invoice-footer {
    margin-bottom: 20px;
}

.invoice-logo {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2e323c;
    text-decoration: none;
}

.invoice-header .address {
    font-size: 0.7rem;
    color: #9fa8b9;
    text-align: right;
}

.invoice-details {
    margin-top: 10px;
    font-size: 0.8rem;
    padding: 10px;
    background: #f5f6fa;
}

.invoice-num {
    text-align: right;
    font-size: 0.7rem;
}

.table-container {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.custom-table th, .custom-table td {
    border: 1px solid #e6e9f0;
    padding: 8px;
    text-align: left;
	font-size: 0.7rem;
}

.custom-table thead th {
    background: #3736af;
    color: #fff;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.text-success {
    color: #05cd4bff;
}

.text-bold {
	font-weight: bold;
}

.text-muted {
    color: #9fa8b9;
    font-size: 0.8rem;
}

.actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}

.actions a {
    text-decoration: none;
    padding: 6px 12px;
    margin-left: 5px;
    border-radius: 4px;
    color: #fff;
}

.actions a.download { background: #007ae1; }
.actions a.print { background: #6c757d; }

.invoice-footer {
    text-align: center;
    font-size: 0.8rem;
    color: #9fa8b9;
}
</style>

<div class="container">

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
        <!-- HEADER -->
        <tr>
            <td style="vertical-align:top;">
                <a href="https://www.sistemkami.com" class="invoice-logo">
                    www.sistemkami.com
                </a>
            </td>

            <td style="vertical-align:top; text-align:right; font-size:0.7rem; color:#9fa8b9;">
                {{ $booking->organizer->name ?? 'Organizer Name' }}<br>
                {{ $booking->organizer->phone ?? '' }}<br>
                {{ $booking->organizer->address_line1 ?? '' }}<br>
                {{ $booking->organizer->address_line2 ?? '' }}<br>
                {{ $booking->organizer->postal_code ?? '' }},
                {{ $booking->organizer->city ?? '' }},
                {{ $booking->organizer->state ?? '' }}
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f6fa; padding:10px; margin-bottom:10px;">
        <!-- DETAILS -->
        <tr>
            <td style="vertical-align:top; font-size:0.8rem;">
                <strong>Customer:</strong><br>
                {{ $booking->participant->name ?? 'Customer Name' }}<br>
                {{ $booking->participant->phone ?? '' }}
            </td>

            <td style="vertical-align:top; text-align:right; font-size:0.7rem;">
                @if($booking->payment_type === 'deposit')
                    <strong>Receipt (Deposit Payment)</strong><br>
                @else
                    <strong>Receipt (Full Payment)</strong><br>
                @endif

                Ref #{{ $booking->booking_code }}<br>
                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}

                <div style="margin-top:8px;">
                    @if($booking->payment_type === 'deposit')
                        <span style="color:#eab308; font-weight:bold;">
                            PARTIALLY PAID
                        </span>
                    @else
                        <span style="color:#05cd4bff; font-weight:bold;">
                            PAID
                        </span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="invoice-body">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Package</th>
						@if($booking->vendorTimeSlots->first()?->timeSlot?->slot_name)
							<th>Slot</th>
						@endif
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->vendorTimeSlots as $slot)
                        <tr>
                            <td>{{ $booking->package->name ?? '' }}</td>
							@if($slot->timeSlot->slot_name)
                            <td>{{ $slot->timeSlot->slot_name ?? 'Slot Name' }}</td>
							@endif
                            <td>{{ \Carbon\Carbon::parse($slot->booked_date_start)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($slot->booked_time_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('h:i A') }}</td>
                            <td>RM{{ number_format($slotPrice ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    {{-- Booked Add-ons --}}
                    @if($booking->addons && $booking->addons->count())
                        @foreach($booking->addons as $addon)
                            @php
                                $qty = $addon->pivot->qty ?? 1;
                                $addonTotalPrice = $addon->price * $qty;
                            @endphp
                            <tr>
                                <td colspan="2">
                                    {{ $addon->name }} 
                                    @if($qty > 1)
                                        x{{ $qty }}
                                    @endif
                                </td>
                                <td colspan="2">{{ $addon->description }}</td>
                                <td>RM{{ number_format($addonTotalPrice, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
						<td colspan="4">Subtotal</td>
						<td>RM{{ number_format($subtotal, 2) }}</td>
					</tr>
					<tr>
						<td colspan="4">Discount</td>
						<td class="text-success">-RM{{ number_format($discount, 2) }}</td>
					</tr>
					<tr>
						<td colspan="4">Service Charge</td>
						<td>RM{{ number_format($serviceCharge, 2) }}</td>
					</tr>
					<tr>
						<td colspan="4"><strong>Grand Total</strong></td>
						<td class="text-bold"><strong>RM{{ number_format($grandTotal, 2) }}</strong></td>
					</tr>

					<tr>
						<td colspan="4">
                            <strong>
                                @if($booking->payment_type === 'deposit')
                                    Deposit Paid
                                @else
                                    Total Paid
                                @endif
                            </strong>
                        </td>

						<td class="text-bold"><strong>RM{{ number_format($paidAmount, 2) }}</strong></td>
					</tr>

					@if($booking->payment_type === 'deposit' && $balance > 0)
					<tr>
						<td colspan="4"><strong>Remaining Balance</strong></td>
						<td class="text-success"><strong>RM{{ number_format($balance, 2) }}</strong></td>
					</tr>
					@endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="invoice-footer">
        Thank you for using SistemKami.
    </div>
</div>
