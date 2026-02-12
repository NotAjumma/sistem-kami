<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\EmailLog;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Package;
use App\Models\VisitorAction;
use App\Models\BookingsVendorTimeSlot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        $page_title = 'Dashboard';
        $authUser = auth()->guard('organizer')->user()->load('user');
        $organizerId = $authUser->id;

        // Get first 100 confirmed bookings with shirt_size info
        // $shirtSizeData = Booking::where('organizer_id', $authUser->id)
        //     ->where('status', 'confirmed')
        //     ->take(100)
        //     ->get()
        //     ->map(function ($booking) {
        //         $extraRaw = $booking->extra_info;

        //         // Skip if null or empty
        //         if (empty($extraRaw))
        //             return null;

        //         // Decode if it's a string (JSON)
        //         $extra = is_string($extraRaw) ? json_decode($extraRaw, true) : (array) $extraRaw;

        //         // Skip if not array or doesn't contain shirt_size
        //         if (!is_array($extra) || empty($extra['shirt_size']))
        //             return null;

        //         return $extra['shirt_size'];
        //     })
        //     ->filter()
        //     ->countBy();

        // $shirtSizes = $shirtSizeData->keys();
        // $shirtCounts = $shirtSizeData->values();

        $shirtSizeData = [];
        // Basic stats
        $totalEvents = Event::where('organizer_id', $organizerId)->count();
        $ticketSold = BookingTicket::whereIn('status', ['printed', 'checkin'])
            ->whereHas('booking', fn($q) => $q->where('organizer_id', $organizerId)->where('status', 'confirmed'))
            ->count();
        $totalBookings = Booking::where('organizer_id', $organizerId)->where('status', 'paid')->count();
        $totalIncome = Booking::where('organizer_id', $organizerId)->where('status', 'paid')->sum('final_price');
        // Total income already received
        $paidIncome = Booking::where('status', 'paid')
            ->where('payment_type', 'full_payment') // full payment received
            ->sum('final_price');

        // Total deposit received
        $depositIncome = Booking::where('status', 'paid')
            ->where('payment_type', 'deposit') // only deposit received
            ->sum('paid_amount'); // assuming you store deposit separately

        // Total income expected (remaining balance)
        $pendingIncome = Booking::where('status', 'paid')
            ->sum('final_price') - ($paidIncome + $depositIncome);
        $pendingBookings = Booking::where('organizer_id', $organizerId)->where('status', 'pending')->count();
        $confirmBookings = Booking::where('organizer_id', $organizerId)->where('status', 'confirmed')->count();

        // Get top 3 events by booking count
        $topEvents = Event::where('organizer_id', $organizerId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        $salesChartData = [];

        foreach ($topEvents as $event) {
            $monthlySales = Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as total')
                ->where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            $salesChartData[] = [
                'name' => $event->title,
                'className' => 'bg-primary',
                'data' => collect(range(1, 12))->map(fn($m) => (int) ($monthlySales[$m] ?? 0))->toArray()
            ];
        }

        $totalPackages = Package::active()
            ->where('organizer_id', $organizerId)
            ->count();

        $totalSlotBooked = BookingsVendorTimeSlot::paidStatuses()
            ->whereHas('booking', fn($q) => $q->where('organizer_id', $organizerId)->where('status', 'paid'))
            ->count();

        $totalUpcomingSlots = BookingsVendorTimeSlot::paidStatuses()
            ->whereHas('booking', fn($q) => 
                $q->where('organizer_id', $organizerId)
                ->where('status', 'paid')
            )
            ->where('booked_date_start', '>=', Carbon::today()) // only future or today slots
            ->count();

        // Chart yearly

        // $salesChartData = [];

        // foreach ($topPackages as $package) {
        //     $monthlySales = Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as total')
        //         ->where('package_id', $package->id)
        //         ->where('status', 'paid')
        //         ->groupBy(DB::raw('MONTH(created_at)'))
        //         ->pluck('total', 'month');

        //     $salesChartData[] = [
        //         'name' => $package->name,
        //         'className' => 'bg-success', // or any color
        //         'data' => collect(range(1, 12))->map(fn($m) => (int) ($monthlySales[$m] ?? 0))->toArray()
        //     ];
        // }

        $chartDataJson = json_encode($salesChartData);


        return view('organizer.index', compact(
            'page_title',
            'authUser',
            'totalEvents',
            'ticketSold',
            'totalBookings',
            'totalIncome',
            'paidIncome',
            'pendingIncome',
            'depositIncome',
            'confirmBookings',
            'pendingBookings',
            'salesChartData',
            'shirtSizeData',
            'totalSlotBooked',
            'totalPackages',
            'totalUpcomingSlots',
        ));
    }

    public function getTotalVisitsToday()
    {
        $organizerId = auth()->guard('organizer')->id();

        // Get package IDs for this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $organizerId)
            ->pluck('id');

        // Date range for today
        $start = Carbon::today()->startOfDay();
        $end   = Carbon::today()->endOfDay();

        // Combined query
        $totalVisitToday = VisitorAction::whereBetween('created_at', [$start, $end])
            ->where(function ($query) use ($organizerId, $packageIds) {
                $query->where(function ($q) use ($organizerId) {
                    $q->where('action', 'visit_page')
                    ->where('page', 'profile')
                    ->where('reference_id', $organizerId);
                })
                ->orWhere(function ($q) use ($packageIds) {
                    if ($packageIds->isNotEmpty()) {
                        $q->where('action', 'view_package')
                        ->whereIn('reference_id', $packageIds);
                    }
                });
            })
            ->distinct('visitor_id')
            ->count('visitor_id');

        return response()->json([
            'total_visits_today' => $totalVisitToday
        ]);
    }

   public function getSalesChartData()
    {
        $organizerId = auth()->guard('organizer')->id();

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        // Generate labels once
        $labels = collect(range(0, 29))->map(function ($i) use ($startDate) {
            return $startDate->copy()->addDays($i)->format('d M');
        })->toArray();

        // Get all package IDs first
        $packages = Package::where('organizer_id', $organizerId)
            ->active()
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        $salesChartData = [];

        foreach ($packages as $package) {

            $dailyData = Booking::selectRaw('DATE(created_at) as date, SUM(final_price) as total_sales, COUNT(*) as total_bookings')
                ->where('package_id', $package->id)
                ->where('status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->get()
                ->keyBy('date');

            $sales = [];
            $bookings = [];

            for ($i = 0; $i < 30; $i++) {
                $dateKey = $startDate->copy()->addDays($i)->format('Y-m-d');

                $sales[] = (float) ($dailyData[$dateKey]->total_sales ?? 0);
                $bookings[] = (int) ($dailyData[$dateKey]->total_bookings ?? 0);
            }

            $salesChartData[] = [
                'name' => $package->name,
                'data' => $sales,
                'bookings' => $bookings,
            ];
        }

        return response()->json([
            'labels' => $labels,
            'series' => $salesChartData,
        ]);
    }

    public function bookings(Request $request)
    {
        $page_title = 'Bookings List';
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Fetch events for the current organizer to populate dropdown
        $events = DB::table('events')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $eventIds = DB::table('events')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs from those events
        $ticketIds = DB::table('tickets')
            ->whereIn('event_id', $eventIds)
            ->pluck('id');

        // Step 3: Get all booking IDs from booking_tickets
        $bookingIds = DB::table('booking_tickets')
            ->whereIn('ticket_id', $ticketIds)
            ->pluck('booking_id');

        // Step 4: Fetch bookings with optional status & search filters
        $bookings = Booking::with(['bookingTickets', 'participant', 'event:id,title'])
            ->whereIn('id', $bookingIds)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('event', function ($q) use ($event_search) {
                    $q->where('title', $event_search);
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('participant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('no_ic', 'like', "%{$search}%")
                            ->orWhere('booking_code', 'like', "%{$search}%");
                    });
                });
            })

            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('organizer.booking.index', compact('page_title', 'authUser', 'bookings', 'events'));
    }

    public function actionLogsChart()
    {
        $days = 30;
        $today = Carbon::today();

        // Labels for last 30 days
        $labels = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $labels[] = $today->copy()->subDays($i)->format('d M');
        }

        $authUser = auth()->guard('organizer')->user();
        $authId = $authUser->id;

        // Packages belonging to this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authId)
            ->pluck('id');

        // Visitor actions per day
        $logs = DB::table('visitor_actions')
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("COUNT(DISTINCT CASE WHEN action = 'visit_page' AND page = 'profile' AND reference_id = $authId THEN visitor_id END) as profile_visits"),
                DB::raw("COUNT(DISTINCT CASE WHEN action = 'whatsapp_click' AND " . ($packageIds->isNotEmpty() ? 'reference_id IN (' . $packageIds->implode(',') . ')' : '0=1') . " THEN visitor_id END) as whatsapp_clicks")
            )
            ->where('created_at', '>=', $today->copy()->subDays($days - 1))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build arrays for chart
        $profileVisits = [];
        $whatsappClicks = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dateKey = $today->copy()->subDays($i)->toDateString();
            $profileVisits[] = $logs[$dateKey]->profile_visits ?? 0;
            $whatsappClicks[] = $logs[$dateKey]->whatsapp_clicks ?? 0;
        }

        // Package views
        $packages = DB::table('visitor_actions as va')
            ->join('packages as p', 'va.reference_id', '=', 'p.id')
            ->select(
                'p.name as package_name',
                DB::raw("DATE(va.created_at) as date"),
                DB::raw("COUNT(DISTINCT va.visitor_id) as views")
            )
            ->where('va.action', 'view_package')
            ->whereIn('va.reference_id', $packageIds)
            ->where('va.created_at', '>=', $today->copy()->subDays($days - 1))
            ->groupBy('p.name', 'date')
            ->orderBy('p.name')
            ->orderBy('date')
            ->get();

        $packageNames = $packages->pluck('package_name')->unique();

        $packageSeries = [];
        foreach ($packageNames as $packageName) {
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $dateKey = $today->copy()->subDays($i)->toDateString();
                $row = $packages->firstWhere(fn($item) => $item->package_name == $packageName && $item->date == $dateKey);
                $data[] = $row->views ?? 0;
            }
            $packageSeries[] = [
                'name' => $packageName,
                'data' => $data
            ];
        }

        return response()->json([
            'labels' => $labels,
            'series' => array_merge(
                [
                    ['name' => 'Profile Visits', 'data' => $profileVisits],
                    ['name' => 'WhatsApp Clicks', 'data' => $whatsappClicks]
                ],
                $packageSeries
            )
        ]);
    }

    public function ticketsConfirmed(Request $request)
    {
        $page_title = 'Tickets List Confirmed';
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Fetch events for the current organizer to populate dropdown
        $events = DB::table('events')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $eventIds = DB::table('events')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs for those events
        $ticketIds = DB::table('tickets')
            ->whereIn('event_id', $eventIds)
            ->pluck('id');

        // Step 3: Fetch booking tickets with related ticket, event, and booking
        $bookingTickets = BookingTicket::with([
            'ticket.event:id,title',
            'booking.bookingTickets'
        ])
            ->whereIn('ticket_id', $ticketIds)
            ->whereHas('booking', function ($query) {
                $query->where('status', 'confirmed');
            })
            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('ticket.event', function ($q) use ($event_search) {
                    $q->where('title', $event_search);
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search bookingTickets participant fields OR ticket_code
                    $q->where('ticket_code', 'like', "%{$search}%")
                        ->orWhereHas('booking.bookingTickets', function ($qq) use ($search) {
                        $qq->where('participant_name', 'like', "%{$search}%")
                            ->orWhere('participant_email', 'like', "%{$search}%")
                            ->orWhere('participant_phone', 'like', "%{$search}%")
                            ->orWhere('participant_no_ic', 'like', "%{$search}%");
                    })
                        // Search booking_code on booking
                        ->orWhereHas('booking', function ($qq) use ($search) {
                        $qq->where('booking_code', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        \Log::info($bookingTickets);

        return view('organizer.booking.ticket_confirmed', compact('page_title', 'authUser', 'bookingTickets', 'events'));
    }

    public function verifyPayment($id)
    {
        // $booking = Booking::with(['participant', 'bookingTickets', 'event.organizers'])->findOrFail($id);
        $booking = Booking::with(['participant', 'bookingTickets', 'event.organizer'])->findOrFail($id);


        if ($booking->payment_method === 'gform' && $booking->status !== 'confirmed') {
            $booking->status = 'confirmed';
            $booking->save();

            $booking->bookingTickets()->update(['status' => 'printed']);

            $booking->load('bookingTickets');

            // Send email to booking's email
            Mail::to($booking->participant->email)->send(new PaymentConfirmed($booking));

            EmailLog::create([
                'to_email' => $booking->participant->email,
                'type' => 'payment_confirmed',
                'sent_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Payment verified and email has been send.');
        }

        return redirect()->back()->with('error', 'Cannot verify this booking.');
    }

    public function cancelBooking($id)
    {
        $booking = Booking::with(['participant', 'bookingTickets', 'event.organizer'])->findOrFail($id);

        if ($booking->status !== 'cancelled') {
            $booking->status = 'cancelled';
            $booking->save();

            // Optionally update related ticket status
            $booking->bookingTickets()->update(['status' => 'cancelled']);

            return redirect()->back()->with('success', 'Booking has been cancelled.');
        }

        return redirect()->back()->with('error', 'This booking is already cancelled.');
    }

    public function ticketCheckin($id)
    {
        $bookingTicket = BookingTicket::with('booking')->findOrFail($id);

        if ($bookingTicket->booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Cannot check in ticket because booking is not confirmed.');
        }

        $bookingTicket->status = 'checkin';
        $bookingTicket->checked_in_at = Now();
        $bookingTicket->save();

        return redirect()->back()->with('success', 'Ticket successfully checked in.');
    }


}