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
use App\Models\Booking;

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

        $isEligibleForShirt = Booking::where('event_id', $this->booking->event_id)
        ->where('status', 'confirmed')
        ->orderBy('created_at', 'asc')
        ->limit(100)
        ->pluck('id')
        ->contains($this->booking->id);

        // Now generate PDF and email view with these formatted fields
        $pdf = Pdf::loadView('emails.ticket_pdf', ['booking' => $this->booking]);

        return $this->subject('Payment Confirmation')
            ->view('emails.payment_confirmed')
            ->with([
                'booking' => $this->booking,
                'isEligibleForShirt' => $isEligibleForShirt,
            ])
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
