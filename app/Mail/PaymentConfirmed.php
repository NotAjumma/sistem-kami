<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    /**
     * Create a new message instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        $event = $this->booking->event;

        // Format start_date
        $startDate = Carbon::parse($event->start_date);
        $startDay = str_pad($startDate->format('j'), 2, '0', STR_PAD_LEFT);
        $startSuffix = $startDate->format('S');
        $event->formatted_start_date = $startDate->format('D') . ', ' . $startDay . $startSuffix . ' ' . $startDate->format('M Y');

        // Format end_date
        $endDate = Carbon::parse($event->end_date);
        $endDay = str_pad($endDate->format('j'), 2, '0', STR_PAD_LEFT);
        $endSuffix = $endDate->format('S');
        $event->formatted_end_date = $endDate->format('D') . ', ' . $endDay . $endSuffix . ' ' . $endDate->format('M Y');

        // Convert start_time and end_time from 24h to 12h format
        $event->formatted_start_time = Carbon::createFromFormat('H:i:s', $event->start_time)->format('g:i A');
        $event->formatted_end_time = Carbon::createFromFormat('H:i:s', $event->end_time)->format('g:i A');

        // Month (3 letter)
        $event->date_month = $startDate->format('M'); // e.g. Oct

        // Day (2 digit) for start and end
        $startDay = $startDate->format('d'); // e.g. 05
        $endDay = $endDate->format('d');     // e.g. 06

        // If single day event, just show one day
        $event->date_days = ($startDay === $endDay) ? $startDay : ($startDay . ' - ' . $endDay);

        // Now generate PDF and email view with these formatted fields
        $pdf = Pdf::loadView('emails.ticket_pdf', ['booking' => $this->booking]);

        return $this->subject('Payment Confirmation')
            ->view('emails.payment_confirmed')
            ->with(['booking' => $this->booking])
            ->attachData($pdf->output(), 'tickets.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmed',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
