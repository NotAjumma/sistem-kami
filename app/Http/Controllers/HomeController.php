<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Category;
use App\Models\PackageCategory;
use App\Models\Package;
use App\Models\Event;
use App\Helper\VisitorLogger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function index()
    {
        $packages = Cache::remember('home.packages', now()->addMinutes(10), function () {
            return Package::with([
                'organizer',
                'category',

                // Package cover image sahaja
                'images' => function ($query) {
                    $query->where('is_cover', true)
                        ->orderBy('sort_order');
                },

                // Vendor slots + slot images
                'vendorTimeSlots.images' => function ($query) {
                    $query->orderByDesc('is_cover')
                        ->orderBy('sort_order');
                },
            ])
            ->where('status', 'active')
            ->orderBy('order_by')
            ->get();
        });

        // derive organizers from packages (unique)
        $organizers = $packages
            ->groupBy('organizer_id')
            ->map(function ($group) {
                $organizer = $group->first()->organizer;
                $organizer->first_package = $group->first();
                return $organizer;
            })
            ->values();

        $eventCategories = Cache::remember('home.event_categories', now()->addHours(1), function () {
            return Category::whereNull('parent_id')
                ->orderBy('order_by')
                ->get();
        });

        $seo = ['canonical' => url('/')];

        $response = response()->view('home.index', compact('packages', 'eventCategories', 'organizers', 'seo'));
        $response->headers->set('Cache-Control', 'public, max-age=300, s-maxage=600');
        return $response;
    }

    public function search(Request $request)
    {
        $page_title = "Sistem Kami | Search";
        $query = Event::with(['organizer', 'tickets'])
            ->where('status', '1'); // Only active events
        // \Log::info('request');
        // \Log::info($request->all());

        // Filter by keyword (title or description)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            });
        }

        // Filter by location (district, state, or country)
        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function ($q) use ($location) {
                $q->where('district', 'like', "%$location%")
                    ->orWhere('state', 'like', "%$location%")
                    ->orWhere('country', 'like', "%$location%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Future: Add filters for package type, price range...

        $events = $query->get();

        $eventCategories = Category::whereNull('parent_id')
            ->orderBy('order_by')
            ->get();

        return view('home.search', compact('events', 'eventCategories', 'page_title'));
    }

    public function log(Request $request)
    {
        VisitorLogger::log(
            $request->action,
            $request->page,
            $request->reference_id,
            $request->meta ?? [],
            $request->uri
        );

        return response()->json(['status' => 'ok']);
    }
}