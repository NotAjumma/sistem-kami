@extends('superadmin.layout')
@section('title', 'Settings')
@section('content')

<h4 class="fw-bold mb-4">Settings</h4>

<div class="card" style="max-width: 600px;">
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

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

@endsection
