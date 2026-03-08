<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Organizer;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use App\Models\VisitorAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuperadminController extends Controller
{
    // ── AUTH ─────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (auth('superadmin')->check()) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
                    ->where('role', 'superadmin')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
        }

        auth('superadmin')->login($user, $request->boolean('remember'));
        $user->update(['last_login' => now()]);

        return redirect()->route('superadmin.dashboard');
    }

    public function logout()
    {
        auth('superadmin')->logout();
        return redirect()->route('superadmin.login');
    }

    // ── DASHBOARD ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'organizers'      => Organizer::count(),
            'bookings'        => Booking::count(),
            'packages'        => Package::count(),
            'revenue'         => Booking::whereIn('status', Booking::REVENUE_STATUSES)->sum('final_price'),
            'home_visits'         => VisitorAction::where('action', 'visit_page')->where('page', 'home')->whereDate('created_at', today())->count(),
            'profile_visits'      => VisitorAction::where('action', 'visit_page')->where('page', 'profile')->whereDate('created_at', today())->count(),
            'home_visits_all'     => VisitorAction::where('action', 'visit_page')->where('page', 'home')->count(),
            'profile_visits_all'  => VisitorAction::where('action', 'visit_page')->where('page', 'profile')->count(),
        ];

        $recentOrganizers = Organizer::latest()->take(5)->get();

        // Per-organizer chart — active organizers ranked by bookings
        $orgStats = DB::table('organizers')
            ->leftJoin('bookings', 'organizers.id', '=', 'bookings.organizer_id')
            ->where('organizers.is_active', true)
            ->whereNull('organizers.deleted_at')
            ->select([
                'organizers.id',
                'organizers.name',
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw("SUM(CASE WHEN bookings.status IN ('paid','confirmed','completed','pending') THEN bookings.final_price ELSE 0 END) as revenue"),
            ])
            ->groupBy('organizers.id', 'organizers.name')
            ->orderByDesc('bookings_count')
            ->get();

        $chartLabels   = $orgStats->pluck('name');
        $chartBookings = $orgStats->pluck('bookings_count');
        $chartRevenue  = $orgStats->pluck('revenue')->map(fn($v) => round((float)$v, 2));

        return view('superadmin.dashboard', compact(
            'stats', 'recentOrganizers',
            'chartLabels', 'chartBookings', 'chartRevenue'
        ));
    }

    // ── HEALTH CHECK ─────────────────────────────────────────────────────────

    public function healthCheck()
    {
        $results = [];

        // Helper to record a check
        $check = function (string $name, callable $fn) use (&$results) {
            $start = microtime(true);
            try {
                $detail = $fn();
                $results[] = [
                    'name'   => $name,
                    'status' => 'pass',
                    'detail' => $detail,
                    'ms'     => round((microtime(true) - $start) * 1000),
                ];
            } catch (\Throwable $e) {
                $results[] = [
                    'name'   => $name,
                    'status' => 'fail',
                    'detail' => $e->getMessage(),
                    'ms'     => round((microtime(true) - $start) * 1000),
                ];
            }
        };

        // 1. Database connection
        $check('Database Connection', function () {
            DB::connection()->getPdo();
            return 'Connected to ' . DB::connection()->getDatabaseName();
        });

        // 2. Key tables accessible
        $check('Core Tables', function () {
            $counts = [
                'organizers' => DB::table('organizers')->count(),
                'bookings'   => DB::table('bookings')->count(),
                'packages'   => DB::table('packages')->count(),
                'users'      => DB::table('users')->count(),
            ];
            return implode(', ', array_map(fn($t, $c) => "$t: $c", array_keys($counts), $counts));
        });

        // 3. Storage writable
        $check('Storage Write', function () {
            $path = 'health_check_' . now()->timestamp . '.tmp';
            Storage::put($path, 'ok');
            Storage::delete($path);
            return 'Disk writable';
        });

        // 4. Storage symlink (public/storage accessible)
        $check('Storage Symlink', function () {
            $link = public_path('storage');
            if (!file_exists($link)) {
                throw new \Exception('public/storage symlink missing — run php artisan storage:link');
            }
            return 'Symlink exists';
        });

        // 5. APP_KEY set
        $check('App Key', function () {
            $key = config('app.key');
            if (empty($key)) {
                throw new \Exception('APP_KEY not set');
            }
            return 'Set (' . strlen(base64_decode(str_replace('base64:', '', $key))) * 8 . '-bit)';
        });

        // 6. Cache read/write
        $check('Cache Read/Write', function () {
            $key = 'health_check_' . now()->timestamp;
            cache()->put($key, 'ok', 5);
            $val = cache()->get($key);
            cache()->forget($key);
            if ($val !== 'ok') throw new \Exception('Cache value mismatch');
            return 'Cache driver: ' . config('cache.default');
        });

        // 7. Key routes registered
        $check('Key Routes Registered', function () {
            $routes = ['index', 'search', 'superadmin.login', 'superadmin.dashboard'];
            $missing = [];
            foreach ($routes as $name) {
                if (!\Illuminate\Support\Facades\Route::has($name)) {
                    $missing[] = $name;
                }
            }
            if (!empty($missing)) {
                throw new \Exception('Missing routes: ' . implode(', ', $missing));
            }
            return implode(', ', $routes);
        });

        // 8. Key views exist
        $check('Key Views Exist', function () {
            $views = ['home.index', 'home.homeLayout', 'superadmin.dashboard', 'superadmin.login'];
            $missing = [];
            foreach ($views as $v) {
                if (!\Illuminate\Support\Facades\View::exists($v)) {
                    $missing[] = $v;
                }
            }
            if (!empty($missing)) {
                throw new \Exception('Missing views: ' . implode(', ', $missing));
            }
            return count($views) . ' views found';
        });

        // 9. Active organizers with packages
        $check('Active Organizers', function () {
            $active   = \App\Models\Organizer::where('is_active', true)->count();
            $inactive = \App\Models\Organizer::where('is_active', false)->count();
            if ($active === 0) throw new \Exception('No active organizers found');
            return "Active: $active, Inactive: $inactive";
        });

        // 10. Latest booking readable
        $check('Latest Booking', function () {
            $booking = \App\Models\Booking::latest()->first();
            if (!$booking) return 'No bookings yet';
            return "Latest: #{$booking->booking_code} ({$booking->status})";
        });

        $passed = count(array_filter($results, fn($r) => $r['status'] === 'pass'));
        $total  = count($results);

        return response()->json([
            'summary' => "$passed / $total checks passed",
            'passed'  => $passed,
            'total'   => $total,
            'results' => $results,
            'ran_at'  => now()->format('d M Y, H:i:s'),
        ]);
    }

    // ── ORGANIZERS ───────────────────────────────────────────────────────────

    public function organizers(Request $request)
    {
        $revenueStatuses = Booking::REVENUE_STATUSES;
        $query = Organizer::withCount(['packages', 'bookings'])
            ->withSum(['bookings as revenue' => fn($q) => $q->whereIn('status', $revenueStatuses)], 'final_price')
            ->with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $organizers = $query->orderBy('is_active', 'desc')->oldest()->paginate(20)->withQueryString();

        return view('superadmin.organizers', compact('organizers'));
    }

    public function createOrganizer()
    {
        return view('superadmin.organizer-create');
    }

    public function storeOrganizer(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:30',
            'city'     => 'nullable|string|max:100',
            'state'    => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'organizer',
        ]);

        $slug = Str::slug($request->name);
        $baseSlug = $slug;
        $i = 1;
        while (Organizer::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        Organizer::create([
            'name'      => $request->name,
            'slug'      => $slug,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'city'      => $request->city,
            'state'     => $request->state,
            'is_active' => $request->boolean('is_active', true),
            'user_id'   => $user->id,
        ]);

        return redirect()->route('superadmin.organizers')->with('success', 'Organizer created successfully.');
    }

    public function editOrganizer($id)
    {
        $organizer = Organizer::with('user')->findOrFail($id);
        return view('superadmin.organizer-edit', compact('organizer'));
    }

    public function updateOrganizer(Request $request, $id)
    {
        $organizer = Organizer::with('user')->findOrFail($id);

        $request->validate([
            'name'                 => 'required|string|max:255',
            'slug'                 => 'required|string|max:255|unique:organizers,slug,' . $id,
            'username'             => 'required|string|max:100|unique:users,username,' . ($organizer->user_id ?? 'NULL'),
            'password'             => 'nullable|string|min:6',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'nullable|string|max:30',
            'website'              => 'nullable|string|max:255',
            'type'                 => 'nullable|string|max:100',
            'category'             => 'nullable|string|max:100',
            'description'          => 'nullable|string',
            'office_name'          => 'nullable|string|max:255',
            'address_line1'        => 'nullable|string|max:255',
            'address_line2'        => 'nullable|string|max:255',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'postal_code'          => 'nullable|string|max:20',
            'country'              => 'nullable|string|max:100',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'is_gmaps_verified'    => 'boolean',
            'google_map_show'      => 'boolean',
            'fonnte_token'         => 'nullable|string|max:255',
            'reminder_quiet_start' => 'nullable|date_format:H:i',
            'reminder_quiet_end'   => 'nullable|date_format:H:i',
            'wallet_balance'       => 'nullable|numeric|min:0',
            'wallet_currency'      => 'nullable|string|max:10',
            'is_active'            => 'boolean',
            'visibility'           => 'nullable|in:public,private',
            'what_flow'            => 'nullable|integer|in:1,2,3,4',
        ]);

        $organizer->update([
            'name'                 => $request->name,
            'slug'                 => $request->slug,
            'email'                => $request->email,
            'phone'                => $request->phone,
            'website'              => $request->website,
            'type'                 => $request->type,
            'category'             => $request->category,
            'description'          => $request->description,
            'office_name'          => $request->office_name,
            'address_line1'        => $request->address_line1,
            'address_line2'        => $request->address_line2,
            'city'                 => $request->city,
            'state'                => $request->state,
            'postal_code'          => $request->postal_code,
            'country'              => $request->country,
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
            'is_gmaps_verified'    => $request->boolean('is_gmaps_verified'),
            'google_map_show'      => $request->boolean('google_map_show'),
            'fonnte_token'         => $request->fonnte_token,
            'reminder_quiet_start' => $request->reminder_quiet_start,
            'reminder_quiet_end'   => $request->reminder_quiet_end,
            'wallet_balance'       => $request->wallet_balance,
            'wallet_currency'      => $request->wallet_currency,
            'is_active'            => $request->boolean('is_active'),
            'visibility'           => $request->visibility,
            'what_flow'            => $request->what_flow,
        ]);

        if ($organizer->user) {
            $userUpdate = ['username' => $request->username];
            if ($request->filled('password')) {
                $userUpdate['password'] = Hash::make($request->password);
            }
            $organizer->user->update($userUpdate);
        }

        return redirect()->route('superadmin.organizer.detail', $id)->with('success', 'Organizer updated.');
    }

    public function destroyOrganizer($id)
    {
        $organizer = Organizer::findOrFail($id);
        $organizer->delete(); // soft delete (SoftDeletes trait)

        return redirect()->route('superadmin.organizers')->with('success', 'Organizer archived (soft deleted).');
    }

    public function organizerDetail($id)
    {
        $organizer = Organizer::with(['packages', 'user'])->findOrFail($id);

        $bookingStats = [
            'total'     => Booking::where('organizer_id', $id)->count(),
            'confirmed' => Booking::where('organizer_id', $id)->where('status', 'confirmed')->count(),
            'pending'   => Booking::where('organizer_id', $id)->where('status', 'pending')->count(),
            'revenue'   => Booking::where('organizer_id', $id)
                                  ->whereIn('status', Booking::REVENUE_STATUSES)
                                  ->sum('final_price'),
        ];

        $recentBookings = Booking::where('organizer_id', $id)
            ->with('participant', 'package')
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.organizer-detail', compact('organizer', 'bookingStats', 'recentBookings'));
    }

    // ── IMPERSONATE ──────────────────────────────────────────────────────────

    public function impersonate($id)
    {
        $organizer = Organizer::findOrFail($id);

        session(['superadmin_impersonating' => auth('superadmin')->id()]);
        auth('organizer')->login($organizer);

        return redirect()->route('organizer.dashboard')
            ->with('info', 'Logged in as ' . $organizer->name);
    }

    public function stopImpersonate()
    {
        auth('organizer')->logout();
        session()->forget('superadmin_impersonating');

        return redirect()->route('superadmin.organizers')
            ->with('success', 'Returned to superadmin.');
    }

    // ── SETTINGS ─────────────────────────────────────────────────────────────

    public function showSettings()
    {
        $settings = [
            'tinymce_api_key' => AppSetting::get('tinymce_api_key', ''),
        ];

        return view('superadmin.settings', compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'tinymce_api_key' => 'nullable|string|max:255',
        ]);

        AppSetting::set('tinymce_api_key', $request->input('tinymce_api_key'));

        return back()->with('success', 'Settings saved.');
    }

    // ── UPLOAD IMAGE ─────────────────────────────────────────────────────────

    public function showUploadImage()
    {
        return view('superadmin.upload-image');
    }
}
