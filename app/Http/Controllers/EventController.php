<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\FishingLeaderboard;
use App\Models\FishingLeaderboardResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{

    public function showBySlug($slug)
    {
        $event = Event::with(['category', 'organizer', 'tickets'])->where('slug', $slug)->firstOrFail();

        if (!$event->organizer || $event->organizer->type !== 'event') {
            abort(403, 'Unauthorized access to non-event type');
        }

        $page_title = $event->title;

        // Filter Ticket
        $today = now();

        $event->load([
            'tickets.children' => function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->whereNull('release_date')
                        ->orWhere('release_date', '<=', $today);
                });
            }
        ]);

        $filteredTickets = $event->tickets->filter(function ($ticket) use ($today) {
            return is_null($ticket->release_date) || $ticket->release_date <= $today;
        })->values();

        // End filter Ticket

        $now = Carbon::now();
        if ($event->status) {
            if ($now->lt($event->registration_deadline)) {
                $event->status_label = 'Coming Soon';
                $event->status = 0;
            } elseif ($now->lt($event->end_date)) {
                $event->status_label = 'Book Now';
                $event->status = 1;
            } else {
                $event->status_label = 'Event has ended';
                $event->status = 0;
            }
        } else {
            $event->status_label = 'Coming Soon';
            $event->status = 0;
        }

        // Format start_date
        $startDate = Carbon::parse($event->start_date);
        $startDay = str_pad($startDate->format('j'), 2, '0', STR_PAD_LEFT);
        $startSuffix = $startDate->format('S');
        $event->formatted_start_date = $startDate->format('D') . ', ' . $startDay . $startSuffix . ' ' . $startDate->format('M Y');

        // Format end_date
        $endDate = Carbon::parse($event->end_date);
        $endDay = str_pad($endDate->format('j'), 2, '0', STR_PAD_LEFT);
        $endSuffix = $endDate->format('S');
        $event->formatted_end_date = $endDate->format('D') . ', ' . $endDay . $endSuffix . ' ' . $endDate->format('M Y');

        // Convert start_time and end_time from 24h to 12h format
        $event->formatted_start_time = Carbon::createFromFormat('H:i:s', $event->start_time)->format('g:i A');
        $event->formatted_end_time = Carbon::createFromFormat('H:i:s', $event->end_time)->format('g:i A');

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        // Month (3 letter)
        $event->date_month = $startDate->format('M'); // e.g. Oct

        // Day (2 digit) for start and end
        $startDay = $startDate->format('d'); // e.g. 05
        $endDay = $endDate->format('d');     // e.g. 06

        // If single day event, just show one day
        $event->date_days = ($startDay === $endDay) ? $startDay : ($startDay . ' - ' . $endDay);

        return view('home.event.index', compact('event', 'filteredTickets', 'page_title'));
    }

    public function showFishingLeaderboard($slug)
    {
        $event = Event::with(['category', 'organizer', 'tickets'])->where('slug', $slug)->firstOrFail();

        if (!$event->organizer || $event->organizer->type !== 'event' 
        // || !$event->starts_at || Carbon::parse($event->starts_at)->toDateString() !== now()->toDateString()
        ) 
        {
            abort(403, 'Unauthorized access to non-event type');
        }

        $page_title = $event->title . ' Leaderboard';
        $authUser = auth()->guard('worker')->user()->load('user');

        $allLeaderboards = FishingLeaderboard::with('rank')->orderByDesc('starts_at')->get();

        // Fetch results for all leaderboards
        $leaderboardResults = [];
        foreach ($allLeaderboards as $leaderboard) {
            $results = FishingLeaderboardResult::where('fishing_leaderboard_id', $leaderboard->id)
                ->with('participant')
                ->orderBy('rank')
                ->get();

            $leaderboardResults[] = [
                'leaderboard' => $leaderboard,
                'results' => $results
            ];
        }

        return view('home.event.fishing.leaderboard', compact(
            'leaderboardResults',
            'allLeaderboards',
            'authUser',
            'page_title',
            'event'
        ));
    }


}