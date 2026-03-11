<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reminder Report</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:24px; color:#333; }
  .wrap { max-width:680px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
  .header { padding:28px 32px; background:{{ $report['total_failed'] > 0 ? '#d97706' : '#16a34a' }}; color:#fff; }
  .header h1 { margin:0 0 6px; font-size:22px; }
  .header p  { margin:0; opacity:.9; font-size:14px; }
  .body { padding:28px 32px; }
  .stats { display:flex; gap:16px; margin-bottom:24px; }
  .stat-box { flex:1; padding:14px 18px; border-radius:8px; text-align:center; }
  .stat-box .num { font-size:28px; font-weight:700; line-height:1; }
  .stat-box .lbl { font-size:12px; margin-top:4px; opacity:.8; }
  .stat-sent   { background:#dcfce7; color:#15803d; }
  .stat-failed { background:#fee2e2; color:#b91c1c; }
  .stat-skip   { background:#f3f4f6; color:#6b7280; }
  .group-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
    color:#6b7280; margin:20px 0 6px; padding-bottom:4px; border-bottom:1px solid #e5e7eb; }
  table { width:100%; border-collapse:collapse; margin-bottom:4px; }
  th { padding:7px 10px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em;
    color:#6b7280; background:#f9fafb; text-align:left; }
  td { padding:7px 10px; font-size:13px; vertical-align:top; }
  tr:nth-child(even) td { background:#f9fafb; }
  .badge-sent   { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600; background:#dcfce7; color:#15803d; }
  .badge-failed { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600; background:#fee2e2; color:#b91c1c; }
  .footer { padding:18px 32px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>{{ $report['total_failed'] > 0 ? '⚠️ Reminder Report' : '✅ Reminder Report' }}</h1>
    <p>Sistem Kami — WhatsApp Booking Reminders</p>
  </div>

  <div class="body">
    <p style="font-size:13px;color:#6b7280;margin:0 0 20px;">
      Ran at: <strong>{{ $report['ran_at'] }}</strong>
    </p>

    <div class="stats">
      <div class="stat-box stat-sent">
        <div class="num">{{ $report['total_sent'] }}</div>
        <div class="lbl">Sent</div>
      </div>
      <div class="stat-box stat-failed">
        <div class="num">{{ $report['total_failed'] }}</div>
        <div class="lbl">Failed</div>
      </div>
      <div class="stat-box stat-skip">
        <div class="num">{{ $report['total_skipped'] }}</div>
        <div class="lbl">Skipped (quiet hours)</div>
      </div>
    </div>

    @if(count($report['organizers']) > 0)
      @foreach($report['organizers'] as $org)
        <div class="group-title">
          {{ $org['name'] }}
          <span style="float:right;font-size:11px;color:{{ $org['failed'] > 0 ? '#dc2626' : '#16a34a' }}">
            {{ $org['sent'] }} sent / {{ $org['failed'] }} failed
          </span>
        </div>

        @if(count($org['bookings']) > 0)
          @foreach($org['bookings'] as $b)
          <div style="border:1px solid {{ $b['status'] === 'failed' ? '#fecaca' : '#d1fae5' }};border-radius:8px;margin-bottom:12px;overflow:hidden;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:{{ $b['status'] === 'failed' ? '#fef2f2' : '#f0fdf4' }};">
              <div>
                <span style="font-weight:700;font-size:13px;">{{ $b['booking_code'] }}</span>
                <span style="color:#6b7280;font-size:12px;margin-left:8px;">{{ $b['participant'] }}</span>
                @if($b['phone'])
                  <span style="color:#9ca3af;font-size:11px;margin-left:6px;">({{ $b['phone'] }})</span>
                @endif
              </div>
              <div style="text-align:right;font-size:12px;color:#6b7280;">
                <div>{{ $b['slot_date'] }}</div>
                <div>{{ $b['slot_time'] }}</div>
              </div>
            </div>
            <div style="display:flex;gap:0;">
              <div style="width:3px;background:{{ $b['status'] === 'sent' ? '#22c55e' : '#ef4444' }};flex-shrink:0;"></div>
              <div style="padding:10px 14px;flex:1;">
                <div style="margin-bottom:6px;">
                  @if($b['status'] === 'sent')
                    <span class="badge-sent">Sent</span>
                  @else
                    <span class="badge-failed">Failed</span>
                  @endif
                  <span style="color:#9ca3af;font-size:11px;margin-left:8px;">{{ $b['package'] }}</span>
                </div>
                @if($b['wa_message'])
                <pre style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:12px;color:#374151;white-space:pre-wrap;word-break:break-word;background:#f9fafb;border-radius:6px;padding:10px;line-height:1.6;">{{ $b['wa_message'] }}</pre>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        @else
          <p style="font-size:13px;color:#9ca3af;margin:8px 0 16px;">No bookings in reminder window.</p>
        @endif
      @endforeach
    @else
      <p style="font-size:14px;color:#6b7280;">No organizers processed (quiet hours or no Fonnte token configured).</p>
    @endif
  </div>

  <div class="footer">
    This report was sent automatically by Sistem Kami after each reminder run.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard" style="color:#6b7280;">Open Dashboard</a>
  </div>

</div>
</body>
</html>
