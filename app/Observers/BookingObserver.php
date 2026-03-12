<?php

namespace App\Observers;

use App\Mail\BookingCancelledMail;
use App\Models\AppSetting;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    public function updated(Booking $booking): void
    {
        // Only fire when status just changed to cancelled
        if (!$booking->wasChanged('status') || $booking->status !== 'cancelled') {
            return;
        }

        $to = AppSetting::get('report_email');

        if (!$to || !configure_resend_mailer()) {
            return;
        }

        try {
            $booking->load(['organizer', 'participant', 'package', 'vendorTimeSlots.vendorTimeSlot']);

            // Refund amount = whatever was paid (mirrors cancelBooking logic)
            $refundAmount = (float) ($booking->paid_amount ?? 0);

            Mail::to($to)->send(new BookingCancelledMail($booking, $refundAmount));
        } catch (\Exception $e) {
            Log::error('Failed to send booking cancelled email', [
                'booking' => $booking->booking_code,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
