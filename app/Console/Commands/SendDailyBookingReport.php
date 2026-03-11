<?php

namespace App\Console\Commands;

use App\Mail\DailyBookingReportMail;
use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\Organizer;
use App\Models\VisitorAction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyBookingReport extends Command
{
    protected $signature   = 'bookings:daily-report {--date= : Date to report (Y-m-d), defaults to today}';
    protected $description = 'Send daily booking summary report email';

    public function handle(): void
    {
        $to = AppSetting::get('report_email');

        if (!$to) {
            $this->info('No report_email configured in app_settings. Skipping.');
            return;
        }

        $date    = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
        $dateStr = $date->format('d M Y');
        $start   = $date->copy()->startOfDay();
        $end     = $date->copy()->endOfDay();

        $bookings = Booking::with(['organizer', 'participant', 'package', 'vendorTimeSlots', 'addons'])
            ->whereDate('created_at', $date)
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->orderBy('organizer_id')
            ->orderBy('created_at')
            ->get();

        // Pre-load visitor stats per organizer for the day
        $visitorStats = $this->getVisitorStatsByOrganizer($start, $end);

        // Group bookings by organizer
        $byOrganizer = [];
        foreach ($bookings as $booking) {
            $orgId   = $booking->organizer_id;
            $orgName = $booking->organizer->name ?? 'Unknown';

            if (!isset($byOrganizer[$orgId])) {
                $byOrganizer[$orgId] = [
                    'name'             => $orgName,
                    'total'            => 0,
                    'total_revenue'    => 0,
                    'visitors_today'   => $visitorStats[$orgId]['unique'] ?? 0,
                    'profile_views'    => $visitorStats[$orgId]['profile'] ?? 0,
                    'package_views'    => $visitorStats[$orgId]['package'] ?? 0,
                    'bookings'         => [],
                ];
            }

            $slot     = $booking->vendorTimeSlots->first();
            $slotDate = $slot ? Carbon::parse($slot->booked_date_start)->format('d M Y') : '-';
            $slotTime = $slot ? Carbon::parse($slot->booked_time_start)->format('h:i A') : '-';

            $byOrganizer[$orgId]['bookings'][] = [
                'booking_code'   => $booking->booking_code,
                'participant'    => $booking->participant->name ?? '-',
                'phone'          => $booking->participant->whatsapp_number ?: ($booking->participant->phone ?? '-'),
                'package'        => $booking->package->name ?? '-',
                'addons'         => $booking->addons->pluck('name')->implode(', '),
                'slot_date'      => $slotDate,
                'slot_time'      => $slotTime,
                'payment_type'   => $booking->payment_type,
                'amount'         => $booking->final_price ?? $booking->total_price,
                'paid_amount'    => $booking->paid_amount,
                'status'         => $booking->status,
                'payment_method' => $booking->payment_method,
                'created_at'     => Carbon::parse($booking->created_at)->setTimezone('Asia/Kuala_Lumpur')->format('h:i A'),
            ];

            $byOrganizer[$orgId]['total']++;
            $byOrganizer[$orgId]['total_revenue'] += $booking->paid_amount ?? 0;
        }

        // Also include organizers with visitors but no bookings today
        foreach ($visitorStats as $orgId => $stats) {
            if (!isset($byOrganizer[$orgId])) {
                $organizer = Organizer::find($orgId);
                if ($organizer) {
                    $byOrganizer[$orgId] = [
                        'name'           => $organizer->name,
                        'total'          => 0,
                        'total_revenue'  => 0,
                        'visitors_today' => $stats['unique'],
                        'profile_views'  => $stats['profile'],
                        'package_views'  => $stats['package'],
                        'bookings'       => [],
                    ];
                }
            }
        }

        // Sort by total bookings desc
        uasort($byOrganizer, fn($a, $b) => $b['total'] <=> $a['total']);

        $report = [
            'date'           => $dateStr,
            'total_bookings' => $bookings->count(),
            'total_revenue'  => $bookings->sum('paid_amount'),
            'total_visitors' => array_sum(array_column($visitorStats, 'unique')),
            'organizers'     => array_values($byOrganizer),
        ];

        try {
            Mail::to($to)->send(new DailyBookingReportMail($report));
            $this->info("Daily booking report sent to {$to} ({$bookings->count()} bookings on {$dateStr})");
        } catch (\Exception $e) {
            Log::error('Failed to send daily booking report', ['error' => $e->getMessage()]);
            $this->warn("Could not send daily booking report: {$e->getMessage()}");
        }
    }

    /**
     * Returns visitor stats keyed by organizer_id.
     * Uses the same logic as OrganizerController::getTotalVisitsToday().
     */
    private function getVisitorStatsByOrganizer(Carbon $start, Carbon $end): array
    {
        // Profile visits: action=visit_page, page=profile, reference_id=organizer_id
        $profileVisits = VisitorAction::select('reference_id', DB::raw('COUNT(DISTINCT visitor_id) as cnt'))
            ->where('action', 'visit_page')
            ->where('page', 'profile')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('reference_id')
            ->pluck('cnt', 'reference_id')
            ->toArray();

        // Package views: action=view_package, reference_id=package_id → join to get organizer_id
        $packageViews = VisitorAction::select('packages.organizer_id', DB::raw('COUNT(DISTINCT visitor_actions.visitor_id) as cnt'))
            ->join('packages', 'packages.id', '=', 'visitor_actions.reference_id')
            ->where('visitor_actions.action', 'view_package')
            ->whereBetween('visitor_actions.created_at', [$start, $end])
            ->groupBy('packages.organizer_id')
            ->pluck('cnt', 'organizer_id')
            ->toArray();

        // Merge into per-organizer stats
        $allOrgIds = array_unique(array_merge(array_keys($profileVisits), array_keys($packageViews)));
        $stats = [];

        foreach ($allOrgIds as $orgId) {
            $profile = (int) ($profileVisits[$orgId] ?? 0);
            $package = (int) ($packageViews[$orgId] ?? 0);

            // Unique = max of the two (visitor may have done both; not double-counting)
            $stats[(int) $orgId] = [
                'profile' => $profile,
                'package' => $package,
                'unique'  => max($profile, $package),
            ];
        }

        return $stats;
    }
}
