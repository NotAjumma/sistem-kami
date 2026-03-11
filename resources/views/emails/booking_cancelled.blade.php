<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Cancelled</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:24px; color:#333; }
  .wrap { max-width:600px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
  .header { padding:24px 28px; background:#dc2626; color:#fff; }
  .header h1 { margin:0 0 4px; font-size:20px; }
  .header p  { margin:0; opacity:.85; font-size:13px; }
  .body { padding:24px 28px; }
  .row { display:flex; padding:9px 0; border-bottom:1px solid #f3f4f6; font-size:13px; }
  .row:last-child { border-bottom:none; }
  .lbl { width:140px; flex-shrink:0; color:#6b7280; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.04em; padding-top:1px; }
  .val { flex:1; color:#111827; }
  .refund-box { margin-top:20px; padding:14px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; }
  .refund-box .title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#b91c1c; margin-bottom:6px; }
  .refund-box .amount { font-size:22px; font-weight:700; color:#dc2626; }
  .no-refund-box { margin-top:20px; padding:12px 16px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; color:#6b7280; }
  .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; margin:20px 0 8px; }
  .footer { padding:16px 28px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>❌ Booking Cancelled</h1>
    <p>{{ $booking->organizer->name ?? '-' }} · {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</p>
  </div>

  <div class="body">

    <div class="section-title">Booking Details</div>

    <div class="row">
      <div class="lbl">Booking Code</div>
      <div class="val"><strong>{{ $booking->booking_code }}</strong></div>
    </div>
    <div class="row">
      <div class="lbl">Original Status</div>
      <div class="val" style="color:#6b7280;">Was {{ ucfirst($booking->getOriginal('status') ?? 'active') }} → <strong style="color:#dc2626;">Cancelled</strong></div>
    </div>
    <div class="row">
      <div class="lbl">Payment Type</div>
      <div class="val">{{ $booking->payment_type === 'deposit' ? 'Deposit' : 'Full Payment' }}</div>
    </div>
    <div class="row">
      <div class="lbl">Originally Paid</div>
      <div class="val">RM{{ number_format($booking->paid_amount ?? 0, 2) }}</div>
    </div>

    @if($refundAmount > 0)
    <div class="refund-box">
      <div class="title">💸 Wallet Refund Issued</div>
      <div class="amount">RM{{ number_format($refundAmount, 2) }}</div>
      <div style="font-size:12px;color:#b91c1c;margin-top:4px;">Deducted from organizer wallet balance</div>
    </div>
    @else
    <div class="no-refund-box">No refund issued (booking had no payment recorded).</div>
    @endif

    <div class="section-title" style="margin-top:24px;">Package & Slot</div>

    <div class="row">
      <div class="lbl">Package</div>
      <div class="val">{{ $booking->package->name ?? '-' }}</div>
    </div>
    @php $slot = $booking->vendorTimeSlots->first(); @endphp
    @if($slot)
    <div class="row">
      <div class="lbl">Date</div>
      <div class="val">{{ \Carbon\Carbon::parse($slot->booked_date_start)->format('d M Y') }}</div>
    </div>
    <div class="row">
      <div class="lbl">Time</div>
      <div class="val">
        {{ \Carbon\Carbon::parse($slot->booked_time_start)->format('h:i A') }}
        @if($slot->booked_time_end) – {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('h:i A') }} @endif
      </div>
    </div>
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

  </div>

  <div class="footer">
    This notification was sent automatically by Sistem Kami when a booking was cancelled.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard" style="color:#6b7280;">Open Dashboard</a>
  </div>

</div>
</body>
</html>
