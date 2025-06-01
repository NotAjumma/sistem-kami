<p>Hi {{ $booking->participant->name }},</p>

<p>Your payment has been confirmed. Thank you!</p>

<p><strong>Booking Summary:</strong></p>
<ul>
    <li>Booking Code: {{ $booking->booking_code }}</li>
    <li>Status: {{ ucfirst($booking->status) }}</li>
    <li>Total Price: RM {{ number_format($booking->total_price, 2) }}</li>
</ul>

<p><strong>Tickets:</strong></p>
<ul>
    @foreach ($booking->bookingTickets as $ticket)
        <li>
            Ticket Code: {{ $ticket->ticket_code }}<br>
            Name: {{ $ticket->participant_name }}<br>
            Email: {{ $ticket->participant_email }}<br>
            Status: {{ ucfirst($ticket->status) }}
        </li>
    @endforeach
</ul>

<p>Regards,<br>SistemKami Team</p>
