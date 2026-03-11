<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyBookingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $report) {}

    public function envelope(): Envelope
    {
        $total = $this->report['total_bookings'];
        $date  = $this->report['date'];

        return new Envelope(
            subject: mail_env_tag() . " [Sistem Kami] Daily Booking Report — {$total} bookings — {$date}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.daily_booking_report');
    }
}
