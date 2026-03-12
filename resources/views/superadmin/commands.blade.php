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

        <hr class="my-3">

        {{-- ── Scheduler Commands (background with live log) ─────────── --}}
        <h6 class="text-muted text-uppercase small mb-3" style="letter-spacing:.06em">
            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">schedule</i>
            Scheduled Tasks
        </h6>

        @foreach(['reminders_send', 'daily_report', 'health_report'] as $key)
        @php $cmd = $commands[$key]; @endphp
        <div class="card mb-3 border-primary border-opacity-25">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0">{{ $cmd['label'] }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:10px">Background</span>
                    </div>
                    <p class="text-muted small mb-1">{{ $cmd['description'] }}</p>
                    <code class="small text-secondary">
                        php artisan {{ $cmd['command'] }}{{ count($cmd['args']) ? ' ' . implode(' ', $cmd['args']) : '' }}
                    </code>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary text-nowrap"
                            onclick="runBgCommand('{{ $key }}', '{{ $cmd['label'] }}')">
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">play_arrow</i>
                        Run
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        @if($pendingCount > 0)
        <div class="alert alert-warning small py-2 px-3">
            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">notifications</i>
            <strong>{{ $pendingCount }}</strong> pending reminder{{ $pendingCount == 1 ? '' : 's' }} waiting to be sent
        </div>
        @endif

        <hr class="my-3">

        {{-- ── Other Artisan Commands ─────────────────────────────────── --}}
        <h6 class="text-muted text-uppercase small mb-3" style="letter-spacing:.06em">
            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">terminal</i>
            System Commands
        </h6>

        @foreach($commands as $key => $cmd)
        @if(in_array($key, ['reminders_send', 'daily_report', 'health_report'])) @continue @endif
        <div class="card mb-3">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="mb-0">{{ $cmd['label'] }}</h6>
                        @if(!empty($cmd['danger']))
                            <span class="badge bg-warning text-dark" style="font-size:11px">Caution</span>
                        @endif
                        @if(!empty($cmd['background']))
                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:10px">Background</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-0">{{ $cmd['description'] }}</p>
                    <code class="small text-secondary">
                        php artisan {{ $cmd['command'] }}{{ count($cmd['args']) ? ' ' . implode(' ', $cmd['args']) : '' }}
                    </code>
                </div>
                <div class="d-flex flex-column gap-2 align-items-end">
                    @if(!empty($cmd['background']))
                    <button type="button" class="btn btn-sm {{ !empty($cmd['danger']) ? 'btn-warning' : 'btn-primary' }} text-nowrap"
                            @if(!empty($cmd['danger'])) onclick="if(confirm('This will overwrite existing files. Are you sure?')) runBgCommand('{{ $key }}', '{{ $cmd['label'] }}')" @else onclick="runBgCommand('{{ $key }}', '{{ $cmd['label'] }}')" @endif>
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">play_arrow</i>
                        Run
                    </button>
                    @else
                    <form method="POST" action="{{ route('superadmin.commands.run') }}"
                          @if(!empty($cmd['danger'])) data-confirm="This will overwrite existing files. Are you sure?" @endif>
                        @csrf
                        <input type="hidden" name="command_key" value="{{ $key }}">
                        <button type="submit" class="btn btn-sm {{ !empty($cmd['danger']) ? 'btn-warning' : 'btn-primary' }} text-nowrap">
                            <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">play_arrow</i>
                            Run
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="col-lg-4">

        {{-- Command Output (for sync commands) --}}
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
                    <li>Scheduler commands run in background with live log</li>
                    <li><strong>Reminders</strong> run automatically every 15 min via scheduler</li>
                    <li><strong>Daily Report</strong> runs automatically at 11 PM MYT</li>
                    <li><strong>Health Check</strong> runs automatically at 8 AM MYT</li>
                    <li>Each booking is reminded once (<code>reminder_sent_at</code> is set)</li>
                    <li>Report emails sent to the configured <code>report_email</code></li>
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

{{-- ── Live Log Modal ────────────────────────────────────────────── --}}
<div class="modal fade" id="liveLogModal" tabindex="-1" aria-labelledby="liveLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="liveLogModalLabel">
                    <i class="material-symbols-outlined align-middle me-2" style="font-size:20px">terminal</i>
                    <span id="liveLogTitle">Command Output</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopPolling()"></button>
            </div>
            <div class="modal-body p-0">
                <div id="liveLogStatus" class="px-3 py-2 bg-primary bg-opacity-10 d-flex align-items-center gap-2" style="font-size:13px">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="text-primary fw-semibold">Running...</span>
                </div>
                <pre id="liveLogOutput" class="p-3 mb-0 bg-dark text-light" style="font-size:12px;white-space:pre-wrap;min-height:150px;max-height:500px;overflow-y:auto">(waiting for output...)</pre>
            </div>
            <div class="modal-footer justify-content-between">
                <small id="liveLogInfo" class="text-muted"></small>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" onclick="stopPolling()">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ── Health Check ───────────────────────────────────────────────── */
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

/* ── Live Log: Background Commands ──────────────────────────────── */
let pollTimer   = null;
let pollKey     = null;

function stopPolling() {
    if (pollTimer) {
        clearInterval(pollTimer);
        pollTimer = null;
    }
    pollKey = null;
}

function runBgCommand(key, label) {
    stopPolling();
    pollKey = key;

    const logTitle  = document.getElementById('liveLogTitle');
    const logOutput = document.getElementById('liveLogOutput');
    const logStatus = document.getElementById('liveLogStatus');
    const logInfo   = document.getElementById('liveLogInfo');

    logTitle.textContent  = label;
    logOutput.textContent = '(starting command...)';
    logInfo.textContent   = '';
    logStatus.innerHTML   = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div><span class="text-primary fw-semibold">Running...</span>';
    logStatus.className   = 'px-3 py-2 bg-primary bg-opacity-10 d-flex align-items-center gap-2';

    const modal = new bootstrap.Modal(document.getElementById('liveLogModal'));
    modal.show();

    // Start the command via AJAX POST
    fetch('{{ route("superadmin.commands.run") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ command_key: key }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            logOutput.textContent = 'ERROR: ' + data.error;
            return;
        }
        logInfo.textContent = 'PID: ' + (data.pid || '-');

        // Start polling for log output
        pollTimer = setInterval(() => pollLog(key), 1000);
        // Also poll immediately
        pollLog(key);
    })
    .catch(err => {
        logOutput.textContent = 'Failed to start command: ' + err.message;
        logStatus.innerHTML   = '<i class="fa-solid fa-circle-xmark text-danger"></i><span class="text-danger fw-semibold">Error</span>';
        logStatus.className   = 'px-3 py-2 bg-danger bg-opacity-10 d-flex align-items-center gap-2';
    });
}

function pollLog(key) {
    if (pollKey !== key) return; // user switched or closed

    const pollUrl = '{{ route("superadmin.commands.poll", ":key") }}'.replace(':key', key);

    fetch(pollUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            const logOutput = document.getElementById('liveLogOutput');
            const logStatus = document.getElementById('liveLogStatus');

            logOutput.textContent = data.output;

            // Auto-scroll to bottom
            logOutput.scrollTop = logOutput.scrollHeight;

            if (!data.running) {
                stopPolling();
                logStatus.innerHTML = '<i class="fa-solid fa-circle-check text-success"></i><span class="text-success fw-semibold">Completed</span>';
                logStatus.className = 'px-3 py-2 bg-success bg-opacity-10 d-flex align-items-center gap-2';
            }
        })
        .catch(() => {
            // Silently retry on next interval
        });
}

// Clean up polling when modal is closed
document.getElementById('liveLogModal').addEventListener('hidden.bs.modal', stopPolling);
</script>
@endpush
