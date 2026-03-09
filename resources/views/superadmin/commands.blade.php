@extends('superadmin.layout')
@push('styles')
    <style>
        .card{
            height: auto;
        }
    </style>
@endpush
@section('title', 'Commands & Tools')

@section('content')
<div class="row mt-4">
    <div class="col-lg-8">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">check_circle</i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- ── Site Health Check ─────────────────────────────────────── --}}
        <div class="card mb-3 border-info border-opacity-50">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0"><i class="fa-solid fa-stethoscope me-1 text-info"></i> Site Health Check</h6>
                    </div>
                    <p class="text-muted small mb-0">Runs a series of checks on infrastructure, page availability, booking flow, and more. Results shown in a modal.</p>
                </div>
                <div>
                    <button id="runHealthCheckBtn" class="btn btn-sm btn-outline-info text-nowrap" onclick="runHealthCheck()">
                        <i class="fa-solid fa-stethoscope me-1"></i> Run Check
                    </button>
                </div>
            </div>
        </div>

        {{-- ── WhatsApp Reminders ────────────────────────────────────── --}}
        <div class="card mb-3 border-warning border-opacity-50">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0"><i class="material-symbols-outlined align-middle me-1" style="font-size:18px">notifications</i> WhatsApp Reminders</h6>
                    </div>
                    <p class="text-muted small mb-1">
                        Send WhatsApp reminders to customers with bookings starting in the next 12 hours.
                        Organizer must have a Fonnte token configured.
                    </p>
                    <span class="badge bg-{{ $pendingCount > 0 ? 'warning text-dark' : 'secondary' }}">
                        {{ $pendingCount }} pending reminder{{ $pendingCount == 1 ? '' : 's' }}
                    </span>
                </div>
                <div>
                    <form method="POST" action="{{ route('superadmin.reminders.trigger') }}"
                          data-confirm="This will send WhatsApp reminders to all eligible customers now. Continue?">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning text-nowrap">
                            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">send</i>
                            Run Now
                        </button>
                    </form>
                </div>
            </div>
            @if(session('reminder_output'))
            <div class="card-footer p-0">
                <pre class="p-3 mb-0 bg-dark text-light rounded-bottom" style="font-size:12px;white-space:pre-wrap;max-height:200px;overflow-y:auto">{{ session('reminder_output') }}</pre>
            </div>
            @endif
        </div>

        <hr class="my-3">

        {{-- ── Artisan Commands ──────────────────────────────────────── --}}
        @foreach($commands as $key => $cmd)
        <div class="card mb-3">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0">{{ $cmd['label'] }}</h6>
                        @if($cmd['danger'])
                            <span class="badge bg-warning text-dark" style="font-size:11px">Caution</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-0">{{ $cmd['description'] }}</p>
                    <code class="small text-secondary">
                        php artisan {{ $cmd['command'] }}{{ count($cmd['args']) ? ' ' . implode(' ', $cmd['args']) : '' }}
                    </code>
                </div>
                <div class="d-flex flex-column gap-2 align-items-end">
                    <form method="POST" action="{{ route('superadmin.commands.run') }}"
                          @if($cmd['danger']) data-confirm="This will overwrite existing files. Are you sure?" @endif>
                        @csrf
                        <input type="hidden" name="command_key" value="{{ $key }}">
                        <button type="submit" class="btn btn-sm {{ $cmd['danger'] ? 'btn-warning' : 'btn-primary' }} text-nowrap">
                            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">play_arrow</i>
                            Run
                        </button>
                    </form>
                    @if(!empty($cmd['background']))
                        <a href="{{ route('superadmin.commands.log', $key) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">article</i>
                            Read Log
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="col-lg-4">

        {{-- Command Output --}}
        @if(session('cmd_output'))
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="material-symbols-outlined" style="font-size:18px">terminal</i>
                <span class="fw-semibold small">Output: {{ session('cmd_ran') }}</span>
            </div>
            <div class="card-body p-0">
                <pre class="p-3 mb-0 bg-dark text-light rounded-bottom" style="font-size:12px;white-space:pre-wrap;max-height:400px;overflow-y:auto">{{ session('cmd_output') }}</pre>
            </div>
        </div>
        @endif

        {{-- Info Card --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Info</h6></div>
            <div class="card-body">
                <ul class="small text-muted mb-0" style="line-height:2">
                    <li>Commands run synchronously — page will wait until complete</li>
                    <li>Large image sets may take a minute for WebP generation</li>
                    <li><strong>Regenerate All WebP</strong> runs in background — use Read Log to check progress</li>
                    <li>Reminders are also sent automatically every hour via scheduler</li>
                    <li>Each booking is reminded once (<code>reminder_sent_at</code> is set)</li>
                    <li>Quiet hours per organizer are respected</li>
                </ul>
            </div>
        </div>

    </div>
</div>

{{-- ── Health Check Modal ─────────────────────────────────────────── --}}
<div class="modal fade" id="healthCheckModal" tabindex="-1" aria-labelledby="healthCheckModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="healthCheckModalLabel">
                    <i class="fa-solid fa-stethoscope me-2 text-info"></i>Site Health Check
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="healthCheckLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-info" role="status"></div>
                    <p class="mt-2 text-muted">Running checks…</p>
                </div>
                <div id="healthCheckContent"></div>
            </div>
            <div class="modal-footer justify-content-between">
                <small id="healthCheckRunAt" class="text-muted"></small>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const HC_GROUP_ICONS = {
    'Infrastructure':      'fa-server',
    'Profile Page':        'fa-user-circle',
    'Date / Time / Slots': 'fa-calendar-days',
    'Booking Flow':        'fa-cart-shopping',
    'Forms & Buttons':     'fa-hand-pointer',
    'Page Views':          'fa-file-lines',
};

function runHealthCheck() {
    const btn     = document.getElementById('runHealthCheckBtn');
    const summary = document.getElementById('healthCheckSummary');
    const loading = document.getElementById('healthCheckLoading');
    const content = document.getElementById('healthCheckContent');
    const runAt   = document.getElementById('healthCheckRunAt');

    content.innerHTML = '';
    runAt.textContent = '';
    loading.classList.remove('d-none');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Running…';

    const modal = new bootstrap.Modal(document.getElementById('healthCheckModal'));
    modal.show();

    fetch('{{ route("superadmin.health-check") }}')
        .then(r => r.json())
        .then(data => {
            loading.classList.add('d-none');
            runAt.textContent = 'Ran at: ' + data.ran_at;

            const allPassed = data.passed === data.total;
            const groups = {};
            data.results.forEach(r => {
                if (!groups[r.group]) groups[r.group] = [];
                groups[r.group].push(r);
            });

            let html = `<div class="alert alert-${allPassed ? 'success' : 'warning'} d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-${allPassed ? 'circle-check' : 'triangle-exclamation'} fs-5"></i>
                <strong>${data.summary}</strong>
            </div>`;

            for (const [group, rows] of Object.entries(groups)) {
                const groupPassed  = rows.filter(r => r.status === 'pass').length;
                const groupTotal   = rows.length;
                const groupAllPass = groupPassed === groupTotal;
                const icon         = HC_GROUP_ICONS[group] || 'fa-circle-dot';

                html += `<div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-1 px-1">
                        <i class="fa-solid ${icon} small text-secondary"></i>
                        <span class="fw-semibold small text-uppercase text-secondary" style="letter-spacing:.05em">${group}</span>
                        <span class="badge ${groupAllPass ? 'bg-success' : 'bg-danger'} ms-auto">${groupPassed}/${groupTotal}</span>
                    </div>
                    <table class="table table-sm table-hover mb-0 border rounded">
                        <tbody>`;

                rows.forEach(r => {
                    const chk      = r.status === 'pass'
                        ? '<i class="fa-solid fa-circle-check text-success"></i>'
                        : '<i class="fa-solid fa-circle-xmark text-danger"></i>';
                    const rowClass = r.status === 'pass' ? '' : 'table-danger';
                    html += `<tr class="${rowClass}">
                        <td style="width:185px" class="fw-semibold small ps-2">${r.name}</td>
                        <td style="width:60px">${chk}</td>
                        <td class="small text-muted">${r.detail}</td>
                        <td class="text-end small text-muted pe-2" style="width:50px">${r.ms}ms</td>
                    </tr>`;
                });

                html += `</tbody></table></div>`;
            }

            content.innerHTML = html;
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-stethoscope me-1"></i> Run Check';
        })
        .catch(err => {
            loading.classList.add('d-none');
            content.innerHTML = `<div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Failed: ${err.message}</div>`;
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-stethoscope me-1"></i> Run Check';
        });
}
</script>
@endpush
