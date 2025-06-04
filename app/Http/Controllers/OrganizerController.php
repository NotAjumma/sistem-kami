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

    public function bookings()
    {
        $page_title = 'Bookings List';
        $authUser = auth()->guard('organizer')->user()->load('user');

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

        // Step 4: Fetch bookings with eager loading
        $bookings = Booking::with([
            'bookingTickets',
            'participant',
            'event:id,title'
        ])
            ->whereIn('id', $bookingIds)
            ->latest()
            ->get();


        \Log::info($bookings);
        return view('organizer.booking.index', compact('page_title', 'authUser', 'bookings'));
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


}