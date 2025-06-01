<style>
    .page-break {
        page-break-after: always;
    }
</style>

@foreach ($booking->bookingTickets as $index => $ticket)
    <h2>Ticket</h2>

    <p><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
    <p><strong>Total Price:</strong> RM {{ number_format($booking->total_price, 2) }}</p>

    <hr>
    <p><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>
    <p><strong>Name:</strong> {{ $ticket->participant_name }}</p>
    <p><strong>Email:</strong> {{ $ticket->participant_email }}</p>
    <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
