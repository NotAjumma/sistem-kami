<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\EmailLog;
use App\Models\FishingRank;
use App\Models\FishingLeaderboard;
use App\Models\FishingLeaderboardResult;
use App\Models\FishingCatch;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class WorkerController extends Controller
{
    public function ticketsConfirmed(Request $request)
    {
        $page_title = 'Tickets List Confirmed';
        $authUser = auth()->guard('worker')->user()->load('user');

        // Fetch events for the current organizer to populate dropdown
        $events = DB::table('events')
            ->where('organizer_id', $authUser->organizer_id)
            ->whereIn('id', $authUser->event_ids)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $eventIds = DB::table('events')
            ->whereIn('id', $authUser->event_ids)
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

    public function fishingKeyInWeight(Request $request)
    {
        $page_title = 'Key In Weight';
        $authUser = auth()->guard('worker')->user()->load('user');

        // Fetch events for dropdown
        $events = DB::table('events')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->where('categories.slug', 'fishing')
            ->where('organizer_id', $authUser->organizer_id)
            ->whereIn('events.id', $authUser->event_ids)
            ->select('events.id', 'title')
            ->orderBy('title')
            ->get();

        // If POST submission
        if ($request->isMethod('post')) {
            $request->validate([
                'participant_id' => 'required|exists:participants,id',
                'weight' => 'required|numeric|min:0.1|max:999.99',
                'event_id' => 'required|exists:events,id',
            ]);

            $leaderboard = FishingLeaderboard::where('event_id', $request->event_id)->with('rank')->first();

            if (!$leaderboard) {
                return back()->withErrors(['event_id' => 'No leaderboard found for selected event.']);
            }

            // Save catch
            FishingCatch::create([
                'participant_id' => $request->participant_id,
                'fishing_leaderboard_id' => $leaderboard->id,
                'weight' => $request->weight,
                'caught_at' => now(),
            ]);

            // Determine whether to use total or single catch
            $aggregateFunction = $leaderboard->rank->calculation_mode === 'single' ? 'MAX' : 'SUM';

            $catches = FishingCatch::where('fishing_leaderboard_id', $leaderboard->id)
                ->select('id', 'participant_id', 'weight as total_weight', 'caught_at as first_catch', 'caught_at as last_catch')
                ->get()
                ->map(function ($row) use ($leaderboard) {
                    $diff = null;

                    if ($leaderboard->rank->type === 'closest_to_target') {
                        $diff = abs($row->total_weight - $leaderboard->rank->target_weight);
                    }

                    return (object) [
                        'participant_id' => $row->participant_id,
                        'total_weight' => $row->total_weight,
                        'difference' => $diff,
                        'first_catch' => $row->first_catch,
                        'last_catch' => $row->last_catch,
                    ];
                });

            // Sort participants based on ranking rules
            $sorted = $catches->sort(function ($a, $b) use ($leaderboard) {
                $rankType = $leaderboard->rank->type;
                $timeMode = $leaderboard->rank->time_mode ?? 'none';

                if ($rankType === 'heaviest') {
                    $cmp = $b->total_weight <=> $a->total_weight;
                } elseif ($rankType === 'closest_to_target') {
                    $cmp = $a->difference <=> $b->difference;
                } else {
                    $cmp = 0;
                }

                // Apply time tiebreaker if needed
                if ($cmp === 0 && $timeMode !== 'none') {
                    if ($timeMode === 'fastest') {
                        return strtotime($a->first_catch) <=> strtotime($b->first_catch);
                    } elseif ($timeMode === 'slowest') {
                        return strtotime($b->last_catch) <=> strtotime($a->last_catch);
                    }
                }

                return $cmp;
            })->values();

            // Refresh leaderboard results
            FishingLeaderboardResult::where('fishing_leaderboard_id', $leaderboard->id)->delete();

            foreach ($sorted as $i => $result) {
                FishingLeaderboardResult::create([
                    'fishing_leaderboard_id' => $leaderboard->id,
                    'participant_id' => $result->participant_id,
                    'total_weight' => $result->total_weight,
                    'difference' => $result->difference,
                    'rank' => $i + 1,
                    'caught_at' => $result->first_catch,
                ]);
            }

            return redirect()->back()->with('success', 'Catch successfully recorded and leaderboard updated!');
        }

        // Fetch participants (optional: filter by event or organizer)
        $participants = Participant::orderBy('name')->get();

        return view('organizer.fishing.key_in_weight', compact(
            'page_title',
            'authUser',
            'events',
            'participants'
        ));
    }

    public function showFishingLeaderboard(Request $request)
    {
        $page_title = 'Leadboard';
        $authUser = auth()->guard('worker')->user()->load('user');

        $allLeaderboards = FishingLeaderboard::with('rank')->orderByDesc('starts_at')->get();
        $leaderboard = null;
        $results = collect();

        if ($request->leaderboard_id) {
            $leaderboard = FishingLeaderboard::with('rank')->find($request->leaderboard_id);

            if ($leaderboard) {
                $results = FishingLeaderboardResult::where('fishing_leaderboard_id', $leaderboard->id)
                    ->with('participant')
                    ->orderBy('rank')
                    ->get();
            }
        }

        return view('organizer.fishing.leaderboard', compact('leaderboard', 'results', 'allLeaderboards', 'authUser', 'page_title'));
    }

}