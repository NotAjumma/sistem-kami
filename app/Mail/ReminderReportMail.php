<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $report) {}

    public function envelope(): Envelope
    {
        $sent   = $this->report['total_sent'];
        $failed = $this->report['total_failed'];
        $status = $failed > 0 ? "⚠️ {$failed} Failed" : '✅ All Sent';

        return new Envelope(
            subject: mail_env_tag() . " [Sistem Kami] Reminder Report — {$status} ({$sent} sent) — {$this->report['ran_at']}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reminder_report');
    }
}
