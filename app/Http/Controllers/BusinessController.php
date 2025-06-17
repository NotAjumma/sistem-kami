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

        return view('home.business.profile', compact('organizer'));
    }
}