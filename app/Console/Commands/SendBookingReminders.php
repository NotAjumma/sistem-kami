<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Organizer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    protected $signature   = 'reminders:send';
    protected $description = 'Send WhatsApp reminders for bookings starting within 12 hours via Fonnte API';

    public function handle(): void
    {
        $now    = Carbon::now();
        $hour   = (int) $now->format('H');
        $cutoff = Carbon::now()->addHours(12);

        $organizers = Organizer::whereNotNull('fonnte_token')
            ->where('fonnte_token', '!=', '')
            ->get();

        if ($organizers->isEmpty()) {
            $this->info('No organizers with Fonnte token configured.');
            return;
        }

        foreach ($organizers as $organizer) {
            $quietStart = (int) ($organizer->reminder_quiet_start ?? 0);
            $quietEnd   = (int) ($organizer->reminder_quiet_end ?? 6);

            if ($this->isDuringQuietHours($hour, $quietStart, $quietEnd)) {
                $this->info("Organizer {$organizer->name}: quiet hours ({$quietStart}:00–{$quietEnd}:00). Skipping.");
                continue;
            }

            $bookings = Booking::with(['participant', 'package', 'vendorTimeSlots'])
                ->where('organizer_id', $organizer->id)
                ->whereNotIn('status', ['cancelled'])
                ->whereNull('reminder_sent_at')
                ->whereHas('vendorTimeSlots', function ($q) use ($now, $cutoff) {
                    $q->whereRaw("CONCAT(booked_date_start, ' ', booked_time_start) BETWEEN ? AND ?", [
                        $now->toDateTimeString(),
                        $cutoff->toDateTimeString(),
                    ]);
                })
                ->get();

            foreach ($bookings as $booking) {
                $sent = $this->sendReminder($booking, $organizer);

                if ($sent) {
                    $booking->update(['reminder_sent_at' => now()]);
                    $this->info("Reminder sent for booking {$booking->booking_code}");
                } else {
                    $this->warn("Failed to send reminder for booking {$booking->booking_code}");
                }

                // Random delay 5–15 seconds between each message to avoid WA ban
                sleep(rand(5, 15));
            }
        }

        $this->info('Done.');
    }

    private function isDuringQuietHours(int $hour, int $start, int $end): bool
    {
        if ($start === $end) {
            return false; // same value = quiet hours disabled
        }
        if ($start < $end) {
            return $hour >= $start && $hour < $end;
        }
        // Wraps midnight: e.g. start=22, end=6
        return $hour >= $start || $hour < $end;
    }

    private function sendReminder(Booking $booking, Organizer $organizer): bool
    {
        $participant = $booking->participant;
        $phone       = $participant->whatsapp_number ?: $participant->phone;

        if (!$phone) {
            return false;
        }

        // Normalize phone: strip leading 0, prefix 60
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '60' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '60')) {
            $phone = '60' . $phone;
        }

        // Build slot info
        $slot      = $booking->vendorTimeSlots->first();
        $slotDate  = $slot ? Carbon::parse($slot->booked_date_start)->format('d M Y') : '-';
        $slotTime  = $slot ? Carbon::parse($slot->booked_time_start)->format('h:i A') : '';
        $slotEnd   = ($slot && $slot->booked_time_end) ? ' - ' . Carbon::parse($slot->booked_time_end)->format('h:i A') : '';

        // Build location line
        $addressParts = array_filter([
            $organizer->office_name,
            $organizer->address_line1,
            $organizer->address_line2,
            $organizer->city,
            $organizer->state,
        ]);
        $address = implode(', ', $addressParts);

        $locationLine = '';
        if ($address) {
            $locationLine = "📍 Lokasi: {$address}";
            if ($organizer->latitude && $organizer->longitude) {
                $locationLine .= "\nhttps://maps.google.com/?q={$organizer->latitude},{$organizer->longitude}";
            }
        }

        $lines = [
            "Hai {$participant->name}! 👋",
            "",
            "Ini adalah peringatan bahawa tempahan anda akan bermula tidak lama lagi!",
            "",
            "Pakej: " . ($booking->package->name ?? '-'),
            "",
            "📅 Tarikh: {$slotDate}",
            "⏰ Masa: {$slotTime}{$slotEnd}",
        ];

        if ($locationLine) {
            $lines[] = "";
            $lines[] = $locationLine;
        }

        $lines[] = "";
        $lines[] = "📝 Peringatan:";
        $lines[] = "• Hadir 15 minit awal";
        $lines[] = "• Sampin, tudung & kasut siap dipakai";
        $lines[] = "• Lewat = tiada masa tambahan";
        $lines[] = "";
        $lines[] = "Kerjasama anda amat dihargai. Jumpa nanti! 😊";
        $lines[] = "- {$organizer->name}";

        $message = implode("\n", $lines);

        try {
            $response = Http::withHeaders([
                'Authorization' => $organizer->fonnte_token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'      => $phone,
                'message'     => $message,
                'countryCode' => '60',
            ]);

            if ($response->successful() && ($response->json('status') === true || $response->json('status') === 'true')) {
                return true;
            }

            Log::warning('Fonnte reminder failed', [
                'booking' => $booking->booking_code,
                'response' => $response->json(),
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Fonnte reminder exception', [
                'booking' => $booking->booking_code,
                'error'   => $e->getMessage(),
            ]);
            return false;
        }
    }
}
