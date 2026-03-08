@extends('superadmin.layout')

@section('title', 'WhatsApp Reminders')

@section('content')
<div class="row mt-4">
    <div class="col-lg-8 col-xl-6">

        {{-- Status Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="material-symbols-outlined align-middle me-1" style="font-size:20px">notifications</i>
                    WhatsApp Reminder Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="text-center px-4 py-3 rounded border">
                        <div class="fs-2 fw-bold text-primary">{{ $pendingCount }}</div>
                        <div class="small text-muted">Pending reminders<br><span class="text-muted">(next 12 hours)</span></div>
                    </div>
                    <div class="text-muted small">
                        Bookings starting within the next 12 hours that have not yet received a WhatsApp reminder, and whose organizer has a Fonnte token configured.
                    </div>
                </div>

                <form method="POST" action="{{ route('superadmin.reminders.trigger') }}"
                      data-confirm="This will send WhatsApp reminders to all eligible customers now. Continue?">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">send</i>
                        Run Reminders Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Output Card --}}
        @if(session('reminder_output'))
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Command Output</h6>
            </div>
            <div class="card-body p-0">
                <pre class="p-3 mb-0 bg-dark text-light rounded-bottom" style="font-size:13px; white-space:pre-wrap;">{{ session('reminder_output') }}</pre>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4 col-xl-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Info</h6></div>
            <div class="card-body">
                <ul class="small text-muted mb-0" style="line-height:2">
                    <li>Reminders are sent automatically every hour via scheduler</li>
                    <li>Use "Run Reminders Now" to trigger manually outside the schedule</li>
                    <li>Only bookings within the next 12 hours are targeted</li>
                    <li>Each booking is reminded once (<code>reminder_sent_at</code> is set after sending)</li>
                    <li>Quiet hours per organizer are respected</li>
                    <li>Payment QR image is attached if the organizer has one configured</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
