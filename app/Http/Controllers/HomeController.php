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
        // Full page cache per locale for 5 minutes — avoids DB + Blade rendering on repeat visits
        $locale = app()->getLocale();
        $html = Cache::remember("home_page_html_{$locale}", 300, function () {
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

            $appName = config('app.name', 'Sistem Kami');
            $seo = [
                'title'       => "$appName | Online Booking System Malaysia — Event & Vendor Booking",
                'description' => "$appName is an online booking system in Malaysia for event organizers and vendors. Manage packages, schedules, and customer bookings — all in one platform.",
                'canonical'   => url('/'),
            ];

            return view('home.index', compact('packages', 'packageCategories', 'organizers', 'seo'))->render();
        });

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Cache-Control', 'public, max-age=300, s-maxage=600');
    }

    public function about()
    {
        $seo = [
            'title'       => __('seo.about.title'),
            'description' => __('seo.about.description'),
            'canonical'   => url('/about'),
        ];

        return view('home.about', compact('seo'));
    }

    public function faq()
    {
        $seo = [
            'title'       => __('seo.faq.title'),
            'description' => __('seo.faq.description'),
            'canonical'   => url('/faq'),
        ];

        return view('home.faq', compact('seo'));
    }

    public function privacyPolicy()
    {
        $seo = [
            'title'       => __('seo.privacy.title'),
            'description' => __('seo.privacy.description'),
            'canonical'   => url('/privacy-policy'),
        ];

        return view('home.privacy-policy', compact('seo'));
    }

    public function terms()
    {
        $seo = [
            'title'       => __('seo.terms.title'),
            'description' => __('seo.terms.description'),
            'canonical'   => url('/terms-and-conditions'),
        ];

        return view('home.terms', compact('seo'));
    }

    /**
     * Generic special organizer branded page.
     * URL slug  (hyphenated, e.g. "lady-d-touch")  →  special_page DB value (underscored, e.g. "lady_d_touch").
     * Renders home.{special_page}_{sub} if the view exists; falls back to the organizer's public profile.
     */
    public function specialPage(string $page, string $sub = 'home')
    {
        $specialPage = str_replace('-', '_', $page);

        $organizer = \App\Models\Organizer::where('special_page', $specialPage)
            ->where('is_active', true)
            ->first();

        if (! $organizer) {
            abort(404);
        }

        $view = 'home.special_page.' . $specialPage . '.' . $sub;

        if (! view()->exists($view)) {
            // Fallback: redirect to the organizer's default public profile
            return redirect()->route('business.profile', ['slug' => $organizer->slug]);
        }

        return view($view, ['organizer' => $organizer, 'specialPage' => $page]);
    }

    public function specialPageWedding(string $slug)
    {
        return $this->specialPage($slug, 'wedding');
    }

    public function wedding()
    {
        $seo = [
            'title'       => __('seo.wedding.title'),
            'description' => __('seo.wedding.description'),
            'canonical'   => url('/wedding'),
        ];

        return view('home.wedding', compact('seo'));
    }

    public function search(Request $request)
    {
        $page_title = __('seo.search.title');

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