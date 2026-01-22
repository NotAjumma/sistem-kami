<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        /* Reset and base */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            font-family: 'Montserrat', sans-serif;
            color: #222;
            padding: 20px;
        }

        .ticket-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .ticket {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: none;
            page-break-inside: avoid;
            padding: 30px 40px;
            position: relative;
        }

        .ticket h1 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0 0 30px 0;
            letter-spacing: 0.1em;
            color: #0f9d9a;
        }

        .label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .value {
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        .datetime {
            font-size: 0.75rem;
            margin-bottom: 4px;
            color: #6b7280;
            font-weight: 700;
        }

        .datetime+.datetime {
            margin-bottom: 0;
        }

        /* Notch shapes on right edge for print */
        .ticket::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 0;
            width: 30px;
            height: 60px;
            background: white;
            border-radius: 0 30px 30px 0;
            box-shadow: -10px 0 0 white;
            transform: translateY(-50%);
            z-index: 10;
        }

        .qr-section {
            margin-top: 30px;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 0 0 12px 12px;
            padding: 30px 20px;
            text-align: center;
        }

        .purchase-info {
            font-size: 0.625rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 0.05em;
        }

        .qr {
            width: 120px;
            height: 120px;
            margin-bottom: 8px;
            object-fit: contain;
        }

        .qr-code-number {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .attendee-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .attendee-ticket,
        .attendee-name,
        .attendee-email {
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 6px;
            word-break: break-word;
        }

        .notes-section {
            margin-top: 40px;
            max-width: 700px;
        }

        .notes-section h2 {
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .notes-section p {
            font-size: 0.75rem;
            line-height: 1.4;
            color: #4b5563;
            margin: 0;
        }

        /* Page break for PDF */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .ticket-container {
                max-width: 100%;
                margin: 0;
                box-shadow: none;
                border: none;
            }

            .ticket {
                box-shadow: none;
                border: 1px solid #d1d5db;
                border-radius: 0;
                page-break-inside: avoid;
                break-inside: avoid;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .ticket::after {
                background: #fff;
                box-shadow: -10px 0 0 #fff;
            }

            .qr-section {
                background: #f9fafb;
                border: 1px dashed #d1d5db;
                border-radius: 0;
                margin-top: 0;
                padding: 20px;
            }

            .notes-section {
                page-break-before: always;
                break-before: always;
            }

            .price-bar {
                border-top: 1px solid #d1d5db;
                padding-top: 12px;
                display: flex;
                justify-content: space-between;
                font-size: 0.875rem;
                font-weight: 700;
                color: #111827;
                user-select: none;
            }

            .content-layer {
                position: relative;
                z-index: 10;
                user-select: none;
            }
        }
    </style>
</head>

<body>
    @foreach ($booking->bookingTickets as $index => $ticket)
        <div class="ticket-container" role="main">
            <article class="ticket" aria-label="Event ticket for TEST EVENT 3">
                <h1 aria-label="Event organizer name">Sistem Kami</h1>
                <div>
                    <div class="label">Your ticket for</div>
                    <div class="value" aria-label="Event name">{{ $booking->event->title }}</div>
                </div>
                <div>
                    <div class="label">Location:</div>
                    <div class="value" aria-label="Event location">{{ $booking->event->venue_name }},
                        {{ $booking->event->district }} {{ $booking->event->state }}, {{ $booking->event->country }}
                    </div>
                </div>
                <div>
                    <div class="label">Date/Time:</div>
                    <div class="value" aria-label="Event location">{{ \Carbon\Carbon::parse($booking->event->start_date)->format('D, jS M Y') }} AT {{ \Carbon\Carbon::parse($booking->event->start_time)->format('g:i A') }}

                    </div>
                </div>
                <div>
                    <div class="label">Booking Price:</div>
                    <div class="value" aria-label="Event location">RM {{ number_format($booking->final_price, 2) }}
                    </div>
                </div>
            </article>
            <section class="qr-section" aria-label="Ticket purchase and attendee information">
                <div class="purchase-info" aria-label="Purchase date and time">PURCHASED ON
                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('M d, Y \A\t h:i A') }}
                </div>
                <!-- <img class="qr" src="https://storage.googleapis.com/a1aa/image/dbbfb779-94d9-41f3-f9bc-4220209f38f5.jpg"
                            alt="Black and white QR code square with pixel pattern" width="120" height="120" /> -->
                <div class="attendee-label">Ticket Code:</div>
                <div class="attendee-email" aria-label="Ticket number">{{ $ticket->ticket_code }}</div><br>
                <div class="attendee-label">Attendee:</div>
                <!-- <div class="attendee-ticket" aria-label="Ticket type">SAMPLE TICKET</div> -->
                <div class="attendee-name" aria-label="Attendee name">{{ $ticket->participant_name }}</div>
                <div class="attendee-email" aria-label="Attendee email">{{ $ticket->participant_no_ic }}</div><br>
                <div class="">
                    <div class="attendee-label">Ticket Price:</div>
                    <div class="attendee-email">RM {{ number_format($ticket->price, 2) }}</div>
                </div>
            </section>
            <section class="notes-section" aria-label="Notes and instructions">
                <h2>NOTES / INSTRUCTIONS</h2>
                <p>
                    Please arrive at least 15 minutes before the event starts to ensure smooth check-in. Tickets are
                    non-transferable and non-refundable.
                    The organizer ({{ $booking->event->organizer->name }}) reserves the right to refuse entry to anyone
                    violating event rules or
                    local laws.
                    By attending, you agree to comply with all health and safety guidelines provided by the organizer
                    ({{ $booking->event->organizer->name }}).
                    For questions or assistance, please contact the event support team at {{
                    $booking->event->organizer->email }}.
                </p>
            </section>
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>