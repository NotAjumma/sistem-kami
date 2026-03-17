<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Organizer;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class HealthCheckService
{
    public static function run(): array
    {
        $results = [];

        $check = function (string $group, string $name, callable $fn) use (&$results) {
            $start = microtime(true);
            try {
                $detail    = $fn();
                $results[] = ['group' => $group, 'name' => $name, 'status' => 'pass', 'detail' => $detail, 'ms' => round((microtime(true) - $start) * 1000)];
            } catch (\Throwable $e) {
                $results[] = ['group' => $group, 'name' => $name, 'status' => 'fail', 'detail' => $e->getMessage(), 'ms' => round((microtime(true) - $start) * 1000)];
            }
        };

        // ── A. INFRASTRUCTURE ────────────────────────────────────────────────

        $check('Infrastructure', 'Database Connection', function () {
            DB::connection()->getPdo();
            return 'Connected to ' . DB::connection()->getDatabaseName();
        });

        $check('Infrastructure', 'Core Tables', function () {
            $tables  = ['organizers', 'bookings', 'packages', 'users', 'vendor_time_slots', 'vendor_off_days', 'booking_form_fields'];
            $missing = array_filter($tables, fn($t) => !DB::getSchemaBuilder()->hasTable($t));
            if (!empty($missing)) throw new \Exception('Missing tables: ' . implode(', ', $missing));
            return count($tables) . ' tables verified';
        });

        $check('Infrastructure', 'Storage Write', function () {
            $path = 'health_check_' . now()->timestamp . '.tmp';
            Storage::put($path, 'ok');
            Storage::delete($path);
            return 'Disk writable';
        });

        $check('Infrastructure', 'Storage Symlink', function () {
            if (!file_exists(public_path('storage'))) {
                throw new \Exception('public/storage symlink missing — run php artisan storage:link');
            }
            return 'Symlink exists';
        });

        $check('Infrastructure', 'App Key & Cache', function () {
            if (empty(config('app.key'))) throw new \Exception('APP_KEY not set');
            $k = 'hc_' . now()->timestamp;
            cache()->put($k, 'ok', 5);
            if (cache()->get($k) !== 'ok') throw new \Exception('Cache write/read failed');
            cache()->forget($k);
            return 'App key set, cache driver: ' . config('cache.default');
        });

        // ── B. ORGANIZER PROFILE PAGE ────────────────────────────────────────

        $check('Profile Page', 'Active Organizers', function () {
            $active = Organizer::where('is_active', true)->count();
            if ($active === 0) throw new \Exception('No active organizers — profile pages will be blank');
            $noSlug = Organizer::where('is_active', true)->where(fn($q) => $q->whereNull('slug')->orWhere('slug', ''))->count();
            if ($noSlug > 0) throw new \Exception("$noSlug active organizer(s) have no slug — profile URL broken");
            return "$active active organizers, all have slugs";
        });

        $check('Profile Page', 'Active Packages (slug & price)', function () {
            $total  = Package::where('status', 'active')->count();
            if ($total === 0) throw new \Exception('No active packages — booking buttons will not appear on profile');
            $noSlug = Package::where('status', 'active')->where(fn($q) => $q->whereNull('slug')->orWhere('slug', ''))->count();
            // flow=2 (FLOW_SLOT_AS_PACKAGE): price lives on vendor_time_slots.slot_price — exclude from price check
            $noPrice = Package::where('status', 'active')->where('final_price', '<=', 0)
                ->whereHas('organizer', fn($q) => $q->where('what_flow', '!=', 2))->count();
            $flow2   = Package::where('status', 'active')->where('final_price', '<=', 0)
                ->whereHas('organizer', fn($q) => $q->where('what_flow', 2))->count();
            $issues = [];
            if ($noSlug  > 0) $issues[] = "$noSlug missing slug (package URL broken)";
            if ($noPrice > 0) $issues[] = "$noPrice with price ≤ 0 (Book Now will show RM 0)";
            if (!empty($issues)) throw new \Exception(implode('; ', $issues));
            $note = $flow2 > 0 ? " ($flow2 use slot price via flow-2 — OK)" : '';
            return "$total active packages — slugs & prices OK$note";
        });

        $check('Profile Page', 'Package Images', function () {
            $withImages = Package::where('status', 'active')->whereHas('images')->count();
            $total      = Package::where('status', 'active')->count();
            if ($total === 0) return 'No active packages';
            if ($withImages === 0) throw new \Exception("0 / $total active packages have images — profile gallery empty");
            return "$withImages / $total packages have images";
        });

        $check('Profile Page', 'Auto WhatsApp (Fonnte Token)', function () {
            $total        = Organizer::where('is_active', true)->count();
            $withToken    = Organizer::where('is_active', true)->whereNotNull('fonnte_token')->where('fonnte_token', '!=', '')->count();
            $withoutToken = $total - $withToken;
            $systemToken  = \App\Models\AppSetting::get('fonnte_token');
            if ($withToken === 0 && !$systemToken) throw new \Exception("No Fonnte token set (organizer or system) — auto reminders & receipts will not work");
            if ($withoutToken > 0 && $systemToken) return "$withToken / $total have own token; $withoutToken will use system token (OK)";
            if ($withoutToken > 0 && !$systemToken) return "$withToken / $total have token; $withoutToken have no token (no system fallback — reminders & receipts won't send for them)";
            return "All $total active organizers have fonnte_token";
        });

        // ── C. DATE / TIME / SLOTS ───────────────────────────────────────────

        $check('Date / Time / Slots', 'Time Slots Exist', function () {
            $total  = DB::table('vendor_time_slots')->count();
            $active = DB::table('vendor_time_slots')->where('is_active', true)->count();
            if ($total === 0) throw new \Exception('No time slots found — slot picker on profile will be empty');
            if ($active === 0) throw new \Exception("$total slots exist but all inactive — slot picker will be empty");
            return "$active active slots (out of $total total)";
        });

        $check('Date / Time / Slots', 'Slot Time Validity', function () {
            $badTimes = DB::table('vendor_time_slots')->where('is_active', true)->where('is_full_day', false)
                ->where(fn($q) => $q->whereNull('start_time')->orWhereNull('end_time'))->count();
            if ($badTimes > 0) throw new \Exception("$badTimes active slot(s) missing start_time or end_time");
            $total = DB::table('vendor_time_slots')->where('is_active', true)->where('is_full_day', false)->count();
            return "$total timed slots all have valid start_time & end_time";
        });

        $check('Date / Time / Slots', 'Off-Days Check', function () {
            $upcoming = DB::table('vendor_off_days')->where('off_date', '>=', now()->toDateString())->count();
            $past     = DB::table('vendor_off_days')->where('off_date', '<', now()->toDateString())->count();
            return "Upcoming off-days: $upcoming, Past off-days: $past";
        });

        $check('Date / Time / Slots', 'Organizers with Slots Configured', function () {
            $active    = Organizer::where('is_active', true)->count();
            $withSlots = Organizer::where('is_active', true)->whereHas('timeSlots', fn($q) => $q->where('is_active', true))->count();
            $noSlots   = $active - $withSlots;
            if ($withSlots === 0) throw new \Exception("0 / $active active organizers have active slots — slot picker empty");
            if ($noSlots > 0) return "$withSlots / $active have active slots; $noSlots have no slots (may use FLOW_NO_SLOT)";
            return "All $active active organizers have at least 1 active slot";
        });

        // ── D. BOOKING FLOW ──────────────────────────────────────────────────

        $check('Booking Flow', 'Booking Form Fields', function () {
            $pkgsWithFields = Package::where('status', 'active')->whereHas('formFields')->count();
            $total          = Package::where('status', 'active')->count();
            if ($total === 0) return 'No active packages';
            return "$pkgsWithFields / $total active packages have custom form fields";
        });

        $check('Booking Flow', 'Bookings Load with Relations', function () {
            $booking = Booking::with(['organizer', 'package'])->latest()->first();
            if (!$booking) return 'No bookings yet — skipped';
            $issues = [];
            if (!$booking->organizer) $issues[] = 'organizer relation null (booking list may error)';
            if ($booking->package_id && !$booking->package) $issues[] = 'package relation null (booking detail may error)';
            if (!empty($issues)) throw new \Exception(implode('; ', $issues));
            return "Latest #{$booking->booking_code} — organizer & package relations OK";
        });

        $check('Booking Flow', 'Calendar Data (Slot Bookings)', function () {
            $total    = DB::table('bookings_vendor_time_slot')->count();
            $upcoming = DB::table('bookings_vendor_time_slot')->where('booked_date_start', '>=', now()->toDateString())->count();
            return "Total: $total, Upcoming: $upcoming";
        });

        $check('Booking Flow', 'Recent Bookings Readable', function () {
            $counts  = [];
            foreach (Booking::ACTIVE_STATUSES as $s) {
                $counts[$s] = Booking::where('status', $s)->count();
            }
            $summary = implode(', ', array_map(fn($s, $c) => "$s: $c", array_keys($counts), $counts));
            $latest  = Booking::latest()->value('booking_code') ?? 'none';
            return "Latest: #$latest | $summary";
        });

        // ── E. FORMS & BUTTONS ───────────────────────────────────────────────

        $check('Forms & Buttons', 'Booking List query', function () {
            $bookings = Booking::with(['vendorTimeSlots', 'participant', 'package:id,name', 'promoter:id,name'])
                ->latest()->limit(5)->get();
            $bad = $bookings->filter(fn($b) => $b->participant === null)->count();
            if ($bad > 0) throw new \Exception("$bad / {$bookings->count()} recent bookings have NULL participant");
            return "Last {$bookings->count()} bookings — all relations OK";
        });

        $check('Forms & Buttons', 'Booking Detail / Edit query', function () {
            $booking = Booking::with(['participant', 'vendorTimeSlots', 'bookingAddons', 'package', 'organizer'])->latest()->first();
            if (!$booking) return 'No bookings yet — skipped';
            $issues = [];
            if (!$booking->participant) $issues[] = 'participant null (edit form will crash)';
            if ($booking->package_id && !$booking->package) $issues[] = 'package null (detail page will crash)';
            if (!empty($issues)) throw new \Exception(implode('; ', $issues));
            return "#{$booking->booking_code} detail/edit relations all loaded";
        });

        $check('Forms & Buttons', 'Create Booking: packages per organizer', function () {
            $withPkgs = Organizer::where('is_active', true)->whereHas('packages', fn($q) => $q->where('status', 'active'))->count();
            $total    = Organizer::where('is_active', true)->count();
            if ($withPkgs === 0) throw new \Exception("No active organizers have active packages — dropdown will be empty");
            if ($withPkgs < $total) return "$withPkgs / $total organizers have active packages";
            return "All $total active organizers have at least 1 active package";
        });

        $check('Forms & Buttons', 'Calendar: slot-booking FK integrity', function () {
            $broken = DB::table('bookings_vendor_time_slot as bvt')
                ->leftJoin('vendor_time_slots as vts', 'bvt.vendor_time_slot_id', '=', 'vts.id')
                ->whereNull('vts.id')->count();
            if ($broken > 0) throw new \Exception("$broken slot-booking row(s) point to deleted slots");
            $total = DB::table('bookings_vendor_time_slot')->count();
            return "$total slot bookings, no broken FK references";
        });

        $check('Forms & Buttons', 'Book Now: date availability (next 14 days)', function () {
            $today       = now()->toDateString();
            $end         = now()->addDays(14)->toDateString();
            $offDates    = DB::table('vendor_off_days')->whereBetween('off_date', [$today, $end])->pluck('off_date')->toArray();
            $blockedDays = count(array_unique($offDates));
            $openDays    = 14 - $blockedDays;
            if ($openDays <= 0) throw new \Exception("All next 14 days are off-days — slot picker will show no availability");
            return "$openDays / 14 days open in next 14 days ($blockedDays off-days set)";
        });

        $check('Forms & Buttons', 'WhatsApp: organizer phone set', function () {
            $systemToken  = \App\Models\AppSetting::get('fonnte_token');
            // If system token exists, all organizers can send — check all have phone numbers
            if ($systemToken) {
                $active  = Organizer::where('is_active', true)->get();
                if ($active->isEmpty()) return 'No active organizers — skipped';
                $noPhone = $active->filter(fn($o) => empty($o->phone))->count();
                if ($noPhone > 0) throw new \Exception("$noPhone active organizer(s) have no phone number — WhatsApp link in messages will be missing");
                return "{$active->count()} active organizer(s) all have phone numbers (system Fonnte token set)";
            }
            $withToken = Organizer::where('is_active', true)->whereNotNull('fonnte_token')->where('fonnte_token', '!=', '')->get();
            if ($withToken->isEmpty()) return 'No organizers with fonnte_token — skipped';
            $noPhone = $withToken->filter(fn($o) => empty($o->phone))->count();
            if ($noPhone > 0) throw new \Exception("$noPhone organizer(s) have fonnte_token but no phone number");
            return "{$withToken->count()} organizer(s) with token all have phone numbers";
        });

        $check('Forms & Buttons', 'Send Receipt: organizer email set', function () {
            $total   = Organizer::where('is_active', true)->count();
            $noEmail = Organizer::where('is_active', true)->where(fn($q) => $q->whereNull('email')->orWhere('email', ''))->count();
            if ($noEmail > 0) throw new \Exception("$noEmail / $total active organizer(s) have no email — send receipt will fail");
            return "All $total active organizers have email set";
        });

        // ── F. PAGE VIEWS ────────────────────────────────────────────────────

        $check('Page Views', 'Dashboard View', function () {
            if (!View::exists('organizer.index')) throw new \Exception('organizer/index.blade.php missing');
            return 'organizer/index.blade.php found';
        });

        $check('Page Views', 'Calendar View', function () {
            if (!View::exists('organizer.booking.calender')) throw new \Exception('organizer/booking/calender.blade.php missing');
            return 'organizer/booking/calender.blade.php found';
        });

        $check('Page Views', 'Booking List & Detail Views', function () {
            $views   = ['organizer.booking.index', 'organizer.booking.show', 'organizer.booking.edit', 'organizer.booking.create', 'organizer.booking.ticket_confirmed'];
            $missing = array_filter($views, fn($v) => !View::exists($v));
            if (!empty($missing)) throw new \Exception('Missing: ' . implode(', ', array_values($missing)));
            return count($views) . ' booking views found';
        });

        $check('Page Views', 'Package Management Views', function () {
            $views   = ['organizer.package.index', 'organizer.package.create', 'organizer.package.edit'];
            $missing = array_filter($views, fn($v) => !View::exists($v));
            if (!empty($missing)) throw new \Exception('Missing: ' . implode(', ', array_values($missing)));
            return count($views) . ' package views found';
        });

        $check('Page Views', 'Settings & Time Slot Views', function () {
            $views   = ['organizer.settings', 'organizer.time-slot.index'];
            $missing = array_filter($views, fn($v) => !View::exists($v));
            if (!empty($missing)) throw new \Exception('Missing: ' . implode(', ', array_values($missing)));
            return count($views) . ' views found';
        });

        $check('Page Views', 'Public Profile & Booking Views', function () {
            $views   = ['home.business.profile', 'home.business.package.index', 'home.business.package.booking', 'home.business.checkoutPackage'];
            $missing = array_filter($views, fn($v) => !View::exists($v));
            if (!empty($missing)) throw new \Exception('Missing: ' . implode(', ', array_values($missing)));
            return count($views) . ' public profile views found';
        });

        // ─────────────────────────────────────────────────────────────────────

        $passed = count(array_filter($results, fn($r) => $r['status'] === 'pass'));
        $total  = count($results);

        return [
            'summary' => "$passed / $total checks passed",
            'passed'  => $passed,
            'total'   => $total,
            'results' => $results,
            'ran_at'  => now()->format('d M Y, H:i:s'),
        ];
    }
}
