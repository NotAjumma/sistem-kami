<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Booking Report</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:24px; color:#333; }
  .wrap { max-width:700px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
  .header { padding:28px 32px; background:#0f172a; color:#fff; }
  .header h1 { margin:0 0 6px; font-size:22px; }
  .header p  { margin:0; opacity:.7; font-size:13px; }
  .body { padding:28px 32px; }
  .stats { display:flex; gap:16px; margin-bottom:24px; }
  .stat-box { flex:1; padding:14px 18px; border-radius:8px; text-align:center; }
  .stat-box .num { font-size:28px; font-weight:700; line-height:1; }
  .stat-box .lbl { font-size:12px; margin-top:4px; opacity:.8; }
  .stat-blue   { background:#dbeafe; color:#1e40af; }
  .stat-green  { background:#dcfce7; color:#15803d; }
  .group-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
    color:#6b7280; margin:20px 0 8px; padding-bottom:4px; border-bottom:1px solid #e5e7eb; }
  .org-summary { display:flex; justify-content:space-between; align-items:center;
    background:#f8fafc; border-radius:8px; padding:10px 14px; margin-bottom:10px; font-size:13px; }
  .org-name { font-weight:700; }
  .org-meta { color:#6b7280; font-size:12px; }
  table { width:100%; border-collapse:collapse; margin-bottom:4px; }
  th { padding:7px 10px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em;
    color:#6b7280; background:#f9fafb; text-align:left; }
  td { padding:7px 10px; font-size:12px; vertical-align:top; border-bottom:1px solid #f3f4f6; }
  tr:last-child td { border-bottom:none; }
  .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600; }
  .badge-pending   { background:#fef3c7; color:#92400e; }
  .badge-confirmed { background:#dbeafe; color:#1e40af; }
  .badge-paid      { background:#dcfce7; color:#15803d; }
  .badge-completed { background:#d1fae5; color:#065f46; }
  .footer { padding:18px 32px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>📊 Daily Booking Report</h1>
    <p>{{ $report['date'] }} · Sistem Kami</p>
  </div>

  <div class="body">

    <div class="stats">
      <div class="stat-box stat-blue">
        <div class="num">{{ $report['total_bookings'] }}</div>
        <div class="lbl">Total Bookings</div>
      </div>
      <div class="stat-box stat-green">
        <div class="num">RM{{ number_format($report['total_revenue'], 2) }}</div>
        <div class="lbl">Total Collected</div>
      </div>
      <div class="stat-box" style="background:#ede9fe;color:#6d28d9;">
        <div class="num">{{ $report['total_visitors'] }}</div>
        <div class="lbl">Unique Visitors</div>
      </div>
    </div>

    @if(count($report['organizers']) > 0)
      @foreach($report['organizers'] as $org)
        <div class="group-title">{{ $org['name'] }}</div>

        <div class="org-summary">
          <div>
            <span class="org-name">{{ $org['total'] }} booking{{ $org['total'] > 1 ? 's' : '' }}</span>
            <span style="margin-left:12px;font-size:12px;color:#7c3aed;">
              👁 {{ $org['visitors_today'] }} visitors
              <span style="color:#9ca3af;">({{ $org['profile_views'] }} profile · {{ $org['package_views'] }} package)</span>
            </span>
          </div>
          <div class="org-meta">Collected: <strong style="color:#15803d;">RM{{ number_format($org['total_revenue'], 2) }}</strong></div>
        </div>

        @if(count($org['bookings']) > 0)
        <table>
          <tr>
            <th>Time</th>
            <th>Booking</th>
            <th>Participant</th>
            <th>Package</th>
            <th>Slot</th>
            <th>Paid (RM)</th>
            <th>Status</th>
          </tr>
          @foreach($org['bookings'] as $b)
          <tr>
            <td style="color:#9ca3af;white-space:nowrap;">{{ $b['created_at'] }}</td>
            <td style="font-weight:600;">{{ $b['booking_code'] }}</td>
            <td>
              {{ $b['participant'] }}
              @if($b['phone'] && $b['phone'] !== '-')
                <div style="color:#9ca3af;font-size:11px;">{{ $b['phone'] }}</div>
              @endif
            </td>
            <td>
              {{ $b['package'] }}
              @if($b['addons'])
                <div style="color:#9ca3af;font-size:11px;">+ {{ $b['addons'] }}</div>
              @endif
            </td>
            <td style="white-space:nowrap;">
              {{ $b['slot_date'] }}<br>
              <span style="color:#6b7280;">{{ $b['slot_time'] }}</span>
            </td>
            <td style="font-weight:600;">{{ number_format($b['paid_amount'], 2) }}
              @if($b['payment_type'] === 'deposit')
                <div style="color:#9ca3af;font-size:11px;">deposit</div>
              @endif
            </td>
            <td><span class="badge badge-{{ $b['status'] }}">{{ ucfirst($b['status']) }}</span></td>
          </tr>
          @endforeach
        </table>
        @endif
      @endforeach
    @else
      <p style="font-size:14px;color:#6b7280;text-align:center;padding:32px 0;">No bookings recorded today.</p>
    @endif

  </div>

  <div class="footer">
    This report was sent automatically by Sistem Kami at end of day.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard" style="color:#6b7280;">Open Dashboard</a>
  </div>

</div>
</body>
</html>
