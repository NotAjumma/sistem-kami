<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\PackageCategory;
use App\Models\VendorOffDay;
use App\Models\VendorTimeSlot;
use App\Models\BookingVendorTimeSlot;
use App\Models\VendorTimeSlotLimit;
use App\Models\Organizer;
use App\Models\Package;
use App\Helper\VisitorLogger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class BusinessController extends Controller
{

    private function page_seo($organizer, $package = null)
    {
        $appName = config('app.name');
        $isPackage = !is_null($package);

        $organizerName      = $organizer->name ?? '';
        $organizerCity      = $organizer->city ?? '';
        $organizerState     = $organizer->state ?? '';
        $organizerCategory  = $organizer->category ?? '';
        $organizerType      = $organizer->type ?? '';

        $imageSource        = $package->image_url ?? $organizer->banner_path ?? $organizer->logo_path;

        if (is_array($imageSource)) {
            $imageSource = $imageSource[0] ?? null;
        }

        $seoImage = $imageSource
            ? asset('images/organizers/' . $organizer->id . '/' . $imageSource)
            : asset('images/og-default.jpg');

        if ($isPackage) {
            return [
                'title'         => "{$package->name} by {$organizerName} | {$appName}",
                'description'   => strip_tags($package->description ?? $organizer->description),
                'keywords'      => implode(', ', array_filter([
                    $package->name,
                    "{$package->name} package",
                    "{$organizerName} {$package->name}",
                    "{$organizerName} package",
                    strtolower($organizerCategory),
                    "{$organizerType} in {$organizerCity}",
                    "affordable {$organizerCategory} Malaysia",
                    "best {$organizerCategory} in {$organizerCity}",
                    "{$appName} vendor",
                ])),
                'image'         => $package->images->first()
                    ? asset('images/organizers/' . $organizer->id . '/packages/' . $package->id . '/' . $package->images->first()->url)
                    : asset('images/logo-blue-full.png'),

            ];
        }

        return [
            'title'             => "{$organizerName} | {$organizerCategory} in {$organizerCity}, {$organizerState}  | {$appName}",
            'description'       => strip_tags($organizer->description),
            'keywords'          => implode(', ', array_filter([
                $organizerName,
                "{$organizerName} profile",
                "{$organizerName} services",
                "{$organizerCategory} in {$organizerCity}",
                "{$organizerCategory} in {$organizerState}",
                "{$organizerType} Malaysia",
                "best {$organizerCategory} vendor",
                "wedding {$organizerType} Malaysia",
                "{$appName} vendor",
            ])),
            'image'             => $seoImage,
        ];
    }

    public function showProfile(Request $request, $slug)
    {
        
        $organizer = Organizer::with([
            'activePackages.discounts',
            'activePackages.category',
            'activePackages.images',
            'activePackages.images',
            'gallery',
        ])->where('slug', $slug)->firstOrFail();

        if ($organizer->type !== 'service' || $organizer->visibility !== 'public' || !$organizer->is_active) {
            abort(403, 'Unauthorized access to non-business type');
        }

        $seo = $this->page_seo($organizer);
        
        $allActivePackages = $organizer->activePackages()->get();

        $packageCategories = \App\Models\PackageCategory::whereIn(
            'id',
            $allActivePackages->pluck('category_id')->unique()
        )->get();
        
        $packagesQuery = $organizer->activePackages()->with([
            'addons',
            'items',
            'discounts',
            'category',
            'images'
        ]);

        // Filter by keyword (searching name or description)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $packagesQuery->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            });
        }

        // Filter by package category
        if ($request->filled('package_category')) {
            $category = PackageCategory::where('slug', $request->package_category)->first();
            if ($category) {
                $packagesQuery->where('category_id', $category->id);
            }
        }

        $packages = $packagesQuery->get();
        $page_title = $organizer->name;

        return view('home.business.profile', compact(
            'organizer',
            'packages',
            'packageCategories',
            'page_title',
            'seo'
        ));
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

        if (!$package) {
            abort(404, 'Package not found for this business.');
        }
        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $organizer->id)
            ->where('package_id', $package->id)
            ->whereIn('status', ['deposit_paid', 'full_payment'])
            ->whereDate('booked_date_start', '>=', now())
            ->select('vendor_time_slot_id', 'booked_date_start', 'booked_date_end', 'booked_time_start', 'booked_time_end')
            ->get()
            ->map(function ($row) {
                return [
                    'date_start' => Carbon::parse($row->booked_date_start)->format('Y-m-d'),
                    'date_end'   => Carbon::parse($row->booked_date_end)->format('Y-m-d'),
                    'time_start' => $row->booked_time_start,
                    'time_end'   => $row->booked_time_end,
                    'vendor_time_slot_id' => $row->vendor_time_slot_id,
                ];
            });

        $timeSlots = VendorTimeSlot::where('organizer_id', $organizer->id)
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        $totalSlotCount = 0;

        foreach ($timeSlots as $slot) {
            if ($slot->is_full_day) {
                $totalSlotCount += 1;
                continue;
            }

            $start  = Carbon::parse($slot->start_time);
            $end    = Carbon::parse($slot->end_time);

            if (!$slot->slot_duration_minutes || $end->lessThanOrEqualTo($start)) {
                continue;
            }

            // Compute how many intervals fit between start and end
            $diffInMinutes      = $end->diffInMinutes($start);
            $slotsInThisPeriod  = intdiv($diffInMinutes, $package->duration_minutes);

            $totalSlotCount += $slotsInThisPeriod;
        }

        $fullyBookedDates       = [];
        $groupedByDate          = collect($bookedDates)->groupBy('date_start');

        foreach ($groupedByDate as $date => $bookingsForDate) {
            $groupedBySlot = collect($bookingsForDate)->groupBy('vendor_time_slot_id');
            $fullyBookedSlotsForDate = [];

            foreach ($groupedBySlot as $slotId => $slotBookings) {
                $slot = $timeSlots->firstWhere('id', $slotId);
                if (!$slot) continue;

                $start          = Carbon::parse($slot->start_time);
                $end            = Carbon::parse($slot->end_time);
                $duration       = $package->duration_minutes;
                $totalSegments  = floor($start->diffInMinutes($end) / $duration);

                $bookedSegments = 0;
                foreach ($slotBookings as $booking) {
                    $bStart = Carbon::parse($booking['time_start']);
                    $bEnd   = Carbon::parse($booking['time_end']);
                    $bookedSegments += floor($bStart->diffInMinutes($bEnd) / $duration);
                }

                if ($bookedSegments >= $totalSegments) {
                    $fullyBookedSlotsForDate[] = $slotId;
                }
            }

            if ($timeSlots->count() > 0 && count($fullyBookedSlotsForDate) === $timeSlots->count()) {
                // $fullyBookedDates[] = $date;
                $fullyBookedDates[] = [
                    'date_start' => $date
                ];
            }
        }

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
            ->whereNotIn('status', ['failed', 'pending'])
            ->select('package_id', 'package_category_id', 'booked_date_start') // ✅ Now includes category
            ->get();

            \Log::info("confirmedBookings");
            \Log::info($confirmedBookings);

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

        $limitReachedDays   = [];      
        $weekRangeBlock     = [];

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
                        } 
                    }
                }
            }
        }

        $offDays = VendorOffDay::where('organizer_id', $organizer->id)
            ->whereDate('off_date', '>=', now())
            ->get();

        $page_title = $package->name;
        $seo = $this->page_seo($organizer, $package);

        VisitorLogger::log('view_package', 'package', $package->id);

        return view('home.business.package.index', [
            'organizer'         => $organizer,
            'package'           => $package,
            'page_title'        => $page_title,
            'timeSlots'         => $timeSlots,
            'offDays'           => $offDays,
            'bookedDates'       => $bookedDates,
            'fullyBookedDates'  => $fullyBookedDates,
            'limitReachedDays'  => $limitReachedDays,
            'weekRangeBlock'    => $weekRangeBlock,
            'seo'               => $seo,
        ]);
    }

    public function getAvailableSlots(Request $request, $packageId)
    {
        $date       = $request->get('date');
        $package    = Package::with('vendorTimeSlots', 'organizer')->findOrFail($packageId);
        $slots      = [];

        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $package->organizer->id)
            ->whereIn('status', ['deposit_paid', 'full_payment'])
            ->whereDate('booked_date_start', '=', $date) // ✅ match exact date
            ->select('vendor_time_slot_id', 'booked_time_start', 'booked_time_end')
            ->get();

        $groupedBookings = $bookedDates->groupBy('vendor_time_slot_id')->map(function ($bookings) use ($package) {
            $blockedTimes = [];
            foreach ($bookings as $b) {
                $bStart = Carbon::parse($b->booked_time_start);
                $bEnd   = $b->booked_time_end 
                            ? Carbon::parse($b->booked_time_end) 
                            : $bStart->copy()->addMinutes($package->duration_minutes); // fallback

                // Calculate which slotStart times are blocked
                $slotStart = Carbon::parse($bStart)->copy()->floorMinutes($package->duration_minutes); // rounds down to nearest interval
                $blockedTimes[] = $slotStart->format('g:i A');
            }
            return array_unique($blockedTimes);
        });

        
        foreach ($package->vendorTimeSlots as $timeSlot) {
            $start      = Carbon::parse($timeSlot->start_time);
            $end        = Carbon::parse($timeSlot->end_time);
            $interval   = $package->duration_minutes;

            $times = [];
            while ($start < $end) {
                $times[] = $start->format('g:i A');
                $start->addMinutes($interval);
            }

            $slots[] = [
                'id'            => $timeSlot->id,
                'court'         => $timeSlot->slot_name ?? 'Slot '.$timeSlot->id,
                'times'         => $times,
                'bookedTimes'   => $groupedBookings[$timeSlot->id] ?? []
            ];
        }

        $offDays = VendorOffDay::where('organizer_id', $package->organizer->id)
            ->whereDate('off_date', $date)
            ->get();

        VisitorLogger::log(
            'check_slot',
            'calendar',
            $packageId,
            [
                'date' => $request->input('date'),
            ]
        );


        return response()->json([
            'date'   => $date,
            'slots'  => $slots,
            'vendorOffTimes' => $offDays,
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

    public function whatsappNow(Request $request)
    {
        VisitorLogger::log(
            'whatsapp_click',
            'package',
            $request->package_id,
            [
                'date'          => $request->date,
                'time'          => $request->time,        
                'slot_name'     => $request->slot_name 
            ]
        );

        return response()->json(['status' => 'ok']);
    }

    public function getBanners($id)
    {
        $organizer = Organizer::findOrFail($id);

        return response()->json([
            'id' => $organizer->id,
            'banners' => $organizer->banner_path ?? []
        ]);
    }

    public function getPackageImages($id)
    {
        $packages = Package::with('images')
            ->where('organizer_id', $id)
            ->get();

        return response()->json([
            'packages' => $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    'images' => $package->images->map(function ($img) use ($package) {
                        return [
                            'url' => asset("images/organizers/{$package->organizer_id}/packages/{$package->id}/{$img->url}"),
                            'alt' => $img->alt_text
                        ];
                    })
                ];
            })
        ]);
    }

}