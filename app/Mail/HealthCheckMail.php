<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HealthCheckMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $report) {}

    public function envelope(): Envelope
    {
        $passed = $this->report['passed'];
        $total  = $this->report['total'];
        $status = $passed === $total ? '✅ All OK' : '⚠️ Issues Found';

        return new Envelope(
            subject: "[Sistem Kami] Health Check — $status ($passed/$total) — {$this->report['ran_at']}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.health_check_report');
    }
}
