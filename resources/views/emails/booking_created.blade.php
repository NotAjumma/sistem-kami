<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Booking</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:24px; color:#333; }
  .wrap { max-width:600px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
  .header { padding:24px 28px; background:#2563eb; color:#fff; }
  .header h1 { margin:0 0 4px; font-size:20px; }
  .header p  { margin:0; opacity:.85; font-size:13px; }
  .body { padding:24px 28px; }
  .row { display:flex; padding:9px 0; border-bottom:1px solid #f3f4f6; font-size:13px; }
  .row:last-child { border-bottom:none; }
  .lbl { width:140px; flex-shrink:0; color:#6b7280; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.04em; padding-top:1px; }
  .val { flex:1; color:#111827; }
  .badge { display:inline-block; padding:2px 10px; border-radius:10px; font-size:11px; font-weight:600; }
  .badge-pending   { background:#fef3c7; color:#92400e; }
  .badge-confirmed { background:#dbeafe; color:#1e40af; }
  .badge-paid      { background:#dcfce7; color:#15803d; }
  .badge-deposit   { background:#ede9fe; color:#6d28d9; }
  .badge-full      { background:#dcfce7; color:#15803d; }
  .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; margin:20px 0 8px; }
  .footer { padding:16px 28px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>🆕 New Booking Created</h1>
    <p>{{ $booking->organizer->name ?? '-' }} · {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</p>
  </div>

  <div class="body">

    <div class="section-title">Booking Details</div>

    <div class="row">
      <div class="lbl">Booking Code</div>
      <div class="val"><strong>{{ $booking->booking_code }}</strong></div>
    </div>
    <div class="row">
      <div class="lbl">Status</div>
      <div class="val">
        <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
      </div>
    </div>
    <div class="row">
      <div class="lbl">Payment Type</div>
      <div class="val">
        @if($booking->payment_type === 'deposit')
          <span class="badge badge-deposit">Deposit</span>
        @else
          <span class="badge badge-full">Full Payment</span>
        @endif
      </div>
    </div>
    <div class="row">
      <div class="lbl">Payment Method</div>
      <div class="val">{{ ucfirst($booking->payment_method ?? '-') }}</div>
    </div>
    <div class="row">
      <div class="lbl">Amount Paid</div>
      <div class="val">
        @if(($booking->paid_amount ?? 0) > 0)
          <strong>RM{{ number_format($booking->paid_amount, 2) }}</strong>
          @if($booking->payment_type === 'deposit')
            <span style="color:#6d28d9;font-size:11px;margin-left:6px;font-weight:600;">DEPOSIT</span>
          @endif
        @else
          <span style="color:#9ca3af;">-</span>
        @endif
      </div>
    </div>
    @if($booking->payment_type === 'deposit')
    <div class="row">
      <div class="lbl">Total Price</div>
      <div class="val">RM{{ number_format($booking->final_price ?? $booking->total_price ?? 0, 2) }}</div>
    </div>
    <div class="row">
      <div class="lbl">Balance Due</div>
      <div class="val"><strong style="color:#dc2626;">RM{{ number_format($booking->balance, 2) }}</strong></div>
    </div>
    @endif

    <div class="section-title">Package & Slot</div>

    <div class="row">
      <div class="lbl">Package</div>
      <div class="val">{{ $booking->package->name ?? '-' }}</div>
    </div>
    @if($booking->addons->isNotEmpty())
    <div class="row">
      <div class="lbl">Add-ons</div>
      <div class="val">{{ $booking->addons->pluck('name')->implode(', ') }}</div>
    </div>
    @endif
    @if($booking->vendorTimeSlots->isNotEmpty())
      @foreach($booking->vendorTimeSlots as $i => $slot)
      <div class="row">
        <div class="lbl">{{ $booking->vendorTimeSlots->count() > 1 ? 'Slot ' . ($i + 1) : 'Slot' }}</div>
        <div class="val">
          @if($slot->vendorTimeSlot?->slot_name)<strong>{{ $slot->vendorTimeSlot->slot_name }}</strong> · @endif
          {{ \Carbon\Carbon::parse($slot->booked_date_start)->format('d M Y') }}
          @if($slot->booked_time_start)
            · {{ \Carbon\Carbon::parse($slot->booked_time_start)->format('h:i A') }}@if($slot->booked_time_end) – {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('h:i A') }}@endif
          @endif
        </div>
      </div>
      @endforeach
    @endif

    <div class="section-title">Participant</div>

    <div class="row">
      <div class="lbl">Name</div>
      <div class="val">{{ $booking->participant->name ?? '-' }}</div>
    </div>
    <div class="row">
      <div class="lbl">Phone / WA</div>
      <div class="val">{{ $booking->participant->whatsapp_number ?: ($booking->participant->phone ?? '-') }}</div>
    </div>
    @php
      $participantEmail = $booking->participant->email ?? null;
      $organizerEmail   = $booking->organizer->email ?? null;
    @endphp
    @if($participantEmail && $participantEmail !== $organizerEmail)
    <div class="row">
      <div class="lbl">Email</div>
      <div class="val">{{ $participantEmail }}</div>
    </div>
    @endif

  </div>

  <div class="footer">
    This notification was sent automatically by Sistem Kami when a new booking was created.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard" style="color:#6b7280;">Open Dashboard</a>
  </div>

</div>
</body>
</html>
