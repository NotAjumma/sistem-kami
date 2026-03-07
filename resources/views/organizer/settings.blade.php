@extends('layouts.admin.default')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- WhatsApp Reminder Settings --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-whatsapp me-2 text-success"></i> WhatsApp Reminder Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <strong>How to set up:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Register at <a href="https://fonnte.com" target="_blank" rel="noopener">fonnte.com</a></li>
                            <li>Connect your WhatsApp number by scanning the QR code</li>
                            <li>Go to your Fonnte dashboard → <strong>Device</strong> → copy your <strong>Token</strong></li>
                            <li>Paste the token below and save</li>
                        </ol>
                        <div class="mt-2 small text-muted">
                            Reminders will be sent automatically 12 hours before each booking session. Free tier is sufficient for low volume.
                        </div>
                    </div>

                    <form action="{{ route('organizer.business.settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fonnte API Token</label>
                            <div class="input-group">
                                <input type="password" id="fonnte_token" name="fonnte_token"
                                    class="form-control @error('fonnte_token') is-invalid @enderror"
                                    value="{{ old('fonnte_token', $authUser->fonnte_token) }}"
                                    placeholder="Paste your Fonnte token here">
                                <button type="button" class="btn btn-outline-secondary" id="toggleToken" title="Show/hide token">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('fonnte_token')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if ($authUser->fonnte_token)
                                <div class="form-text text-success">
                                    <i class="fas fa-check-circle me-1"></i> Token is configured. Reminders are active.
                                </div>
                            @else
                                <div class="form-text text-muted">
                                    No token set. Reminders will not be sent until you add a token.
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quiet Hours (no reminders sent during this period)</label>
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label small text-muted">From</label>
                                </div>
                                <div class="col-auto">
                                    <select name="reminder_quiet_start" class="form-select form-select-sm">
                                        @for ($h = 0; $h < 24; $h++)
                                            <option value="{{ $h }}" {{ old('reminder_quiet_start', $authUser->reminder_quiet_start ?? 0) == $h ? 'selected' : '' }}>
                                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00 ({{ $h < 12 ? ($h == 0 ? '12am' : $h.'am') : ($h == 12 ? '12pm' : ($h-12).'pm') }})
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="col-form-label small text-muted">Until</label>
                                </div>
                                <div class="col-auto">
                                    <select name="reminder_quiet_end" class="form-select form-select-sm">
                                        @for ($h = 0; $h < 24; $h++)
                                            <option value="{{ $h }}" {{ old('reminder_quiet_end', $authUser->reminder_quiet_end ?? 6) == $h ? 'selected' : '' }}>
                                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00 ({{ $h < 12 ? ($h == 0 ? '12am' : $h.'am') : ($h == 12 ? '12pm' : ($h-12).'pm') }})
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-text text-muted">Reminders will not be sent between these hours. Default: 12am – 6am.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Settings
                            </button>
                            @if ($authUser->fonnte_token)
                            <button type="submit" name="fonnte_token" value="" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-1"></i> Remove Token
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggleToken').addEventListener('click', function () {
        const input = document.getElementById('fonnte_token');
        const icon  = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endpush
