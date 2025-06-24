@extends('home.homeLayout')

@section('content')
    <div class="container py-5 text-center">
        <div class="card shadow p-4">
            <h1 class="text-success">Booking Paid</h1>
            <h2>Booking Confirmed!</h2>
            <p>Booking ID: <strong>{{ $booking->id }}</strong></p>
            <p>Package: {{ $booking->package->name }}</p>
            <p>Booked Date: {{ $booking->vendorTimeSlots->first()->booked_date_start ?? 'N/A' }}</p>

            {{-- <a href="{{ route('booking.pdf', $booking->id) }}" class="btn btn-primary">Download Receipt (PDF)</a> --}}

            <a href="{{ route('index') }}" class="btn btn-primary mt-3">
                Return to Homepage
            </a>
        </div>
    </div>
@endsection