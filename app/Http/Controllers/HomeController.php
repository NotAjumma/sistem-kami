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
        // Full page cache for 5 minutes — avoids DB + Blade rendering on repeat visits
        $html = Cache::remember('home_page_html', 300, function () {
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

            // Only categories that have at least one active package
            $packageCategories = Cache::remember('home.package_categories', now()->addMinutes(10), function () {
                return PackageCategory::whereHas('packages', function ($q) {
                    $q->where('status', 'active');
                })->orderBy('name')->get();
            });

            $seo = ['canonical' => url('/')];

            return view('home.index', compact('packages', 'packageCategories', 'organizers', 'seo'))->render();
        });

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Cache-Control', 'public, max-age=300, s-maxage=600');
    }

    public function about()
    {
        $seo = [
            'title' => 'About Us | Sistem Kami',
            'description' => 'Learn about Sistem Kami — a booking and business management platform designed to help service providers and organizers manage packages, schedules, and customers.',
            'canonical' => url('/about'),
        ];

        return view('home.about', compact('seo'));
    }

    public function faq()
    {
        $seo = [
            'title' => 'FAQ | Sistem Kami',
            'description' => 'Frequently asked questions about Sistem Kami — how it works, pricing, booking management, and more.',
            'canonical' => url('/faq'),
        ];

        return view('home.faq', compact('seo'));
    }

    public function privacyPolicy()
    {
        $seo = [
            'title' => 'Privacy Policy | Sistem Kami',
            'description' => 'Read the Sistem Kami privacy policy — how we collect, use, and protect your personal data.',
            'canonical' => url('/privacy-policy'),
        ];

        return view('home.privacy-policy', compact('seo'));
    }

    public function terms()
    {
        $seo = [
            'title' => 'Terms & Conditions | Sistem Kami',
            'description' => 'Read the Sistem Kami terms and conditions governing the use of our platform.',
            'canonical' => url('/terms-and-conditions'),
        ];

        return view('home.terms', compact('seo'));
    }

    public function search(Request $request)
    {
        $page_title = "Sistem Kami | Search";

        $query = Package::with([
            'organizer',
            'category',
            'images' => function ($q) {
                $q->where('is_cover', true)->orderBy('sort_order');
            },
            'vendorTimeSlots.images' => function ($q) {
                $q->orderByDesc('is_cover')->orderBy('sort_order');
            },
        ])->where('status', 'active');

        // Filter by keyword (package name/description or organizer name)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%")
                    ->orWhereHas('organizer', function ($oq) use ($keyword) {
                        $oq->where('name', 'like', "%$keyword%");
                    });
            });
        }

        // Filter by location (organizer city, state, or country)
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('organizer', function ($q) use ($location) {
                $q->where('city', 'like', "%$location%")
                    ->orWhere('state', 'like', "%$location%")
                    ->orWhere('country', 'like', "%$location%");
            });
        }

        // Filter by package category
        if ($request->filled('category')) {
            $category = PackageCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $packages = $query->orderBy('order_by')->get();

        // Group by organizer (same as homepage)
        $organizers = $packages
            ->groupBy('organizer_id')
            ->map(function ($group) {
                $organizer = $group->first()->organizer;
                $organizer->first_package = $group->first();
                $organizer->search_packages = $group;
                return $organizer;
            })
            ->values();

        $packageCategories = PackageCategory::whereHas('packages', function ($q) {
            $q->where('status', 'active');
        })->orderBy('name')->get();

        return view('home.search', compact('packages', 'organizers', 'packageCategories', 'page_title'));
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