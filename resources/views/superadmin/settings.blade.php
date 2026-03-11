@extends('superadmin.layout')
@section('title', 'Settings')
@section('content')

<h4 class="fw-bold mb-4">Settings</h4>

<div class="card mb-4" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0">API Keys & Configuration</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.settings.save') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">TinyMCE API Key</label>
                <input type="text" class="form-control" name="tinymce_api_key"
                       value="{{ old('tinymce_api_key', $settings['tinymce_api_key']) }}"
                       placeholder="Paste your TinyMCE API key here">
                <div class="form-text">Get your key at <strong>tiny.cloud</strong> → My Account → API Keys. Leave blank to use no-api-key (watermark will show).</div>
            </div>

            <hr class="my-4">

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-envelope me-1 text-warning"></i> Resend API Key
                    <span class="badge bg-success ms-1" style="font-size:10px">Free — 3,000 emails/month</span>
                </label>
                <input type="password" class="form-control font-monospace" name="resend_api_key"
                       value="{{ old('resend_api_key', $settings['resend_api_key']) }}"
                       placeholder="re_xxxxxxxxxxxxxxxxxxxx"
                       autocomplete="new-password">
                <div class="form-text">
                    Get your free key at <strong>resend.com</strong> → API Keys → Create API Key.
                    Used to send health check email reports. No <code>.env</code> changes needed — stored in DB.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">From Email <span class="text-muted fw-normal">(sender)</span></label>
                <input type="email" class="form-control" name="health_check_from"
                       value="{{ old('health_check_from', $settings['health_check_from']) }}"
                       placeholder="onboarding@resend.dev">
                <div class="form-text">
                    Use <code>onboarding@resend.dev</code> for testing (no setup needed).<br>
                    For production: <strong>verify your own domain</strong> on <strong>resend.com → Domains</strong>,
                    then use <code>noreply@yourdomain.com</code>.
                </div>
            </div>

            <div class="mb-1">
                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-stethoscope me-1 text-info"></i> Send Report To
                </label>
                <input type="email" class="form-control" name="report_email"
                       value="{{ old('report_email', $settings['report_email']) }}"
                       placeholder="e.g. salessistemkami@gmail.com">
                <div class="form-text">
                    All system reports go here — health check, reminders, new/cancelled bookings, daily summary.
                    Test: <code>php artisan health:report --force</code>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

@endsection
