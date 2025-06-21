<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\VendorOffDay;
use App\Models\VendorTimeSlot;
use App\Models\BookingVendorTimeSlot;
use App\Models\VendorTimeSlotLimit;
use App\Models\Organizer;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class BusinessController extends Controller
{

    public function showProfile($slug)
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

        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $organizer->id)
            ->where('package_id', $package->id)
            ->whereIn('bookings_vendor_time_slot.status', ['deposit_paid', 'full_payment'])
            ->whereDate('booked_date_start', '>=', now())
            ->pluck('booked_date_start')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        $confirmedBookings = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $organizer->id)
            ->where(function ($q) use ($package) {
                $q->where('package_id', $package->id)
                    ->orWhereIn('package_id', function ($sub) use ($package) {
                        $sub->select('id')
                            ->from('packages')
                            ->where('category_id', $package->category_id);
                    });
            })
            ->whereNotNull('booked_date_start')
            ->select('package_id', 'package_category_id', 'booked_date_start') // ✅ Now includes category
            ->get();


        $slotLimits = VendorTimeSlotLimit::where('organizer_id', $organizer->id)
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

        $limitReachedDays = [];        // To disable dates in frontend
        $bookedDatesFormatted = [];
        $weekRangeBlock = [];

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
                        } else {
                            $bookedDatesFormatted[] = $day;
                        }
                    }
                }
            }
        }

        $timeSlots = VendorTimeSlot::where('organizer_id', $organizer->id)
            ->where(function ($q) use ($package) {
                $q->whereNull('package_id')
                    ->orWhere('package_id', $package->id);
            })
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        $offDays = VendorOffDay::where('organizer_id', $organizer->id)
            ->whereDate('off_date', '>=', now())
            ->get();

        $page_title = $package->name;

        return view('home.business.package.index', [
            'organizer' => $organizer,
            'package' => $package,
            'page_title' => $page_title,
            'timeSlots' => $timeSlots,
            'offDays' => $offDays,
            'bookedDates' => $bookedDates,
            'limitReachedDays' => $limitReachedDays,
            'bookedDatesFormatted' => $bookedDatesFormatted,
            'weekRangeBlock' => $weekRangeBlock,
        ]);
    }

    public function showBooking($organizerSlug, $packageSlug)
    {
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

        $organizer = Organizer::where('slug', $organizerSlug)
            ->with([
                'activePackages.images',
                'activePackages.discounts',
                'gallery',
                'timeSlots' => function ($query) {
                    $query->where('is_active', 1)->orderBy('start_time');
                },
                'offDays' => function ($query) {
                    $query->whereDate('off_date', '>=', now());
                }
            ])
            ->firstOrFail();

        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $organizer->id)
            ->where('package_id', $package->id)
            ->whereIn('bookings_vendor_time_slot.status', ['deposit_paid', 'full_payment'])
            ->whereDate('booked_date_start', '>=', now())
            ->pluck('booked_date_start')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        $confirmedBookings = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $organizer->id)
            ->where(function ($q) use ($package) {
                $q->where('package_id', $package->id)
                    ->orWhereIn('package_id', function ($sub) use ($package) {
                        $sub->select('id')
                            ->from('packages')
                            ->where('category_id', $package->category_id);
                    });
            })
            ->whereNotNull('booked_date_start')
            ->select('package_id', 'package_category_id', 'booked_date_start') // ✅ Now includes category
            ->get();


        $slotLimits = VendorTimeSlotLimit::where('organizer_id', $organizer->id)
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

        $limitReachedDays = [];        // To disable dates in frontend
        $bookedDatesFormatted = [];
        $weekRangeBlock = [];

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
                        } else {
                            $bookedDatesFormatted[] = $day;
                        }
                    }
                }
            }
        }

        $timeSlots = VendorTimeSlot::where('organizer_id', $organizer->id)
            ->where(function ($q) use ($package) {
                $q->whereNull('package_id')
                    ->orWhere('package_id', $package->id);
            })
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        $offDays = VendorOffDay::where('organizer_id', $organizer->id)
            ->whereDate('off_date', '>=', now())
            ->get();

        $page_title = $package->name;

        return view('home.business.package.booking', [
            'organizer' => $organizer,
            'package' => $package,
            'page_title' => $page_title,
            'timeSlots' => $timeSlots,
            'offDays' => $offDays,
            'bookedDates' => $bookedDates,
            'limitReachedDays' => $limitReachedDays,
            'bookedDatesFormatted' => $bookedDatesFormatted,
            'weekRangeBlock' => $weekRangeBlock,
        ]);
    }

}