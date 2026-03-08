<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Health Check Report</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f4f6f9; margin:0; padding:24px; color:#333; }
  .wrap { max-width:680px; margin:0 auto; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); }
  .header { padding:28px 32px; background:{{ $report['passed'] === $report['total'] ? '#16a34a' : '#dc2626' }}; color:#fff; }
  .header h1 { margin:0 0 6px; font-size:22px; }
  .header p  { margin:0; opacity:.9; font-size:14px; }
  .body { padding:28px 32px; }
  .summary-badge { display:inline-block; padding:8px 18px; border-radius:20px; font-size:16px; font-weight:700;
    background:{{ $report['passed'] === $report['total'] ? '#dcfce7' : '#fee2e2' }};
    color:{{ $report['passed'] === $report['total'] ? '#15803d' : '#b91c1c' }}; margin-bottom:24px; }
  .group-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
    color:#6b7280; margin:20px 0 6px; padding-bottom:4px; border-bottom:1px solid #e5e7eb; }
  table { width:100%; border-collapse:collapse; margin-bottom:4px; }
  td { padding:7px 10px; font-size:13px; vertical-align:top; }
  tr:nth-child(even) td { background:#f9fafb; }
  .pass { color:#16a34a; font-weight:600; }
  .fail { color:#dc2626; font-weight:600; }
  .check-name { font-weight:600; width:175px; }
  .detail { color:#4b5563; }
  .ms { color:#9ca3af; text-align:right; width:50px; white-space:nowrap; }
  .footer { padding:18px 32px; background:#f9fafb; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
  .footer a { color:#6b7280; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>{{ $report['passed'] === $report['total'] ? '✅ All Systems OK' : '⚠️ Issues Detected' }}</h1>
    <p>Sistem Kami — Site Health Check Report</p>
  </div>

  <div class="body">
    <div class="summary-badge">{{ $report['summary'] }} checks passed</div>
    <p style="font-size:13px;color:#6b7280;margin:0 0 20px;">Ran at: <strong>{{ $report['ran_at'] }}</strong> &nbsp;·&nbsp; Environment: <strong>{{ config('app.env') }}</strong></p>

    @php
      $groups = collect($report['results'])->groupBy('group');
    @endphp

    @foreach($groups as $group => $rows)
      @php $groupPassed = $rows->where('status','pass')->count(); @endphp
      <div class="group-title">
        {{ $group }}
        <span style="float:right;font-size:11px;color:{{ $groupPassed === $rows->count() ? '#16a34a' : '#dc2626' }}">
          {{ $groupPassed }}/{{ $rows->count() }}
        </span>
      </div>
      <table>
        @foreach($rows as $r)
        <tr style="{{ $r['status'] === 'fail' ? 'background:#fef2f2;' : '' }}">
          <td class="check-name">{{ $r['name'] }}</td>
          <td class="{{ $r['status'] === 'pass' ? 'pass' : 'fail' }}">{{ strtoupper($r['status']) }}</td>
          <td class="detail">{{ $r['detail'] }}</td>
          <td class="ms">{{ $r['ms'] }}ms</td>
        </tr>
        @endforeach
      </table>
    @endforeach
  </div>

  <div class="footer">
    This report was sent automatically by Sistem Kami.<br>
    To change the recipient email, go to <strong>Superadmin → Settings → Health Check Email</strong>.<br>
    <a href="{{ config('app.url') }}/superadmin/dashboard">Open Dashboard</a>
  </div>

</div>
</body>
</html>
