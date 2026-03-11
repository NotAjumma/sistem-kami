<?php

namespace App\Console\Commands;

use App\Mail\ReminderReportMail;
use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\Organizer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature   = 'reminders:send';
    protected $description = 'Send WhatsApp reminders for bookings starting within 12 hours via Fonnte API';

    public function handle(): void
    {
        $now    = Carbon::now();
        $hour   = (int) $now->format('H');
        // Look back 2 hours to catch slots missed due to scheduler downtime/restarts
        $start  = Carbon::now()->subHours(2);
        $cutoff = Carbon::now()->addHours(12);

        $organizers = Organizer::whereNotNull('fonnte_token')
            ->where('fonnte_token', '!=', '')
            ->get();

        $report = [
            'ran_at'        => $now->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A'),
            'total_sent'    => 0,
            'total_failed'  => 0,
            'total_skipped' => 0,
            'organizers'    => [],
        ];

        if ($organizers->isEmpty()) {
            $this->info('No organizers with Fonnte token configured.');
            $this->sendReport($report);
            return;
        }

        foreach ($organizers as $organizer) {
            $quietStart = (int) ($organizer->reminder_quiet_start ?? 0);
            $quietEnd   = (int) ($organizer->reminder_quiet_end ?? 6);

            if ($this->isDuringQuietHours($hour, $quietStart, $quietEnd)) {
                $this->info("Organizer {$organizer->name}: quiet hours ({$quietStart}:00–{$quietEnd}:00). Skipping.");
                $report['total_skipped']++;
                continue;
            }

            $bookings = Booking::with(['participant', 'package', 'vendorTimeSlots'])
                ->where('organizer_id', $organizer->id)
                ->whereNotIn('status', ['cancelled'])
                ->whereNull('reminder_sent_at')
                ->whereHas('vendorTimeSlots', function ($q) use ($start, $cutoff) {
                    $q->whereRaw("CONCAT(booked_date_start, ' ', booked_time_start) BETWEEN ? AND ?", [
                        $start->toDateTimeString(),
                        $cutoff->toDateTimeString(),
                    ]);
                })
                ->get();

            $orgEntry = [
                'name'     => $organizer->name,
                'sent'     => 0,
                'failed'   => 0,
                'bookings' => [],
            ];

            foreach ($bookings as $booking) {
                $result = $this->sendReminder($booking, $organizer);

                $slot     = $booking->vendorTimeSlots->first();
                $slotDate = $slot ? Carbon::parse($slot->booked_date_start)->format('d M Y') : '-';
                $slotTime = $slot ? Carbon::parse($slot->booked_time_start)->format('h:i A') : '-';

                $orgEntry['bookings'][] = [
                    'booking_code' => $booking->booking_code,
                    'participant'  => $booking->participant->name ?? '-',
                    'phone'        => $result['phone'],
                    'package'      => $booking->package->name ?? '-',
                    'slot_date'    => $slotDate,
                    'slot_time'    => $slotTime,
                    'status'       => $result['success'] ? 'sent' : 'failed',
                    'wa_message'   => $result['message'],
                ];

                if ($result['success']) {
                    $booking->update(['reminder_sent_at' => now()]);
                    $this->info("Reminder sent for booking {$booking->booking_code}");
                    $orgEntry['sent']++;
                    $report['total_sent']++;
                } else {
                    $this->warn("Failed to send reminder for booking {$booking->booking_code}");
                    $orgEntry['failed']++;
                    $report['total_failed']++;
                }

                // Random delay 5–15 seconds between each message to avoid WA ban
                sleep(rand(5, 15));
            }

            $report['organizers'][] = $orgEntry;
        }

        $this->info('Done.');
        $this->sendReport($report);
    }

    private function sendReport(array $report): void
    {
        $to = AppSetting::get('report_email');

        if (!$to || !configure_resend_mailer()) {
            return;
        }

        try {
            Mail::to($to)->send(new ReminderReportMail($report));
            $this->info("Report emailed to {$to}");
        } catch (\Exception $e) {
            Log::error('Failed to send reminder report email', ['error' => $e->getMessage()]);
            $this->warn("Could not send report email: {$e->getMessage()}");
        }
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

    private function sendReminder(Booking $booking, Organizer $organizer): array
    {
        $fail = fn(string $msg = '') => ['success' => false, 'message' => $msg, 'phone' => ''];

        $participant = $booking->participant;
        $phone       = $participant->whatsapp_number ?: $participant->phone;

        if (!$phone) {
            return $fail();
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

        // ======================
        // Google Maps Link Logic
        // ======================

        if ($organizer->is_gmaps_verified) {

            // guna nama bisnes
            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" .
                urlencode($organizer->office_name);

        } elseif ($organizer->latitude && $organizer->longitude) {

            // guna lat long
            $mapsUrl = "https://www.google.com/maps?q={$organizer->latitude},{$organizer->longitude}";

        } else {

            // fallback guna full address
            $fullAddress = $organizer->address_line1 . ', ' .
                $organizer->postal_code . ' ' .
                $organizer->city . ', ' .
                $organizer->state;

            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" .
                urlencode($fullAddress);
        }

        $locationLine = '';
        if ($address) {
            $locationLine = "📍 Lokasi: {$address}";
            if ($organizer->latitude && $organizer->longitude) {
                $locationLine .= " {$mapsUrl}";
            }
        }

        $balance  = ($booking->total_price + ($booking->service_charge ?? 0) - ($booking->discount ?? 0)) - $booking->paid_amount;

        $lines = [
            "Hai {$participant->name}! 👋",
            "",
            "Ini adalah peringatan bahawa tempahan anda akan bermula tidak lama lagi!",
            "",
            "Pakej: " . ($booking->package->name ?? '-'),
        ];

        if ($booking->payment_type === 'deposit' && $balance > 0) {
            $lines[] = "💰 Baki: RM" . number_format($balance, 2);
        }

        $lines[] = "";
        $lines[] = "📅 Tarikh: {$slotDate}";
        $lines[] = "⏰ Masa: {$slotTime}{$slotEnd}";

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

        if ($organizer->payment_qr_path && $booking->payment_type === 'deposit' && $balance > 0) {
            $lines[] = "";
            $lines[] = "💳 QR Kod Pembayaran:";
            $lines[] = $organizer->payment_qr_url;
        }

        $message = implode("\n", $lines);

        try {
            $response = Http::withHeaders([
                'Authorization' => $organizer->fonnte_token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target'      => $phone,
                'message'     => $message,
                'countryCode' => '60',
            ]);

            if (!$response->successful() || ($response->json('status') !== true && $response->json('status') !== 'true')) {
                Log::warning('Fonnte reminder failed', [
                    'booking'  => $booking->booking_code,
                    'response' => $response->json(),
                ]);
                return $fail($message);
            }

            return ['success' => true, 'message' => $message, 'phone' => $phone];

        } catch (\Exception $e) {
            Log::error('Fonnte reminder exception', [
                'booking' => $booking->booking_code,
                'error'   => $e->getMessage(),
            ]);
            return $fail($message);
        }
    }
}

