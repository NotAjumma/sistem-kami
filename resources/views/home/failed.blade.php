@extends('home.homeLayout') 

@section('content')
<div class="container py-5 text-center">
    <div class="card shadow p-4">
        <h1 class="text-danger">Booking Failed</h1>
        <p class="mt-3 mb-4">Unfortunately, we couldn't process your booking or payment.</p>

        <div class="mb-4">
            <i class="fas fa-times-circle fa-4x text-danger"></i>
        </div>

        <p>Please try again or contact support if the problem continues.</p>

        <a href="{{ route('index') }}" class="btn btn-primary mt-3">
            Return to Homepage
        </a>
    </div>
</div>
@endsection
