@php
    $slotCount = $booking->vendorTimeSlots->count() ?: 1; // avoid division by zero
    $slotPrice = $booking->total_price / $slotCount;
    $subtotal = $booking->total_price;
    $discount = $booking->discount;
    $serviceCharge = $booking->service_charge ?? 0;
    $grandTotal = $subtotal + $serviceCharge - $discount;

    $paidAmount = $booking->paid_amount;
    $balance = 0;

    if($booking->payment_type === 'deposit') {
        $balance = $grandTotal - $paidAmount;
    } else {
        $paidAmount = $grandTotal; // full_payment
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

    <div class="invoice-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <a href="https://www.sistemkami.com" class="invoice-logo">www.sistemkami.com</a>
        <div class="address">
            {{ $booking->organizer->name ?? 'Organizer Name' }}<br>
            {{ $booking->organizer->phone ?? 'Organizer Address' }}<br>
            {{ $booking->organizer->address_line1 ?? '' }}<br>
            {{ $booking->organizer->address_line2 ?? '' }}<br>
            {{ $booking->organizer->postal_code ?? '' }}, {{ $booking->organizer->city ?? '' }},
            {{ $booking->organizer->state ?? '' }}
        </div>
    </div>

    <div class="invoice-details" style="display: flex; justify-content: space-between;">
        <div>
            <strong>Customer:</strong><br>
            {{ $booking->participant->name ?? 'Customer Name' }}<br>
            {{ $booking->participant->phone ?? 'Customer Address' }}
        </div>
        <div class="invoice-num">
            Invoice #{{ $booking->booking_code }}<br>
            {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}
        </div>
    </div>

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
						<td colspan="4"><strong>Paid Amount</strong></td>
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
