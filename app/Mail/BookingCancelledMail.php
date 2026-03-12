<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Booking $booking, public readonly float $refundAmount) {}

    public function envelope(): Envelope
    {
        $code      = $this->booking->booking_code;
        $organizer = $this->booking->organizer->name ?? '-';

        return new Envelope(
            subject: mail_env_tag() . " [{$organizer}] [Cancelled/Refund] {$code}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking_cancelled');
    }
}
