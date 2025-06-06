<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\EmailLog;
use App\Models\Ticket;
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

        // \Log::info($authUser);
        return view('organizer.index', compact('page_title', 'authUser'));
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