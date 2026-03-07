<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\EmailLog;
use App\Models\Ticket;
use App\Models\Package;
use App\Models\Event;
use App\Models\VendorTimeSlot;
use App\Models\VendorTimeSlotLimit;
use App\Models\SlotImage;
use App\Models\VendorOffDay;
use App\Models\Worker;
use App\Models\WalletTransaction;
use App\Models\Organizer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Http;

class OrganizerBusinessController extends Controller
{
    public function dashboard()
    {
        $page_title = 'Dashboard';
        $authUser = auth()->guard('organizer')->user()->load('user');
        $organizerId = $authUser->id;

        // Get first 100 confirmed bookings with shirt_size info
        // $shirtSizeData = Booking::where('organizer_id', $authUser->id)
        //     ->where('status', 'confirmed')
        //     ->take(100)
        //     ->get()
        //     ->map(function ($booking) {
        //         $extraRaw = $booking->extra_info;

        //         // Skip if null or empty
        //         if (empty($extraRaw))
        //             return null;

        //         // Decode if it's a string (JSON)
        //         $extra = is_string($extraRaw) ? json_decode($extraRaw, true) : (array) $extraRaw;

        //         // Skip if not array or doesn't contain shirt_size
        //         if (!is_array($extra) || empty($extra['shirt_size']))
        //             return null;

        //         return $extra['shirt_size'];
        //     })
        //     ->filter()
        //     ->countBy();

        // $shirtSizes = $shirtSizeData->keys();
        // $shirtCounts = $shirtSizeData->values();

        // Basic stats
        $totalEvents = Event::where('organizer_id', $organizerId)->count();
        $ticketSold = BookingTicket::whereIn('status', ['printed', 'checkin'])
            ->whereHas('booking', fn($q) => $q->where('organizer_id', $organizerId)->where('status', 'confirmed'))
            ->count();
        $totalBookings = Booking::where('organizer_id', $organizerId)->count();
        $totalIncome = Booking::where('organizer_id', $organizerId)->where('status', 'confirmed')->sum('final_price');
        $pendingBookings = Booking::where('organizer_id', $organizerId)->where('status', 'pending')->count();
        $confirmBookings = Booking::where('organizer_id', $organizerId)->where('status', 'confirmed')->count();

        // Get top 3 events by booking count
        $topEvents = Event::where('organizer_id', $organizerId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        $salesChartData = [];

        foreach ($topEvents as $event) {
            $monthlySales = Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as total')
                ->where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            $salesChartData[] = [
                'name' => $event->title,
                'className' => 'bg-primary',
                'data' => collect(range(1, 12))->map(fn($m) => (int) ($monthlySales[$m] ?? 0))->toArray()
            ];
        }

        $topPackages = Package::where('organizer_id', $organizerId)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        $salesChartData = [];

        foreach ($topPackages as $package) {
            $monthlySales = Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as total')
                ->where('package_id', $package->id)
                ->where('status', 'paid')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            $salesChartData[] = [
                'name' => $package->name,
                'className' => 'bg-success', // or any color
                'data' => collect(range(1, 12))->map(fn($m) => (int) ($monthlySales[$m] ?? 0))->toArray()
            ];
        }


        return view('organizer.index', compact(
            'page_title',
            'authUser',
            'totalEvents',
            'ticketSold',
            'totalBookings',
            'totalIncome',
            'confirmBookings',
            'pendingBookings',
            'salesChartData',
        ));
    }

    public function bookings(Request $request)
    {
        $page_title = 'Bookings List';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $events = null;
        // Fetch events for the current organizer to populate dropdown
        $packages = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'name')
            ->orderBy('order_by')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs from those events
        $bookingVendorTimeSlots = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        $bookingIds = DB::table('bookings')
            ->whereIn('id', $bookingVendorTimeSlots)
            ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $bookings = Booking::with(['vendorTimeSlots', 'participant', 'package:id,name', 'promoter:id,name'])
            ->whereIn('id', $bookingIds)

            ->when($request->status, 
                fn($q) => $q->where('status', $request->status)
            )

            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('package', function ($q) use ($event_search) {
                    $q->where('name', $event_search);
                });
            })

            ->when($request->date, function ($query, $date) {
                $query->whereHas('vendorTimeSlots', function ($q) use ($date) {
                    $q->whereDate('booked_date_start', $date);
                });
            })

            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('participant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('no_ic', 'like', "%{$search}%");
                    })
                    ->orWhere('booking_code', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(15)
            ->withQueryString();

            // Log::info("bookings");
            // Log::info($bookings);
        return view('organizer.booking.index', compact('page_title', 'authUser', 'bookings', 'packages', 'events'));
    }

    public function showPackages(Request $request)
    {
        $page_title = 'Packages List';
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        $categoriesIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('category_id');

        // Fetch categories for the current organizer to populate dropdown
        $categories = DB::table('package_categories')
            ->whereIn('id', $categoriesIds)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Step 2: Get all ticket IDs from those events
        $bookings = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        // $bookingIds = DB::table('bookings')
        //     ->whereIn('id', $bookingVendorTimeSlots)
        //     ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $packages = Package::with(['items', 'category', 'addons', 'images'])
            ->whereIn('id', $packageIds)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            // ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category_search, function ($query, $category_search) {
                $query->whereHas('category', function ($q) use ($category_search) {
                    $q->where('name', $category_search);
                });
            })
            ->latest()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('organizer.package.index', compact('page_title', 'authUser', 'packages' ,'categories'));
    }

    public function showCreatePackage(Request $request)
    {
        $page_title = 'Create Package';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $categories = DB::table('package_categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('organizer.package.create', compact('page_title', 'authUser', 'categories'));
    }

    public function storePackage(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'slug'       => 'required|string|max:255|unique:packages,slug',
            'category_id'=> 'required|exists:package_categories,id',
            'base_price' => 'required|numeric|min:0',
            'final_price'=> 'required|numeric|min:0',
            'status'     => 'required|in:active,inactive,draft',
        ]);

        $package = Package::create([
            'organizer_id'              => $authUser->id,
            'category_id'               => $request->category_id,
            'name'                      => $request->name,
            'slug'                      => $request->slug,
            'package_code'              => $request->package_code,
            'description'               => $request->description,
            'tnc'                       => $request->tnc,
            'base_price'                => $request->base_price,
            'final_price'               => $request->final_price,
            'discount_percentage'       => $request->discount_percentage ?: null,
            'deposit_percentage'        => $request->deposit_percentage ?: null,
            'deposit_fixed'             => $request->deposit_fixed ?: null,
            'service_charge_percentage' => $request->service_charge_percentage ?: null,
            'service_charge_fixed'      => $request->service_charge_fixed ?: 1,
            'duration_minutes'          => $request->duration_minutes ?: 0,
            'rest_minutes'              => $request->rest_minutes ?: 0,
            'package_slot_quantity'     => $request->package_slot_quantity ?: 1,
            'order_by'                  => $request->order_by ?: 0,
            'status'                    => $request->status,
            'valid_from'                => $request->valid_from ?: null,
            'valid_until'               => $request->valid_until ?: null,
            'last_paid_date'            => $request->last_paid_date ?: null,
            'max_booking_year_offset'   => $request->max_booking_year_offset ?: null,
            'is_manual'                 => $request->boolean('is_manual'),
        ]);

        // Package Items
        foreach ($request->input('items', []) as $item) {
            if (empty($item['title'])) continue;
            $package->items()->create([
                'title'        => $item['title'],
                'description'  => $item['description'] ?? null,
                'quantity'     => $item['quantity'] ?? 1,
                'unit_price'   => $item['unit_price'] ?? null,
                'is_optional'  => isset($item['is_optional']),
                'show_on_card' => isset($item['show_on_card']),
                'sort_order'   => $item['sort_order'] ?? 0,
            ]);
        }

        // Package Addons
        foreach ($request->input('addons', []) as $addon) {
            if (empty($addon['name'])) continue;
            $package->addons()->create([
                'name'         => $addon['name'],
                'hint'         => $addon['hint'] ?? null,
                'description'  => $addon['description'] ?? null,
                'price'        => $addon['price'] ?? 0,
                'is_required'  => isset($addon['is_required']),
                'is_time'      => isset($addon['is_time']),
                'time_minutes' => $addon['time_minutes'] ?? null,
            ]);
        }

        // Package Discounts
        if ($request->filled('discount.amount')) {
            $package->discounts()->create([
                'type'      => $request->input('discount.type', 'percentage'),
                'amount'    => $request->input('discount.amount'),
                'starts_at' => $request->input('discount.starts_at') ?: null,
                'ends_at'   => $request->input('discount.ends_at') ?: null,
                'is_active' => true,
            ]);
        }

        // Package Images (with optional temp-upload move)
        $tempId = $request->input('temp_id');
        foreach ($request->input('images', []) as $image) {
            if (empty($image['url'])) continue;
            $filename = $image['url'];
            if (!empty($image['is_temp']) && $tempId) {
                $tempPath = "uploads/temp/{$authUser->id}/{$tempId}/{$filename}";
                $destPath = "uploads/{$authUser->id}/packages/{$package->id}/{$filename}";
                if (\Storage::disk('public')->exists($tempPath)) {
                    \Storage::disk('public')->move($tempPath, $destPath);
                }
            }
            $package->images()->create([
                'url'        => $filename,
                'alt_text'   => $image['alt_text'] ?? null,
                'is_cover'   => isset($image['is_cover']),
                'sort_order' => $image['sort_order'] ?? 0,
            ]);
        }

        // Booking Form Fields
        foreach ($request->input('form_fields', []) as $field) {
            if (empty($field['field_label'])) continue;
            $package->formFields()->create([
                'field_label' => $field['field_label'],
                'field_key'   => $field['field_key'] ?? \Illuminate\Support\Str::snake($field['field_label']),
                'field_type'  => $field['field_type'] ?? 'text',
                'options'     => !empty($field['options']) ? json_decode($field['options'], true) : null,
                'is_required' => isset($field['is_required']),
            ]);
        }

        return redirect()
            ->route('organizer.business.packages')
            ->with('success', 'Package "' . $package->name . '" created successfully.');
    }

    public function showEditPackage($id)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');
        $page_title = 'Edit Package';

        $package = Package::where('organizer_id', $authUser->id)
            ->with(['items', 'addons', 'discounts', 'images', 'formFields'])
            ->findOrFail($id);

        $categories = DB::table('package_categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('organizer.package.edit', compact('page_title', 'authUser', 'package', 'categories'));
    }

    public function updatePackage(Request $request, $id)
    {
        $authUser = auth()->guard('organizer')->user();

        $package = Package::where('organizer_id', $authUser->id)->findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'slug'       => 'required|string|max:255|unique:packages,slug,' . $id,
            'category_id'=> 'required|exists:package_categories,id',
            'base_price' => 'required|numeric|min:0',
            'final_price'=> 'required|numeric|min:0',
            'status'     => 'required|in:active,inactive,draft',
        ]);

        $package->update([
            'category_id'               => $request->category_id,
            'name'                      => $request->name,
            'slug'                      => $request->slug,
            'package_code'              => $request->package_code,
            'description'               => $request->description,
            'tnc'                       => $request->tnc,
            'base_price'                => $request->base_price,
            'final_price'               => $request->final_price,
            'discount_percentage'       => $request->discount_percentage ?: null,
            'deposit_percentage'        => $request->deposit_percentage ?: null,
            'deposit_fixed'             => $request->deposit_fixed ?: null,
            'service_charge_percentage' => $request->service_charge_percentage ?: null,
            'service_charge_fixed'      => $request->service_charge_fixed ?: 1,
            'duration_minutes'          => $request->duration_minutes ?: 0,
            'rest_minutes'              => $request->rest_minutes ?: 0,
            'package_slot_quantity'     => $request->package_slot_quantity ?: 1,
            'order_by'                  => $request->order_by ?: 0,
            'status'                    => $request->status,
            'valid_from'                => $request->valid_from ?: null,
            'valid_until'               => $request->valid_until ?: null,
            'last_paid_date'            => $request->last_paid_date ?: null,
            'max_booking_year_offset'   => $request->max_booking_year_offset ?: null,
            'is_manual'                 => $request->boolean('is_manual'),
        ]);

        // Sync Items
        $package->items()->delete();
        foreach ($request->input('items', []) as $item) {
            if (empty($item['title'])) continue;
            $package->items()->create([
                'title'        => $item['title'],
                'description'  => $item['description'] ?? null,
                'quantity'     => $item['quantity'] ?? 1,
                'unit_price'   => $item['unit_price'] ?? null,
                'is_optional'  => isset($item['is_optional']),
                'show_on_card' => isset($item['show_on_card']),
                'sort_order'   => $item['sort_order'] ?? 0,
            ]);
        }

        // Sync Addons
        $package->addons()->delete();
        foreach ($request->input('addons', []) as $addon) {
            if (empty($addon['name'])) continue;
            $package->addons()->create([
                'name'         => $addon['name'],
                'hint'         => $addon['hint'] ?? null,
                'description'  => $addon['description'] ?? null,
                'price'        => $addon['price'] ?? 0,
                'is_required'  => isset($addon['is_required']),
                'is_time'      => isset($addon['is_time']),
                'time_minutes' => $addon['time_minutes'] ?? null,
            ]);
        }

        // Sync Discounts
        $package->discounts()->delete();
        if ($request->filled('discount.amount')) {
            $package->discounts()->create([
                'type'      => $request->input('discount.type', 'percentage'),
                'amount'    => $request->input('discount.amount'),
                'starts_at' => $request->input('discount.starts_at') ?: null,
                'ends_at'   => $request->input('discount.ends_at') ?: null,
                'is_active' => true,
            ]);
        }

        // Sync Images
        $package->images()->delete();
        foreach ($request->input('images', []) as $image) {
            if (empty($image['url'])) continue;
            $package->images()->create([
                'url'        => $image['url'],
                'alt_text'   => $image['alt_text'] ?? null,
                'is_cover'   => isset($image['is_cover']),
                'sort_order' => $image['sort_order'] ?? 0,
            ]);
        }

        // Sync Booking Form Fields
        $package->formFields()->delete();
        foreach ($request->input('form_fields', []) as $field) {
            if (empty($field['field_label'])) continue;
            $package->formFields()->create([
                'field_label' => $field['field_label'],
                'field_key'   => $field['field_key'] ?? \Illuminate\Support\Str::snake($field['field_label']),
                'field_type'  => $field['field_type'] ?? 'text',
                'options'     => !empty($field['options']) ? json_decode($field['options'], true) : null,
                'is_required' => isset($field['is_required']),
            ]);
        }

        return redirect()
            ->route('organizer.business.packages')
            ->with('success', 'Package "' . $package->name . '" updated successfully.');
    }

    public function destroyPackage($id)
    {
        $authUser = auth()->guard('organizer')->user();
        $package  = Package::where('organizer_id', $authUser->id)->findOrFail($id);
        $package->delete();

        return redirect()
            ->route('organizer.business.packages')
            ->with('success', 'Package deleted.');
    }

    public function uploadPackageImage(Request $request, $id)
    {
        $authUser = auth()->guard('organizer')->user();
        $package  = Package::where('organizer_id', $authUser->id)->findOrFail($id);

        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $file      = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename  = \Illuminate\Support\Str::uuid() . '.' . $extension;
        $folder    = "uploads/{$authUser->id}/packages/{$package->id}";
        $file->storeAs($folder, $filename, 'public');

        $image = $package->images()->create([
            'url'        => $filename,
            'alt_text'   => $request->input('alt_text', ''),
            'is_cover'   => $package->images()->count() === 0,
            'sort_order' => $package->images()->max('sort_order') + 1,
        ]);

        return response()->json([
            'success'   => true,
            'url'       => asset("storage/{$folder}/{$filename}"),
            'filename'  => $filename,
            'image_id'  => $image->id,
        ]);
    }

    public function uploadTempImage(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();

        $request->validate([
            'image'   => 'required|image|max:5120',
            'temp_id' => 'required|string|max:64',
        ]);

        $file     = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $folder   = "uploads/temp/{$authUser->id}/{$request->temp_id}";
        $file->storeAs($folder, $filename, 'public');

        return response()->json([
            'success'  => true,
            'url'      => asset("storage/{$folder}/{$filename}"),
            'filename' => $filename,
        ]);
    }

    public function ticketsConfirmed(Request $request)
    {
        $page_title = 'Tickets List Confirmed';
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Fetch events for the current organizer to populate dropdown
        $events = DB::table('events')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $eventIds = DB::table('events')
            ->where('organizer_id', $authUser->id)
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

    public function cancelBooking($id)
    {
        $booking = Booking::with(['participant','vendorTimeSlots','package.organizer'])->findOrFail($id);

        if ($booking->status !== 'cancelled') {

            DB::beginTransaction();

            try {

                $organizer = $booking->package->organizer;

                $refundAmount = $booking->paid_amount;

                /*
                |--------------------------------------------------------------------------
                | WALLET REFUND (if payment already exists)
                |--------------------------------------------------------------------------
                */

                if ($refundAmount > 0) {

                    $before = $organizer->wallet_balance ?? 0;
                    $after  = $before - $refundAmount;

                    WalletTransaction::create([
                        'organizer_id' => $organizer->id,
                        'type' => 'refund',
                        'amount' => -$refundAmount,
                        'balance_before' => $before,
                        'balance_after' => $after,
                        'reference_type' => Booking::class,
                        'reference_id' => $booking->id,
                        'description' => 'Refund booking cancellation - '.$booking->booking_code,
                        'status' => 'completed'
                    ]);

                    $organizer->update([
                        'wallet_balance' => $after
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE BOOKING
                |--------------------------------------------------------------------------
                */

                $booking->status = 'cancelled';
                $booking->save();

                $booking->vendorTimeSlots()->update([
                    'status' => 'cancelled'
                ]);

                DB::commit();

                return redirect()->back()->with('success', 'Booking has been cancelled.');

            } catch (\Exception $e) {

                DB::rollBack();

                return redirect()->back()->with('error', 'Cancel failed.');
            }
        }

        return redirect()->back()->with('error', 'This booking is already cancelled.');
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

    public function fetchCalendarData($id)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');

        // Find the package under this organizer
        $package = Package::whereHas('organizer', function ($query) use ($authUser) {
            $query->where('id', $authUser->id);
        })
            ->where('id', $id)
            ->with([
                'addons',
                'items',
                'discounts',
                'category',
                'images',
                'organizer',
            ])
            ->firstOrFail();

        $bookedDates = DB::table('bookings_vendor_time_slot')
            ->where('organizer_id', $authUser->id)
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

        $timeSlots = VendorTimeSlot::where('organizer_id', $authUser->id)
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

            if (!$package->duration_minutes || $end->lessThanOrEqualTo($start)) {
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
            ->where('organizer_id', $authUser->id)
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


        $slotLimits = VendorTimeSlotLimit::where('organizer_id', $authUser->id)
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

        $offDays = VendorOffDay::where('organizer_id', $authUser->id)
            ->whereDate('off_date', '>=', now())
            ->get();

        Log::info($timeSlots);
        return response()->json([
            'authUser'          => $authUser,
            'package'           => $package,
            'timeSlots'         => $timeSlots,
            'offDays'           => $offDays,
            'bookedDates'       => $bookedDates,
            'fullyBookedDates'  => $fullyBookedDates,
            'limitReachedDays'  => $limitReachedDays,
            'weekRangeBlock'    => $weekRangeBlock,
        ]);


    }

    public function showBooking($id)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');

        $booking = Booking::with([
            'participant',
            'package',
            'vendorTimeSlots.vendorTimeSlot',
            'bookingAddons.addon',
            'details',
            'promoter',
        ])
            ->where('organizer_id', $authUser->id)
            ->findOrFail($id);

        $page_title = 'Booking Details';

        return view('organizer.booking.show', compact('page_title', 'authUser', 'booking'));
    }

    public function editBooking($id)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');

        $booking = Booking::with([
            'participant',
            'package.addons',
            'vendorTimeSlots.vendorTimeSlot',
            'bookingAddons.addon',
            'details',
            'promoter',
        ])
            ->where('organizer_id', $authUser->id)
            ->findOrFail($id);

        $promoters = Worker::where('organizer_id', $authUser->id)
            ->where('is_active', 2)
            ->get();

        // Keyed by addon_id for quick lookup in view
        $currentAddons = $booking->bookingAddons->keyBy('addon_id');

        $page_title = 'Edit Booking';

        return view('organizer.booking.edit', compact('page_title', 'authUser', 'booking', 'promoters', 'currentAddons'));
    }

    public function updateBooking(Request $request, $id)
    {
        $authUser = auth()->guard('organizer')->user();

        $booking = Booking::with(['participant', 'vendorTimeSlots', 'bookingAddons'])
            ->where('organizer_id', $authUser->id)
            ->findOrFail($id);

        $request->validate([
            'status'       => 'required|in:pending,paid,cancelled',
            'payment_type' => 'required|in:deposit,full_payment',
            'paid_amount'  => 'required|numeric|min:0',
            'notes'        => 'nullable|string|max:1000',
            'addons'       => 'nullable|array',
            'addons.*'     => 'integer|min:0',
            'details'      => 'nullable|array',
            'details.*'    => 'nullable|string|max:1000',
        ]);

        // Participant fields
        $booking->participant->update([
            'name'  => $request->input('name', $booking->participant->name),
            'phone' => $request->input('phone', $booking->participant->phone),
            'email' => $request->filled('email') ? $request->input('email') : $booking->participant->email,
        ]);

        // Sync additional info (booking details)
        foreach ($request->input('details', []) as $key => $value) {
            $booking->details()->where('field_key', $key)->update(['field_value' => $value]);
        }

        // Sync vendor time slot notes
        if ($request->filled('notes')) {
            $booking->vendorTimeSlots()->update(['notes' => $request->notes]);
        }

        // Calculate current addon total before deleting
        $currentAddonTotal = 0;
        $booking->load('bookingAddons.addon');
        foreach ($booking->bookingAddons as $ba) {
            $currentAddonTotal += ($ba->addon->price ?? 0) * $ba->qty;
        }
        $basePrice = ($booking->total_price ?? 0) - $currentAddonTotal;

        // Sync addons: addons[addon_id] = qty
        $booking->bookingAddons()->delete();

        $newAddonTotal = 0;
        $addonPrices = \App\Models\PackageAddon::whereIn('id', array_keys($request->input('addons', [])))->get()->keyBy('id');

        foreach ($request->input('addons', []) as $addonId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                \App\Models\BookingAddon::create([
                    'booking_id' => $booking->id,
                    'addon_id'   => (int) $addonId,
                    'qty'        => $qty,
                ]);
                $newAddonTotal += ($addonPrices[$addonId]->price ?? 0) * $qty;
            }
        }

        $newTotalPrice = $basePrice + $newAddonTotal;
        $paidAmount    = (float) $request->paid_amount;
        $paymentType   = $request->payment_type;

        if ($paymentType === 'full_payment' && $paidAmount < $newTotalPrice) {
            // Added addons: paid no longer covers total → downgrade to deposit
            $paymentType = 'deposit';
        } elseif ($paymentType === 'full_payment' && $paidAmount > $newTotalPrice) {
            // Removed addons: overpaid → clamp paid down to new total (refund handled externally)
            $paidAmount = $newTotalPrice;
        }

        $booking->update([
            'status'       => $request->status,
            'payment_type' => $paymentType,
            'paid_amount'  => $paidAmount,
            'total_price'  => $newTotalPrice,
        ]);

        // Receipt upload
        if ($request->hasFile('resit')) {
            $file     = $request->file('resit');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/receipts'), $filename);
            $booking->update(['resit_path' => $filename]);
        }

        return redirect()
            ->route('organizer.business.booking.show', $booking->id)
            ->with('success', 'Booking updated successfully.');
    }

    public function showCreateBooking(Request $request)
    {
        $page_title = 'Create Booking';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $packages = Package::query()
            ->active()
            ->sortedAsc()
            ->select('id', 'name', 'organizer_id', 'package_slot_quantity')
            ->where('organizer_id', $authUser->id)
            ->with([
                'addons',
                'items',
                'discounts',
                'category',
                'images',
                'organizer',
            ])
            ->get();

        $limitReachedDays   = [];      
        $weekRangeBlock     = [];
        $fullyBookedDates   = [];
        $offDays            = [];
        $timeSlots          = [];
        $bookedDates        = [];

        $promoter = Worker::where('organizer_id', $authUser->id)
                ->where('is_active', 2)->get();

        return view('organizer.booking.create', [
            'page_title'        => $page_title,
            'authUser'          => $authUser,
            'packages'          => $packages,
            'timeSlots'         => $timeSlots,
            'offDays'           => $offDays,
            'bookedDates'       => $bookedDates,
            'fullyBookedDates'  => $fullyBookedDates,
            'limitReachedDays'  => $limitReachedDays,
            'weekRangeBlock'    => $weekRangeBlock,
            'promoter'          => $promoter,
        ]);


    }

    public function calender(Request $request)
    {
        $page_title = 'Calender';
        $authUser = auth()->guard('organizer')->user()->load('user');

        $events = null;
        // Fetch events for the current organizer to populate dropdown
        $packages = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Step 1: Get all event IDs by this organizer
        $packageIds = DB::table('packages')
            ->where('organizer_id', $authUser->id)
            ->pluck('id');

        // Step 2: Get all ticket IDs from those events
        $bookingVendorTimeSlots = DB::table('bookings_vendor_time_slot')
            ->whereIn('package_id', $packageIds)
            ->pluck('booking_id');

        // Step 3: Get all booking IDs from booking_tickets
        $bookingIds = DB::table('bookings')
            ->whereIn('id', $bookingVendorTimeSlots)
            ->pluck('id');

        // Step 4: Fetch bookings with optional status & search filters
        $bookings = Booking::with(['vendorTimeSlots', 'participant', 'package:id,name'])
            ->whereIn('id', $bookingIds)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->event_search, function ($query, $event_search) {
                $query->whereHas('package', function ($q) use ($event_search) {
                    $q->where('name', $event_search);
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('participant', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('no_ic', 'like', "%{$search}%")
                            ->orWhere('booking_code', 'like', "%{$search}%");
                    });
                });
            })

            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('organizer.booking.calender', compact('page_title', 'authUser', 'bookings', 'packages', 'events'));
    }

    public function getBookingsJson(Request $request)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');
        $organizerId = $authUser->id;

        $mode = $request->get('mode', 'detail', 'week');

        // MONTH SUMMARY MODE
        if ($mode === 'month') {

            $rows = DB::table('bookings')
                ->where('bookings.organizer_id', $organizerId)
                ->where('bookings.status', '!=', 'cancelled')
                ->join('bookings_vendor_time_slot as slots', 'slots.booking_id', '=', 'bookings.id')
                ->leftJoin('packages', 'packages.id', '=', 'bookings.package_id')
                ->select(
                    DB::raw("DATE(slots.booked_date_start) as date"),
                    'packages.name as package',
                    'bookings.status',
                    DB::raw('COUNT(DISTINCT bookings.id) as total')
                )
                ->groupBy('date', 'package', 'bookings.status')
                ->get();

            return $rows->map(function ($row) {
                $color = match ($row->status) {
                    'paid' => '#16a34a',
                    'deposit' => '#f59e0b',
                    'cancelled' => '#dc2626',
                    default => '#2563eb',
                };

                return [
                    'title' => $row->package,
                    'start' => $row->date,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'count' => $row->total,
                    ]
                ];
            });
        }

        if ($mode === 'week') {

            $bookings = Booking::with([
                'vendorTimeSlots.timeSlot',
                'package'
            ])
            ->where('bookings.status', '!=', 'cancelled')
            ->get();

            $events = [];

            foreach ($bookings as $booking) {

                if ($booking->vendorTimeSlots->isEmpty()) continue;

                $start = null;
                $end   = null;
                $slotNames = [];

                foreach ($booking->vendorTimeSlots as $slot) {

                    $slotStart = $slot->booked_date_start . 'T' . $slot->booked_time_start;
                    $slotEnd   = $slot->booked_date_end   . 'T' . $slot->booked_time_end;

                    if (!$start || $slotStart < $start) {
                        $start = $slotStart;
                    }

                    if (!$end || $slotEnd > $end) {
                        $end = $slotEnd;
                    }

                    if ($slot->timeSlot?->slot_name) {
                        $slotNames[] = $slot->timeSlot->slot_name;
                    }
                }

                $color = match ($booking->status) {
                    'paid' => '#16a34a',
                    'deposit' => '#f59e0b',
                    'cancelled' => '#dc2626',
                    default => '#2563eb',
                };

                $events[] = [
                    'id' => 'w-'.$booking->id,
                    'code' => $booking->booking_code,
                    'title' => $booking->package?->name ?? 'Booking',
                    'start' => $start,
                    'end' => $end,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'slot' => implode(', ', array_unique($slotNames)), 
                    ],
                ];
            }


            return $events;
        }

        // --------------------------------------------------
        // WEEK / DAY (DETAILED MODE)
        // --------------------------------------------------

        $events = [];

        $bookings = Booking::with(['vendorTimeSlots.timeSlot:id,slot_name', 'participant', 'package', 'bookingAddons.addon', 'details'])
            ->where('organizer_id', $organizerId)
            ->where('bookings.status', '!=', 'cancelled')
            ->whereHas('vendorTimeSlots')
            ->get();

        foreach ($bookings as $booking) {

            $addons = [];

            foreach ($booking->bookingAddons as $bookingAddon) {
                if ($bookingAddon->addon) {
                    $addons[] = $bookingAddon->addon->name . ' x' . $bookingAddon->qty;
                }
            }

            if ($booking->vendorTimeSlots->isEmpty()) {
                continue;
            }

            $start = null;
            $end   = null;
            $slotNames = [];

            foreach ($booking->vendorTimeSlots as $slot) {

                $slotStart = $slot->booked_date_start . 'T' . $slot->booked_time_start;
                $slotEnd   = $slot->booked_date_end   . 'T' . $slot->booked_time_end;

                // find earliest start
                if (!$start || $slotStart < $start) {
                    $start = $slotStart;
                }

                // find latest end
                if (!$end || $slotEnd > $end) {
                    $end = $slotEnd;
                }

                if ($slot->timeSlot?->slot_name) {
                    $slotNames[] = $slot->timeSlot->slot_name;
                }
            }

            // calculate balance
            $balance = 0;
            if ($booking->is_deposit) {
                $balance = $booking->final_price - $booking->paid_amount;
            }

            $color = match ($booking->status) {
                'paid' => '#16a34a',
                'deposit' => '#f59e0b',
                'cancelled' => '#dc2626',
                default => '#2563eb',
            };

            $customFields = [];

            foreach ($booking->details as $detail) {
                $customFields[$detail->field_key] = $detail->field_value;
            }

            $events[] = [
                'id'    => $booking->id, // IMPORTANT: booking id, not slot id
                'title' => $booking->package?->name ?? 'Booking',
                'start' => $start,
                'end'   => $end,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'extendedProps' => [
                    'customer'      => $booking->participant?->name ?? 'Unknown',
                    'phone'         => $booking->participant?->phone ?? '',
                    'status'        => $booking->status,
                    'code'          => $booking->booking_code,
                    'slot'          => implode(', ', $slotNames), // merged slots
                    'is_deposit'    => $booking->is_deposit,
                    'deposit'       => $booking->paid_amount,
                    'balance'       => $balance,
                    'booking_id'    => $booking->id,
                    'addons'        => implode(', ', $addons),
                    'custom_fields' => $customFields,
                ],
            ];
        }

        return $events;
    }

    public function holidays()
    {
        $year = request('year', now()->year);

        // Fetch from free Nager.Date API
        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/MY");

        if ($response->failed()) {
            return [];
        }

        $holidays = $response->json();

        // return for FullCalendar
        return collect($holidays)->map(function ($h) {
            return [
                'title' => $h['localName'], // e.g., "Hari Raya Aidilfitri"
                'start' => $h['date'],      // full day
                'allDay' => true,
                'display' => 'background',   // background event
                'classNames' => ['fc-holiday'] // custom CSS class
            ];
        });
    }

    public function makeFullPayment($id)
    {
        $booking = Booking::with(['participant', 'vendorTimeSlots'])->findOrFail($id);


        if ($booking->payment_type === 'deposit' && $booking->status == 'paid') {
            $remainingAmount        = $booking->final_price - $booking->paid_amount;
            $booking->paid_amount   = $booking->final_price; 
            $booking->payment_type  = 'full_payment';
            $booking->save();

            $booking->vendorTimeSlots()->update(['status' => 'full_payment']);

            /*
            |--------------------------------------------------------------------------
            | WALLET TRANSACTION
            |--------------------------------------------------------------------------
            */

            if ($remainingAmount > 0) {

                $organizer = $booking->package->organizer;

                $before = $organizer->wallet_balance ?? 0;
                $after  = $before + $remainingAmount;

                WalletTransaction::create([
                    'organizer_id'      => $organizer->id,
                    'type'              => 'income',
                    'amount'            => $remainingAmount,
                    'balance_before'    => $before,
                    'balance_after'     => $after,
                    'reference_type'    => Booking::class,
                    'reference_id'      => $booking->id,
                    'description'       => 'Remaining booking payment - ' . $booking->booking_code,
                    'status'            => 'completed'
                ]);

                $organizer->update([
                    'wallet_balance' => $after
                ]);
            }

            $booking->load('vendorTimeSlots');

            // Send email to booking's email
            // Mail::to($booking->participant->email)->send(new PaymentConfirmed($booking));

            // EmailLog::create([
            //     'to_email' => $booking->participant->email,
            //     'type' => 'payment_confirmed',
            //     'sent_at' => now(),
            // ]);

            $receiptUrl = route('booking.receipt.package', $booking->booking_code);

            $text = "Hai " . ucfirst($booking->participant->name) . ",\n\n";

            $text .= "🎉 Tahniah! Baki bayaran anda telah berjaya dijelaskan sepenuhnya.\n";
            $text .= "Tempahan anda kini telah disahkan sebagai *FULL PAYMENT*.\n\n";

            $text .= "*Status Pembayaran*: ✅ Pembayaran Penuh Telah Diterima\n";
            $text .= "Jumlah Keseluruhan Dibayar: RM" . number_format(
                $booking->final_price,
                2
            ) . "\n\n";

            $text .= "📄 Resit Pembayaran Penuh boleh dimuat turun di sini:\n";
            $text .= $receiptUrl . "\n\n";

            $text .= "Terima kasih kerana menggunakan perkhidmatan SistemKami 🙏\n\n";
            $text .= "📌 Peringatan 📌\n";
            $text .= "⏱️ Sila datang 15 minit lebih awal sebelum slot anda.\n";
            $text .= "👟 Digalakkan pakai kasut yang sesuai (lelaki & perempuan) untuk gambar lebih cantik.\n";
            $text .= "👜 Wanita, bawa handbag untuk nampak lebih bergaya.";

            $whatsappUrl = 'https://api.whatsapp.com/send?phone=+6' . $booking->participant->phone
            . '&text=' . urlencode($text);

            // return redirect()->back()->with('success', 'Payment verified and booking has been updated to Full Payment.');
            return redirect()->back()->with([
                'success' => 'Payment verified and booking for ' . $booking->participant->name . ' has been updated to Full Payment.',
                'whatsapp_url' => $whatsappUrl,
            ]);

        }

        return redirect()->back()->with('error', 'Cannot verify this booking.');
    }

    public function overviewReport()
    {
        $page_title = 'Overview';
        $main_title = 'Report';
        $authUser = auth()->guard('organizer')->user()->load('user');

        return view('organizer.report.overview_report',
            compact('page_title','main_title','authUser')
        );
    }

    public function packageChartData()
    {
        $authUser = auth()->guard('organizer')->user();

        $packages = Package::where('organizer_id', $authUser->id)
            ->withCount(['bookings as paid_bookings_count' => function ($query) {
                $query->where('status', 'paid');
            }])
            ->withSum(['bookings as paid_revenue' => function ($query) {
                $query->where('status', 'paid');
            }], 'final_price')
            ->active()
            ->get()
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'bookings' => $package->paid_bookings_count ?? 0,
                    'revenue' => $package->paid_revenue ?? 0
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        return response()->json([
            'labels' => $packages->pluck('name'),
            'booking_series' => $packages->pluck('bookings'),
            'revenue_series' => $packages->pluck('revenue')
        ]);
    }

    public function addOnChartData()
    {
        $authUser = auth()->guard('organizer')->user();

        // Step 1: Clean sales per addon_id
        $salesSub = \DB::table('booking_addon')
            ->join('bookings', 'booking_addon.booking_id', '=', 'bookings.id')
            ->where('bookings.organizer_id', $authUser->id)
            ->where('bookings.status', 'paid')
            ->select(
                'booking_addon.addon_id',
                \DB::raw('SUM(booking_addon.qty) as total_sold')
            )
            ->groupBy('booking_addon.addon_id');

        // Step 2: Join + group by NAME only
        $addons = \DB::table('package_addons')
            ->join('packages', 'package_addons.package_id', '=', 'packages.id')
            ->leftJoinSub($salesSub, 'sales', function ($join) {
                $join->on('package_addons.id', '=', 'sales.addon_id');
            })
            ->where('packages.organizer_id', $authUser->id)
            ->where('packages.status', 'active')
            ->select(
                'package_addons.name',
                \DB::raw('SUM(COALESCE(sales.total_sold, 0)) as total_sold')
            )
            ->groupBy('package_addons.name')
            ->orderByDesc('total_sold')
            ->get();

        return response()->json([
            'labels' => $addons->pluck('name'),
            'series' => $addons->pluck('total_sold')
        ]);
    }

    public function slotChartData()
    {
        $authUser = auth()->guard('organizer')->user();

        // Step 1: Get booking count per slot_id
        $bookingSub = \DB::table('bookings_vendor_time_slot')
            ->join('bookings', 'bookings_vendor_time_slot.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->select(
                'bookings_vendor_time_slot.vendor_time_slot_id',
                \DB::raw('COUNT(*) as total_bookings')
            )
            ->groupBy('bookings_vendor_time_slot.vendor_time_slot_id');

        // Step 2: Join with vendor_time_slots and group by slot_name
        $slots = \DB::table('vendor_time_slots')
            ->leftJoinSub($bookingSub, 'booking_counts', function ($join) {
                $join->on('vendor_time_slots.id', '=', 'booking_counts.vendor_time_slot_id');
            })
            ->where('vendor_time_slots.organizer_id', $authUser->id)
            ->where('vendor_time_slots.is_active', 1)
            ->select(
                'vendor_time_slots.slot_name',
                \DB::raw('SUM(COALESCE(booking_counts.total_bookings, 0)) as total_bookings')
            )
            ->groupBy('vendor_time_slots.slot_name')
            ->orderByDesc('total_bookings')
            ->get();

        return response()->json([
            'labels' => $slots->pluck('slot_name'),
            'series' => $slots->pluck('total_bookings')
        ]);
    }

    public function sendReceipt($id)
    {
        $booking = Booking::with([
            'package',
            'vendorTimeSlots.timeSlot',
            'addons'
        ])->findOrFail($id);

        $authUser = auth()->guard('organizer')->user();

        $data = [
            'name' => $booking->participant->name ?? 'Customer',
            'whatsapp_number' => $booking->participant->phone ?? null,
        ];

        if (!$data['whatsapp_number']) {
            return back()->with('error', 'Nombor WhatsApp tidak dijumpai.');
        }

        $phone = preg_replace('/[^0-9]/', '', $data['whatsapp_number']);

        // convert 01xxxx → 601xxxx
        if (substr($phone, 0, 1) === '0') {
            $phone = '6' . $phone;
        }

        // =========================
        // Generate WhatsApp Text
        // =========================
        $name = ucfirst(strtolower($data['name']));
        $text = "Hai {$name} 👋\n\n";

        $text .= "Tempahan anda telah berjaya dibuat untuk:\n";
        $text .= "📸 Pakej: {$booking->package->name}\n";
        $text .= "👤 Organizer: {$authUser->name}\n\n";

        if ($booking->payment_type === 'deposit') {

            if ($booking->paid_amount > 0) {
                $text .= "💳 Status: Deposit diterima\n";
                $text .= "💰 Jumlah Deposit: RM" . number_format($booking->paid_amount, 2) . "\n";
            } else {
                $text .= "💳 Status: Tiada deposit dibayar\n";
            }

            $balance = ($booking->total_price + ($booking->service_charge ?? 0) - ($booking->discount ?? 0)) - $booking->paid_amount;

            $text .= "🧾 Baki Perlu Dibayar: RM" . number_format($balance, 2) . "\n\n";

        } else {

            $text .= "💳 Status Pembayaran: Penuh (Selesai)\n";
            $text .= "💰 Jumlah Dibayar: RM" . number_format($booking->paid_amount, 2) . "\n\n";
        }

        foreach ($booking->vendorTimeSlots as $slot) {

            $slotName = $slot->timeSlot->slot_name ?? '';
            $startDate = Carbon::parse($slot->booked_date_start)->format('d M Y');
            $startTime = Carbon::parse($slot->booked_time_start)->format('h:i A');
            $endTime   = Carbon::parse($slot->booked_time_end)->format('h:i A');

            if ($slotName && strtolower(trim($slotName)) !== 'slot') {
                $text .= "📌 Slot: {$slotName}\n";
            }

            $text .= "🗓 Tarikh: {$startDate}\n";
            $text .= "⏰ Masa: {$startTime} - {$endTime}\n\n";
        }

        if ($booking->addons->count()) {

            $text .= "✨ Add Ons Dipilih:\n";

            foreach ($booking->addons as $addon) {

                $qty = $addon->pivot->qty ?? 1;
                $text .= "• {$addon->name}";
                if ($qty > 1) {
                    $text .= " x{$qty}";
                }
                $text .= "\n";
            }

            $text .= "\n";
        }

        $receiptUrl = route('booking.receipt', $booking->booking_code);

        if ($booking->payment_type === 'deposit') {
            $text .= "📄 Resit Deposit:\n";
        } else {
            $text .= "📄 Resit Pembayaran Penuh:\n";
        }

        $text .= "{$receiptUrl}\n\n";
        // $text .= "Terima kasih kerana memilih kami ❤️\n\n";

        // =========================
        // Reminder & Location
        // =========================

        $organizer = $booking->package->organizer;

        $text .= "⏳ Reminder Penting\n";
        $text .= "Sila datang 15 minit lebih awal sebelum slot anda.\n\n";

        $text .= "📍 Lokasi Studio:\n";
        $text .= $organizer->office_name . "\n";
        $text .= $organizer->address_line1 . "\n";
        $text .= $organizer->postal_code . " " .
                $organizer->city . ", " .
                $organizer->state . "\n";

        // ======================
        // Google Maps Link Logic
        // ======================

        if ($organizer->is_gmaps_verified) {

            // guna nama bisnes
            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" .
                urlencode($organizer->office_name);

        } elseif ($organizer->latitude && $organizer->longitude) {

            // guna lat long
            $mapsUrl = "https://www.google.com/maps?q={$organizer->latitude},{$organizer->longitude}";

        } else {

            // fallback guna full address
            $fullAddress = $organizer->address_line1 . ', ' .
                $organizer->postal_code . ' ' .
                $organizer->city . ', ' .
                $organizer->state;

            $mapsUrl = "https://www.google.com/maps/search/?api=1&query=" .
                urlencode($fullAddress);
        }

        $text .= "Google Maps:\n{$mapsUrl}\n\n";

        // =========================
        // Send via Fonnte if token exists, else return WhatsApp URL
        // =========================
        if ($authUser->fonnte_token) {
            // Build rich Fonnte message (reminder-style, no receipt URL)
            $firstSlot  = $booking->vendorTimeSlots->first();
            $slotDate   = $firstSlot ? Carbon::parse($firstSlot->booked_date_start)->format('d M Y') : '-';
            $slotTime   = $firstSlot ? Carbon::parse($firstSlot->booked_time_start)->format('h:i A') : '';
            $slotEnd    = ($firstSlot && $firstSlot->booked_time_end)
                ? ' - ' . Carbon::parse($firstSlot->booked_time_end)->format('h:i A')
                : '';

            $balance    = ($booking->total_price + ($booking->service_charge ?? 0) - ($booking->discount ?? 0)) - $booking->paid_amount;
            $payLines   = [];
            if ($booking->payment_type === 'deposit') {
                $payLines[] = "Deposit: RM" . number_format($booking->paid_amount, 2);
                if ($balance > 0) {
                    $payLines[] = "Baki: RM" . number_format($balance, 2);
                }
            } else {
                $payLines[] = "Bayar Penuh: RM" . number_format($booking->paid_amount, 2);
            }

            $addressParts = array_filter([
                $organizer->office_name,
                $organizer->address_line1,
                $organizer->city,
                $organizer->state,
            ]);
            $addressLine = implode(', ', $addressParts);

            $fonnteLines = [
                "Hai {$name}! 👋",
                "",
                "Tempahan anda telah berjaya dibuat!",
                "",
                "Pakej: {$booking->package->name}",
            ];
            foreach ($payLines as $pl) {
                $fonnteLines[] = $pl;
            }
            $fonnteLines[] = "";
            $fonnteLines[] = "📅 Tarikh: {$slotDate}";
            $fonnteLines[] = "⏰ Masa: {$slotTime}{$slotEnd}";

            if ($addressLine) {
                $fonnteLines[] = "";
                $fonnteLines[] = "📍 Lokasi: {$addressLine}";
                $fonnteLines[] = $mapsUrl;
            }

            $fonnteLines[] = "";
            $fonnteLines[] = "📄 Resit:";
            $fonnteLines[] = "{$receiptUrl}";
            $fonnteLines[] = "";
            $fonnteLines[] = "📝 Peringatan:";
            $fonnteLines[] = "• Hadir 15 minit awal";
            $fonnteLines[] = "• Sampin, tudung & kasut siap dipakai";
            $fonnteLines[] = "• Lewat = tiada masa tambahan";
            $fonnteLines[] = "";
            $fonnteLines[] = "Terima kasih! Jumpa nanti! 😊";
            $fonnteLines[] = "- {$authUser->name}";

            $fonnteText = implode("\n", $fonnteLines);
            try {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $authUser->fonnte_token,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target'      => $phone,
                    'message'     => $fonnteText,
                    'countryCode' => '60',
                ]);

                if ($response->successful() && ($response->json('status') === true || $response->json('status') === 'true')) {
                    return response()->json(['success' => true, 'sent' => true]);
                }

                \Illuminate\Support\Facades\Log::warning('Fonnte send receipt failed, falling back to WhatsApp URL', [
                    'booking'  => $booking->booking_code,
                    'phone'    => $phone,
                    'msg_len'  => mb_strlen($fonnteText),
                    'response' => $response->json(),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Fonnte send receipt exception, falling back to WhatsApp URL', [
                    'booking' => $booking->booking_code,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        $whatsappUrl = "https://api.whatsapp.com/send?phone=+{$phone}&text=" . urlencode($text);

        return response()->json([
            'success' => true,
            'url'     => $whatsappUrl,
        ]);
    }

    public function getTransactions(Request $request)
    {
        $page_title = 'Transactions';
        $main_title = 'Report';
        $authUser = auth()->guard('organizer')->user();

        $workers = Worker::where('organizer_id', $authUser->id)->get();
        $transactions = WalletTransaction::with('reference')
            ->where('organizer_id', $authUser->id)
            ->latest()
            ->paginate(20);

        return view('organizer.report.transaction', compact('transactions', 'page_title', 'main_title', 'authUser', 'workers'));
    }

    public function storeWithdraw(Request $request)
    {
        $organizerId = auth()->guard('organizer')->id();

        // Get the organizer record
        $organizer = Organizer::find($organizerId);

        if (!$organizer) {
            return back()->with('error', 'Organizer not found');
        }

        // Check sufficient balance
        if ($request->amount > $organizer->wallet_balance) {
            return back()->with('error', 'Insufficient balance');
        }

        $balanceBefore = $organizer->wallet_balance;
        $balanceAfter = $balanceBefore - $request->amount;

        // If type is worker payout, get worker id and name
        $workerId = $request->type === 'worker_payout' ? $request->worker_id : null;
        $workerName = null;

        if ($request->type === 'worker_payout' && $workerId) {
            $worker = Worker::find($workerId);
            $workerName = $worker?->name; // null safe
        }

        // Create wallet transaction
        WalletTransaction::create([
            'organizer_id' => $organizerId,
            'type' => $request->type,
            'amount' => -$request->amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => $workerId ? Worker::class : null,
            'reference_id' => $workerId,
            'description' => $request->description 
                ?? ($request->type === 'withdrawal' 
                    ? 'Organizer withdraw' 
                    : ($workerName ? "Payout to $workerName" : 'Worker payout')),
            'status' => 'completed'
        ]);

        // Deduct from organizer's wallet_balance
        $organizer->wallet_balance = $balanceAfter;
        $organizer->save();

        return back()->with('success', 'Transaction completed');
    }

    public function showSettings()
    {
        $authUser   = auth()->guard('organizer')->user();
        $page_title = 'Settings';

        $offDays = VendorOffDay::where('organizer_id', $authUser->id)
            ->orderByRaw('CASE WHEN day_of_week IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('day_of_week')
            ->orderBy('off_date')
            ->get();

        return view('organizer.settings', compact('page_title', 'authUser', 'offDays'));
    }

    public function updateSettings(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();

        $request->validate([
            'fonnte_token'          => 'nullable|string|max:500',
            'reminder_quiet_start'  => 'required|integer|min:0|max:23',
            'reminder_quiet_end'    => 'required|integer|min:0|max:23',
        ]);

        $authUser->update([
            'fonnte_token'         => $request->input('fonnte_token'),
            'reminder_quiet_start' => $request->input('reminder_quiet_start', 0),
            'reminder_quiet_end'   => $request->input('reminder_quiet_end', 6),
        ]);

        return back()->with('success', 'Settings saved.');
    }

    public function storeOffDay(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();

        $request->validate([
            'type'        => 'required|in:weekly,specific',
            'day_of_week' => 'required_if:type,weekly|nullable|integer|min:0|max:6',
            'off_date'    => 'required_if:type,specific|nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
            'reason'      => 'nullable|string|max:255',
        ]);

        VendorOffDay::create([
            'organizer_id' => $authUser->id,
            'day_of_week'  => $request->type === 'weekly' ? $request->day_of_week : null,
            'off_date'     => $request->type === 'specific' ? $request->off_date : null,
            'start_time'   => $request->start_time ?: null,
            'end_time'     => $request->end_time ?: null,
            'reason'       => $request->reason ?: null,
        ]);

        return back()->with('success', 'Off day added.');
    }

    public function destroyOffDay($id)
    {
        $authUser = auth()->guard('organizer')->user();

        VendorOffDay::where('id', $id)
            ->where('organizer_id', $authUser->id)
            ->delete();

        return back()->with('success', 'Off day removed.');
    }

    // =========================================================
    // Time Slots CRUD
    // =========================================================

    public function showTimeSlots()
    {
        $authUser   = auth()->guard('organizer')->user();
        $page_title = 'Time Slots';

        $timeSlots = VendorTimeSlot::where('organizer_id', $authUser->id)
            ->with('images')
            ->orderBy('start_time')
            ->get();

        return view('organizer.time-slot.index', compact('page_title', 'authUser', 'timeSlots'));
    }

    public function uploadSlotImage(Request $request, $id)
    {
        $authUser = auth()->guard('organizer')->user();

        $slot = VendorTimeSlot::where('id', $id)
            ->where('organizer_id', $authUser->id)
            ->firstOrFail();

        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $file      = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename  = \Illuminate\Support\Str::uuid() . '.' . $extension;
        $folder    = "uploads/{$authUser->id}/slots/{$slot->id}";
        $file->storeAs($folder, $filename, 'public');

        $image = SlotImage::create([
            'slot_id'    => $slot->id,
            'url'        => $filename,
            'alt_text'   => $request->input('alt_text', ''),
            'is_cover'   => $slot->images()->count() === 0,
            'sort_order' => ($slot->images()->max('sort_order') ?? 0) + 1,
        ]);

        return response()->json([
            'success'  => true,
            'url'      => asset("storage/{$folder}/{$filename}"),
            'image_id' => $image->id,
            'is_cover' => $image->is_cover,
        ]);
    }

    public function destroySlotImage($slotId, $imageId)
    {
        $authUser = auth()->guard('organizer')->user();

        $slot = VendorTimeSlot::where('id', $slotId)
            ->where('organizer_id', $authUser->id)
            ->firstOrFail();

        $image = SlotImage::where('id', $imageId)
            ->where('slot_id', $slot->id)
            ->firstOrFail();

        // Delete the file
        $folder = "uploads/{$authUser->id}/slots/{$slot->id}";
        \Illuminate\Support\Facades\Storage::disk('public')->delete("{$folder}/{$image->url}");

        $wasCover = $image->is_cover;
        $image->delete();

        // Promote next image to cover if the deleted one was cover
        if ($wasCover) {
            $next = SlotImage::where('slot_id', $slot->id)->orderBy('sort_order')->first();
            if ($next) {
                $next->update(['is_cover' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function storeTimeSlot(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();

        $request->validate([
            'slot_name'         => 'required|string|max:100',
            'slot_price'        => 'nullable|numeric|min:0',
            'pax'               => 'nullable|integer|min:1',
            'is_full_day'       => 'nullable|boolean',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i',
            'duration_minutes'  => 'nullable|integer|min:1',
            'rest_minutes'      => 'nullable|integer|min:0',
            'is_multiple'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
        ]);

        VendorTimeSlot::create([
            'organizer_id'      => $authUser->id,
            'slot_name'         => $request->slot_name,
            'slot_price'        => $request->slot_price ?? 0,
            'pax'               => $request->pax,
            'is_full_day'       => $request->boolean('is_full_day'),
            'start_time'        => $request->boolean('is_full_day') ? null : $request->start_time,
            'end_time'          => $request->boolean('is_full_day') ? null : $request->end_time,
            'duration_minutes'  => $request->duration_minutes,
            'rest_minutes'      => $request->rest_minutes ?? 0,
            'is_multiple'       => $request->boolean('is_multiple'),
            'is_active'         => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Time slot created.');
    }

    public function updateTimeSlot(Request $request, $id)
    {
        $authUser = auth()->guard('organizer')->user();

        $slot = VendorTimeSlot::where('id', $id)
            ->where('organizer_id', $authUser->id)
            ->firstOrFail();

        $request->validate([
            'slot_name'         => 'required|string|max:100',
            'slot_price'        => 'nullable|numeric|min:0',
            'pax'               => 'nullable|integer|min:1',
            'is_full_day'       => 'nullable|boolean',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i',
            'duration_minutes'  => 'nullable|integer|min:1',
            'rest_minutes'      => 'nullable|integer|min:0',
            'is_multiple'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
        ]);

        $slot->update([
            'slot_name'         => $request->slot_name,
            'slot_price'        => $request->slot_price ?? 0,
            'pax'               => $request->pax,
            'is_full_day'       => $request->boolean('is_full_day'),
            'start_time'        => $request->boolean('is_full_day') ? null : $request->start_time,
            'end_time'          => $request->boolean('is_full_day') ? null : $request->end_time,
            'duration_minutes'  => $request->duration_minutes,
            'rest_minutes'      => $request->rest_minutes ?? 0,
            'is_multiple'       => $request->boolean('is_multiple'),
            'is_active'         => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Time slot updated.');
    }

    public function destroyTimeSlot($id)
    {
        $authUser = auth()->guard('organizer')->user();

        VendorTimeSlot::where('id', $id)
            ->where('organizer_id', $authUser->id)
            ->delete();

        return back()->with('success', 'Time slot deleted.');
    }
}