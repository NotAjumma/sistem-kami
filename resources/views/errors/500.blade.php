@extends('layouts.fullwidth')
@section('content')
@php
    $isOrganizer = request()->is('organizer/*');
    $redirectUrl = $isOrganizer ? route('organizer.dashboard') : route('index');
    $buttonText = $isOrganizer ? 'Back to Dashboard' : 'Back to Homepage';
@endphp

<div class="col-md-6">
    <div class="error-page">
        <div class="error-inner text-center">
            <div class="dz-error" data-text="500">500</div>
            <h2 class="error-head mb-0"><i class="fa fa-times-circle text-danger me-2"></i> Internal Server Error</h2>
            <p>You do not have permission to view this resource</p>
            <a href="{{ $redirectUrl }}" class="btn btn-secondary">{{ $buttonText }}</a>
        </div>
    </div>
</div>
@endsection
