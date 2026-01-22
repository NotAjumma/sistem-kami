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
        $package = $this->booking->package;

        // Now generate PDF and email view with these formatted fields
        // $pdf = Pdf::loadView('emails.ticket_pdf', ['booking' => $this->booking]);

        \Log::info($this->booking);
        return $this->subject('Payment Confirmation')
            ->view('emails.payment_confirmed_package')
            ->with([
                'booking' => $this->booking,
            ]);
            // ->attachData($pdf->output(), 'tickets.pdf', [
            //     'mime' => 'application/pdf',
            // ]);
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
