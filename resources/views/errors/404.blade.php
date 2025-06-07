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
            <div class="dz-error" data-text="404">404</div>
            <h2 class="error-head mb-0">
                <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                The page you were looking for is not found!
            </h2>
            <p>You may have mistyped the address or the page may have moved.</p>
            <a href="{{ $redirectUrl }}" class="btn btn-secondary">{{ $buttonText }}</a>
        </div>
    </div>
</div>
@endsection
