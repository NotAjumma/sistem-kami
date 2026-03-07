@extends('layouts.admin.default')
@push('styles')
    <style>
        .card {
            height: auto;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Off Days --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-times me-2 text-danger"></i> Off Days
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Set recurring weekly off days or specific dates when you are not available for bookings.</p>

                    {{-- Add Off Day Form --}}
                    <form action="{{ route('organizer.business.settings.off-days.store') }}" method="POST">
                        @csrf
                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Type</label>
                                <select name="type" id="offDayType" class="form-select form-select-sm" onchange="toggleOffDayType()">
                                    <option value="weekly">Weekly (recurring)</option>
                                    <option value="specific">Specific Date</option>
                                </select>
                            </div>
                            <div class="col-md-2" id="weeklyField">
                                <label class="form-label small fw-semibold">Day</label>
                                <select name="day_of_week" class="form-select form-select-sm">
                                    <option value="0">Sunday</option>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-none" id="specificField">
                                <label class="form-label small fw-semibold">Date</label>
                                <input type="date" name="off_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Start Time <span class="text-muted">(optional)</span></label>
                                <input type="time" name="start_time" class="form-control form-control-sm" placeholder="All day">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">End Time <span class="text-muted">(optional)</span></label>
                                <input type="time" name="end_time" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Reason <span class="text-muted">(optional)</span></label>
                                <input type="text" name="reason" class="form-control form-control-sm" placeholder="e.g. Public holiday">
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add
                                </button>
                            </div>
                        </div>
                        @error('day_of_week')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        @error('off_date')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        @error('start_time')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        @error('end_time')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                    </form>

                    {{-- Off Days Table --}}
                    @if($offDays->isEmpty())
                        <p class="text-muted small mb-0">No off days configured.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table">
                                    <tr>
                                        <th>Type</th>
                                        <th>Day / Date</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th style="width:60px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offDays as $od)
                                    <tr>
                                        <td>
                                            @if($od->day_of_week !== null)
                                                <span class="badge bg-primary">Weekly</span>
                                            @else
                                                <span class="badge bg-secondary">Specific</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($od->day_of_week !== null)
                                                {{ ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$od->day_of_week] }}
                                            @else
                                                {{ \Carbon\Carbon::parse($od->off_date)->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($od->start_time && $od->end_time)
                                                {{ \Carbon\Carbon::parse($od->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($od->end_time)->format('H:i') }}
                                            @elseif($od->start_time)
                                                From {{ \Carbon\Carbon::parse($od->start_time)->format('H:i') }}
                                            @else
                                                <span class="text-muted">All day</span>
                                            @endif
                                        </td>
                                        <td>{{ $od->reason ?? '—' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('organizer.business.settings.off-days.destroy', $od->id) }}"
                                                  data-confirm="Remove this off day?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

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
                            <li>Go to your Fonnte dashboard → <strong>Device </strong> → Create Device → Connect Device → copy your <strong>Token</strong></li>
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

    function toggleOffDayType() {
        const type = document.getElementById('offDayType').value;
        document.getElementById('weeklyField').classList.toggle('d-none', type !== 'weekly');
        document.getElementById('specificField').classList.toggle('d-none', type !== 'specific');
    }
</script>
@endpush
