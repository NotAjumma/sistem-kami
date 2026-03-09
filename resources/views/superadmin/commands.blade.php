@extends('superadmin.layout')
@push('styles')
    <style>
        .card{
            height: auto;
        }
    </style>
@endpush
@section('title', 'Artisan Commands')

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

        {{-- Command Cards --}}
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
                <form method="POST" action="{{ route('superadmin.commands.run') }}"
                      @if($cmd['danger']) data-confirm="This will overwrite existing files. Are you sure?" @endif>
                    @csrf
                    <input type="hidden" name="command_key" value="{{ $key }}">
                    <button type="submit" class="btn btn-sm {{ $cmd['danger'] ? 'btn-warning' : 'btn-primary' }} text-nowrap">
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:16px">play_arrow</i>
                        Run
                    </button>
                </form>
            </div>
        </div>
        @endforeach

    </div>

    <div class="col-lg-4">

        {{-- Output Card --}}
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
                    <li><strong>Regenerate All WebP</strong> should be run after changing image quality or resize settings</li>
                    <li>Clear All Caches before deploying new code changes</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
