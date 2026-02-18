<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\EmailLog;
use App\Models\Ticket;
use App\Models\Package;
use App\Models\Event;
use App\Models\VendorTimeSlot;
use App\Models\VendorTimeSlotLimit;
use App\Models\VendorOffDay;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Http;

class OrganizerBusinessController extends Controller
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

        // Basic stats
        $totalEvents = Event::where('organizer_id', $organizerId)->count();
        $ticketSold = BookingTicket::whereIn('status', ['printed', 'checkin'])
            ->whereHas('booking', fn($q) => $q->where('organizer_id', $organizerId)->where('status', 'confirmed'))
            ->count();
        $totalBookings = Booking::where('organizer_id', $organizerId)->count();
        $totalIncome = Booking::where('organizer_id', $organizerId)->where('status', 'confirmed')->sum('final_price');
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

        $topPackages = Package::where('organizer_id', $organizerId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        $salesChartData = [];

        foreach ($topPackages as $package) {
            $monthlySales = Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as total')
                ->where('package_id', $package->id)
                ->where('status', 'paid')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            $salesChartData[] = [
                'name' => $package->name,
                'className' => 'bg-success', // or any color
                'data' => collect(range(1, 12))->map(fn($m) => (int) ($monthlySales[$m] ?? 0))->toArray()
            ];
        }


        return view('organizer.index', compact(
            'page_title',
            'authUser',
            'totalEvents',
            'ticketSold',
            'totalBookings',
            'totalIncome',
            'confirmBookings',
            'pendingBookings',
            'salesChartData',
        ));
    }

    public function bookings(Request $request)
    {
        $page_title = 'Bookings List';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $events = null;
        // Fetch events for the current organizer to populate dropdown
        $packages = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'name')
            ->orderBy('order_by')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs from those events
        $bookingVendorTimeSlots = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        $bookingIds = DB::table('bookings')
            ->whereIn('id', $bookingVendorTimeSlots)
            ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $bookings = Booking::with(['vendorTimeSlots', 'participant', 'package:id,name'])
            ->whereIn('id', $bookingIds)

            ->when($request->status, 
                fn($q) => $q->where('status', $request->status)
            )

            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('package', function ($q) use ($event_search) {
                    $q->where('name', $event_search);
                });
            })

            ->when($request->date, function ($query, $date) {
                $query->whereHas('vendorTimeSlots', function ($q) use ($date) {
                    $q->whereDate('booked_date_start', $date);
                });
            })

            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('participant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('no_ic', 'like', "%{$search}%");
                    })
                    ->orWhere('booking_code', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(15)
            ->withQueryString();

            // Log::info("bookings");
            // Log::info($bookings);
        return view('organizer.booking.index', compact('page_title', 'authUser', 'bookings', 'packages', 'events'));
    }

    public function showPackages(Request $request)
    {
        $page_title = 'Packages List';
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        $categoriesIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('category_id');

        // Fetch categories for the current organizer to populate dropdown
        $categories = DB::table('package_categories')
            ->whereIn('id', $categoriesIds)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Step 2: Get all ticket IDs from those events
        $bookings = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        // $bookingIds = DB::table('bookings')
        //     ->whereIn('id', $bookingVendorTimeSlots)
        //     ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $packages = Package::with(['items', 'category', 'addons'])
            ->whereIn('id', $packageIds)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            // ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category_search, function ($query, $category_search) {
                $query->whereHas('category', function ($q) use ($category_search) {
                    $q->where('name', $category_search);
                });
            })
            ->latest()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('organizer.package.index', compact('page_title', 'authUser', 'packages' ,'categories'));
    }

    public function showCreatePackage(Request $request)
    {
        $page_title = 'Create Package';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $categories = DB::table('package_categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('organizer.package.create', compact('page_title', 'authUser', 'categories'));

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
        $booking = Booking::with(['participant', 'vendorTimeSlots'])->findOrFail($id);

        if ($booking->status !== 'cancelled') {
            $booking->status = 'cancelled';
            $booking->save();

            // Optionally update related ticket status
            $booking->vendorTimeSlots()->update(['status' => 'cancelled']);

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

    public function fetchCalendarData($id)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Find the package under this organizer
        $package = Package::whereHas('organizer', function ($query) use ($authUser) {
            $query->where('id', $authUser->id);
        })
            ->where('id', $id)
            ->with([
                'addons',
                'items',
                'discounts',
                'category',
                'images',
                'organizer',
            ])
            ->firstOrFail();

        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $authUser->id)
            ->where('package_id', $package->id)
            ->whereIn('status', ['deposit_paid', 'full_payment'])
            ->whereDate('booked_date_start', '>=', now())
            ->select('vendor_time_slot_id', 'booked_date_start', 'booked_date_end', 'booked_time_start', 'booked_time_end')
            ->get()
            ->map(function ($row) {
                return [
                    'date_start' => Carbon::parse($row->booked_date_start)->format('Y-m-d'),
                    'date_end'   => Carbon::parse($row->booked_date_end)->format('Y-m-d'),
                    'time_start' => $row->booked_time_start,
                    'time_end'   => $row->booked_time_end,
                    'vendor_time_slot_id' => $row->vendor_time_slot_id,
                ];
            });

        $timeSlots = VendorTimeSlot::where('organizer_id', $authUser->id)
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        $totalSlotCount = 0;

        foreach ($timeSlots as $slot) {
            if ($slot->is_full_day) {
                $totalSlotCount += 1;
                continue;
            }

            $start  = Carbon::parse($slot->start_time);
            $end    = Carbon::parse($slot->end_time);

            if (!$package->duration_minutes || $end->lessThanOrEqualTo($start)) {
                continue;
            }

            // Compute how many intervals fit between start and end
            $diffInMinutes      = $end->diffInMinutes($start);
            $slotsInThisPeriod  = intdiv($diffInMinutes, $package->duration_minutes);

            $totalSlotCount += $slotsInThisPeriod;
        }

        $fullyBookedDates       = [];
        $groupedByDate          = collect($bookedDates)->groupBy('date_start');

        foreach ($groupedByDate as $date => $bookingsForDate) {
            $groupedBySlot = collect($bookingsForDate)->groupBy('vendor_time_slot_id');
            $fullyBookedSlotsForDate = [];

            foreach ($groupedBySlot as $slotId => $slotBookings) {
                $slot = $timeSlots->firstWhere('id', $slotId);
                if (!$slot) continue;

                $start          = Carbon::parse($slot->start_time);
                $end            = Carbon::parse($slot->end_time);
                $duration       = $package->duration_minutes;
                $totalSegments  = floor($start->diffInMinutes($end) / $duration);

                $bookedSegments = 0;
                foreach ($slotBookings as $booking) {
                    $bStart = Carbon::parse($booking['time_start']);
                    $bEnd   = Carbon::parse($booking['time_end']);
                    $bookedSegments += floor($bStart->diffInMinutes($bEnd) / $duration);
                }

                if ($bookedSegments >= $totalSegments) {
                    $fullyBookedSlotsForDate[] = $slotId;
                }
            }

            if ($timeSlots->count() > 0 && count($fullyBookedSlotsForDate) === $timeSlots->count()) {
                // $fullyBookedDates[] = $date;
                $fullyBookedDates[] = [
                    'date_start' => $date
                ];
            }
        }

        $confirmedBookings = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $authUser->id)
            ->where(function ($q) use ($package) {
                $q->where('package_id', $package->id)
                    ->orWhereIn('package_id', function ($sub) use ($package) {
                        $sub->select('id')
                            ->from('packages')
                            ->where('category_id', $package->category_id);
                    });
            })
            ->whereNotNull('booked_date_start')
            ->whereNotIn('status', ['failed', 'pending'])
            ->select('package_id', 'package_category_id', 'booked_date_start') // ✅ Now includes category
            ->get();


        $slotLimits = VendorTimeSlotLimit::where('organizer_id', $authUser->id)
            ->where(function ($q) use ($package) {
                $q->whereNull('package_id')
                    ->orWhere('package_id', $package->id)
                    ->orWhere('package_category_id', $package->category_id);
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', now());
            })
            ->get();

        $limitReachedDays   = [];      
        $weekRangeBlock     = [];

        foreach ($slotLimits as $limit) {
            $grouped = $confirmedBookings
                ->filter(
                    fn($b) =>
                    ($limit->package_id && $b->package_id == $limit->package_id) ||
                    ($limit->package_category_id && $b->package_category_id == $limit->package_category_id)
                )
                ->groupBy(function ($b) use ($limit) {
                    $date = Carbon::parse($b->booked_date_start);
                    return $limit->duration_unit === 'week'
                        ? $date->startOfWeek()->toDateString()
                        : $date->format('Y-m');
                });

            foreach ($grouped as $start => $bookings) {
                if (count($bookings) >= $limit->booking_limit) {
                    $weekStart = Carbon::parse($start);
                    // $weekStart = Carbon::parse($start)->startOfWeek(Carbon::MONDAY);
                    $weekEnd = $weekStart->copy()->addDays(6); // optional if you want range
                    $weekRangeBlock[] = $weekStart->toDateString() . ' - ' . $weekEnd->toDateString();

                    $bookedDaysInThisWeek = collect($bookings)
                        ->pluck('booked_date_start')
                        ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
                        ->toArray();

                    for ($i = 0; $i < 7; $i++) {
                        $day = $weekStart->copy()->addDays($i)->toDateString();

                        if (!in_array($day, $bookedDaysInThisWeek)) {
                            $limitReachedDays[] = $day;
                        } 
                    }
                }
            }
        }

        $offDays = VendorOffDay::where('organizer_id', $authUser->id)
            ->whereDate('off_date', '>=', now())
            ->get();

        Log::info($timeSlots);
        return response()->json([
            'authUser'          => $authUser,
            'package'           => $package,
            'timeSlots'         => $timeSlots,
            'offDays'           => $offDays,
            'bookedDates'       => $bookedDates,
            'fullyBookedDates'  => $fullyBookedDates,
            'limitReachedDays'  => $limitReachedDays,
            'weekRangeBlock'    => $weekRangeBlock,
        ]);


    }

    public function showCreateBooking(Request $request)
    {
        $page_title = 'Create Booking';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $packages = Package::query()
            ->active()
            ->sortedAsc()
            ->select('id', 'name', 'organizer_id', 'package_slot_quantity')
            ->where('organizer_id', $authUser->id)
            ->with([
                'addons',
                'items',
                'discounts',
                'category',
                'images',
                'organizer',
            ])
            ->get();

        $limitReachedDays   = [];      
        $weekRangeBlock     = [];
        $fullyBookedDates       = [];
        $offDays       = [];
        $timeSlots       = [];
        $bookedDates       = [];

        return view('organizer.booking.create', [
            'page_title'        => $page_title,
            'authUser'          => $authUser,
            'packages'          => $packages,
            'timeSlots'         => $timeSlots,
            'offDays'           => $offDays,
            'bookedDates'       => $bookedDates,
            'fullyBookedDates'  => $fullyBookedDates,
            'limitReachedDays'  => $limitReachedDays,
            'weekRangeBlock'    => $weekRangeBlock,
        ]);


    }

    public function calender(Request $request)
    {
        $page_title = 'Calender';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $events = null;
        // Fetch events for the current organizer to populate dropdown
        $packages = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs from those events
        $bookingVendorTimeSlots = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        $bookingIds = DB::table('bookings')
            ->whereIn('id', $bookingVendorTimeSlots)
            ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $bookings = Booking::with(['vendorTimeSlots', 'participant', 'package:id,name'])
            ->whereIn('id', $bookingIds)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('package', function ($q) use ($event_search) {
                    $q->where('name', $event_search);
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

        return view('organizer.booking.calender', compact('page_title', 'authUser', 'bookings', 'packages', 'events'));
    }

    public function getBookingsJson(Request $request)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');
        $organizerId = $authUser->id;

        $mode = $request->get('mode', 'detail', 'week');

        // MONTH SUMMARY MODE
        if ($mode === 'month') {

            $rows = DB::table('bookings')
                ->where('bookings.organizer_id', $organizerId)
                ->where('bookings.status', '!=', 'cancelled')
                ->join('bookings_vendor_time_slot as slots', 'slots.booking_id', '=', 'bookings.id')
                ->leftJoin('packages', 'packages.id', '=', 'bookings.package_id')
                ->select(
                    DB::raw("DATE(slots.booked_date_start) as date"),
                    'packages.name as package',
                    'bookings.status',
                    DB::raw('COUNT(DISTINCT bookings.id) as total')
                )
                ->groupBy('date', 'package', 'bookings.status')
                ->get();

            return $rows->map(function ($row) {
                $color = match ($row->status) {
                    'paid' => '#16a34a',
                    'deposit' => '#f59e0b',
                    'cancelled' => '#dc2626',
                    default => '#2563eb',
                };

                return [
                    'title' => $row->package,
                    'start' => $row->date,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'count' => $row->total,
                    ]
                ];
            });
        }

        if ($mode === 'week') {

            $bookings = Booking::with([
                'vendorTimeSlots.timeSlot',
                'package'
            ])
            ->where('bookings.status', '!=', 'cancelled')
            ->get();

            $events = [];

            foreach ($bookings as $booking) {

                if ($booking->vendorTimeSlots->isEmpty()) continue;

                $start = null;
                $end   = null;
                $slotNames = [];

                foreach ($booking->vendorTimeSlots as $slot) {

                    $slotStart = $slot->booked_date_start . 'T' . $slot->booked_time_start;
                    $slotEnd   = $slot->booked_date_end   . 'T' . $slot->booked_time_end;

                    if (!$start || $slotStart < $start) {
                        $start = $slotStart;
                    }

                    if (!$end || $slotEnd > $end) {
                        $end = $slotEnd;
                    }

                    if ($slot->timeSlot?->slot_name) {
                        $slotNames[] = $slot->timeSlot->slot_name;
                    }
                }

                $color = match ($booking->status) {
                    'paid' => '#16a34a',
                    'deposit' => '#f59e0b',
                    'cancelled' => '#dc2626',
                    default => '#2563eb',
                };

                $events[] = [
                    'id' => 'w-'.$booking->id,
                    'code' => $booking->booking_code,
                    'title' => $booking->package?->name ?? 'Booking',
                    'start' => $start,
                    'end' => $end,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'slot' => implode(', ', array_unique($slotNames)), 
                    ],
                ];
            }


            return $events;
        }

        // --------------------------------------------------
        // WEEK / DAY (DETAILED MODE)
        // --------------------------------------------------

        $events = [];

        $bookings = Booking::with(['vendorTimeSlots.timeSlot:id,slot_name', 'participant', 'package', 'bookingAddons.addon'])
            ->where('organizer_id', $organizerId)
            ->where('bookings.status', '!=', 'cancelled')
            ->whereHas('vendorTimeSlots')
            ->get();

        foreach ($bookings as $booking) {

            $addons = [];

            foreach ($booking->bookingAddons as $bookingAddon) {
                if ($bookingAddon->addon) {
                    $addons[] = $bookingAddon->addon->name . ' x' . $bookingAddon->qty;
                }
            }

            if ($booking->vendorTimeSlots->isEmpty()) {
                continue;
            }

            $start = null;
            $end   = null;
            $slotNames = [];

            foreach ($booking->vendorTimeSlots as $slot) {

                $slotStart = $slot->booked_date_start . 'T' . $slot->booked_time_start;
                $slotEnd   = $slot->booked_date_end   . 'T' . $slot->booked_time_end;

                // find earliest start
                if (!$start || $slotStart < $start) {
                    $start = $slotStart;
                }

                // find latest end
                if (!$end || $slotEnd > $end) {
                    $end = $slotEnd;
                }

                if ($slot->timeSlot?->slot_name) {
                    $slotNames[] = $slot->timeSlot->slot_name;
                }
            }

            // calculate balance
            $balance = 0;
            if ($booking->is_deposit) {
                $balance = $booking->final_price - $booking->paid_amount;
            }

            $color = match ($booking->status) {
                'paid' => '#16a34a',
                'deposit' => '#f59e0b',
                'cancelled' => '#dc2626',
                default => '#2563eb',
            };

            $events[] = [
                'id'    => $booking->id, // IMPORTANT: booking id, not slot id
                'title' => $booking->package?->name ?? 'Booking',
                'start' => $start,
                'end'   => $end,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'extendedProps' => [
                    'customer'      => $booking->participant?->name ?? 'Unknown',
                    'phone'         => $booking->participant?->phone ?? '',
                    'status'        => $booking->status,
                    'code'          => $booking->booking_code,
                    'slot'          => implode(', ', $slotNames), // merged slots
                    'is_deposit'    => $booking->is_deposit,
                    'deposit'       => $booking->paid_amount,
                    'balance'       => $balance,
                    'booking_id'    => $booking->id,
                    'addons'        => implode(', ', $addons),
                ],
            ];
        }


        return $events;
    }

    public function holidays()
    {
        $year = request('year', now()->year);

        // Fetch from free Nager.Date API
        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/MY");

        if ($response->failed()) {
            return [];
        }

        $holidays = $response->json();

        // return for FullCalendar
        return collect($holidays)->map(function ($h) {
            return [
                'title' => $h['localName'], // e.g., "Hari Raya Aidilfitri"
                'start' => $h['date'],      // full day
                'allDay' => true,
                'display' => 'background',   // background event
                'classNames' => ['fc-holiday'] // custom CSS class
            ];
        });
    }

    public function makeFullPayment($id)
    {
        $booking = Booking::with(['participant', 'vendorTimeSlots'])->findOrFail($id);


        if ($booking->payment_type === 'deposit' && $booking->status == 'paid') {
            $booking->paid_amount = $booking->final_price; 
            $booking->payment_type = 'full_payment';
            $booking->save();

            $booking->vendorTimeSlots()->update(['status' => 'full_payment']);

            $booking->load('vendorTimeSlots');

            // Send email to booking's email
            // Mail::to($booking->participant->email)->send(new PaymentConfirmed($booking));

            // EmailLog::create([
            //     'to_email' => $booking->participant->email,
            //     'type' => 'payment_confirmed',
            //     'sent_at' => now(),
            // ]);

            $receiptUrl = route('booking.receipt.package', $booking->booking_code);

            $text = "Hai " . ucfirst($booking->participant->name) . ",\n\n";

            $text .= "🎉 Tahniah! Baki bayaran anda telah berjaya dijelaskan sepenuhnya.\n";
            $text .= "Tempahan anda kini telah disahkan sebagai *FULL PAYMENT*.\n\n";

            $text .= "*Status Pembayaran*: ✅ Pembayaran Penuh Telah Diterima\n";
            $text .= "Jumlah Keseluruhan Dibayar: RM" . number_format(
                $booking->final_price,
                2
            ) . "\n\n";

            $text .= "📄 Resit Pembayaran Penuh boleh dimuat turun di sini:\n";
            $text .= $receiptUrl . "\n\n";

            $text .= "Terima kasih kerana menggunakan perkhidmatan SistemKami 🙏\n\n";
            $text .= "📌 Peringatan 📌\n";
            $text .= "⏱️ Sila datang 15 minit lebih awal sebelum slot anda.\n";
            $text .= "👟 Digalakkan pakai kasut yang sesuai (lelaki & perempuan) untuk gambar lebih cantik.\n";
            $text .= "👜 Wanita, bawa handbag untuk nampak lebih bergaya.";

            $whatsappUrl = 'https://api.whatsapp.com/send?phone=+6' . $booking->participant->phone
            . '&text=' . urlencode($text);

            // return redirect()->back()->with('success', 'Payment verified and booking has been updated to Full Payment.');
            return redirect()->back()->with([
                'success' => 'Payment verified and booking for ' . $booking->participant->name . ' has been updated to Full Payment.',
                'whatsapp_url' => $whatsappUrl,
            ]);

        }

        return redirect()->back()->with('error', 'Cannot verify this booking.');
    }


}