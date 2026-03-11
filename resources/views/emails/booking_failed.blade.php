<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Creation Failed</title>
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
  .error-box { margin-top:20px; padding:14px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; }
  .error-box .title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#b91c1c; margin-bottom:8px; }
  .error-box pre { margin:0; font-size:12px; color:#7f1d1d; white-space:pre-wrap; word-break:break-word; font-family:monospace; line-height:1.5; }
  .context-box { margin-top:16px; padding:14px 16px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; }
  .context-box .title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; margin-bottom:8px; }
  .context-box pre { margin:0; font-size:12px; color:#374151; white-space:pre-wrap; word-break:break-word; font-family:monospace; line-height:1.5; }
  .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; margin:20px 0 8px; }
  .footer { padding:16px 28px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>⚠️ Booking Creation Failed</h1>
    <p>{{ $organizerName }} · {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</p>
  </div>

  <div class="body">

    <div class="section-title">Who Attempted</div>

    <div class="row">
      <div class="lbl">Organizer</div>
      <div class="val"><strong>{{ $organizerName }}</strong></div>
    </div>

    @if(!empty($context['package']))
    <div class="row">
      <div class="lbl">Package</div>
      <div class="val">{{ $context['package'] }}</div>
    </div>
    @endif

    @if(!empty($context['participant_name']))
    <div class="row">
      <div class="lbl">Participant</div>
      <div class="val">{{ $context['participant_name'] }}</div>
    </div>
    @endif

    @if(!empty($context['selected_date']))
    <div class="row">
      <div class="lbl">Date</div>
      <div class="val">{{ $context['selected_date'] }}</div>
    </div>
    @endif

    @if(!empty($context['payment_type']))
    <div class="row">
      <div class="lbl">Payment Type</div>
      <div class="val">{{ ucfirst($context['payment_type']) }}</div>
    </div>
    @endif

    <div class="error-box">
      <div class="title">❌ Error Message</div>
      <pre>{{ $errorMessage }}</pre>
    </div>

    @if(!empty($context))
    <div class="context-box">
      <div class="title">Request Context</div>
      <pre>{{ json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
    @endif

  </div>

  <div class="footer">
    This notification was sent automatically by Sistem Kami when a booking creation failed.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard" style="color:#6b7280;">Open Dashboard</a>
  </div>

</div>
</body>
</html>
