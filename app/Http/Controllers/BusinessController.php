<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\Organizer;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;

class BusinessController extends Controller
{

    public function showPublic($slug)
    {
        $organizer = Organizer::with([
            'activePackages.addons',
            'activePackages.items',
            'activePackages.discounts',
            'activePackages.category',
            'activePackages.images',
            'activePackages.images',
            'gallery',
        ])->where('slug', $slug)->firstOrFail();

        \Log::info($organizer);

        if ($organizer->type !== 'business') {
            abort(403, 'Unauthorized access to non-business type');
        }

        $page_title = $organizer->name;

        return view('home.business.profile', compact('organizer', 'page_title'));
    }

    public function showPackage($organizerSlug, $packageSlug)
    {
        // Retrieve organizer by slug
        $organizer = Organizer::where('slug', $organizerSlug)
            ->with([
                'activePackages.images',
                'activePackages.discounts',
                'gallery',
            ])
            ->firstOrFail();

        // Find the package under this organizer
        $package = Package::whereHas('organizer', function ($query) use ($organizerSlug) {
            $query->where('slug', $organizerSlug);
        })
            ->where('slug', $packageSlug)
            ->with([
                'addons',
                'items',
                'discounts',
                'category',
                'images',
                'organizer',
            ])
            ->firstOrFail();

            \Log::info($package);

        if (!$package) {
            abort(404, 'Package not found for this business.');
        }

        $page_title = $package->name;

        return view('home.business.package.index', [
            'organizer' => $organizer,
            'package' => $package,
            'page_title' => $page_title,
        ]);
    }
}