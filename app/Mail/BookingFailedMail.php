<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $organizerName,
        public readonly string $errorMessage,
        public readonly array  $context = [],
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: mail_env_tag() . " [Sistem Kami] ⚠️ Booking Creation Failed — {$this->organizerName}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking_failed');
    }
}
