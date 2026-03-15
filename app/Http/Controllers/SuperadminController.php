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
use App\Services\HealthCheckService;
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
        $activeStatuses    = Booking::ACTIVE_STATUSES;
        $collectedStatuses = ['paid', 'confirmed', 'completed'];

        $stats = [
            'organizers'      => Organizer::count(),
            'bookings'        => Booking::whereIn('status', $activeStatuses)->count(),
            'packages'        => Package::count(),
            'revenue'         => Booking::whereIn('status', $collectedStatuses)->sum('final_price'),
            'home_visits'         => VisitorAction::where('action', 'visit_page')->where('page', 'home')->whereDate('created_at', today())->count(),
            'profile_visits'      => VisitorAction::where('action', 'visit_page')->where('page', 'profile')->whereDate('created_at', today())->count(),
            'home_visits_all'     => VisitorAction::where('action', 'visit_page')->where('page', 'home')->count(),
            'profile_visits_all'  => VisitorAction::where('action', 'visit_page')->where('page', 'profile')->count(),
        ];

        $recentOrganizers = Organizer::latest()->take(5)->get();

        // Per-organizer chart — all organizers ranked by active bookings
        $orgStats = DB::table('organizers')
            ->leftJoin('bookings', function ($join) use ($collectedStatuses) {
                $join->on('organizers.id', '=', 'bookings.organizer_id')
                     ->whereIn('bookings.status', $collectedStatuses);
            })
            ->whereNull('organizers.deleted_at')
            ->select([
                'organizers.id',
                'organizers.name',
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw('SUM(COALESCE(bookings.final_price, 0)) as revenue'),
            ])
            ->groupBy('organizers.id', 'organizers.name')
            ->having('bookings_count', '>', 0)
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
        return response()->json(HealthCheckService::run());
    }

    // ── ORGANIZERS ───────────────────────────────────────────────────────────

    public function organizers(Request $request)
    {
        $activeStatuses    = Booking::ACTIVE_STATUSES;
        $collectedStatuses = ['paid', 'confirmed', 'completed'];

        $query = Organizer::withCount([
                'packages',
                'bookings',
                'bookings as active_bookings_count' => fn($q) => $q->whereIn('status', $activeStatuses),
                'bookings as today_bookings_count'  => fn($q) => $q->whereDate('created_at', today()),
            ])
            ->withSum(
                ['bookings as revenue' => fn($q) => $q->whereIn('status', $collectedStatuses)],
                'final_price'
            )
            ->with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $organizers = $query->orderByDesc('revenue')->orderByDesc('active_bookings_count')->paginate(20)->withQueryString();

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
            'auto_send_receipt'    => 'boolean',
            'reminder_quiet_start' => 'nullable|integer|min:0|max:23',
            'reminder_quiet_end'   => 'nullable|integer|min:0|max:23',
            'payment_qr'           => 'nullable|image|max:2048',
            'sp_img_hero'          => 'nullable|image|max:20480',
            'sp_img_gallery'       => 'nullable|image|max:20480',
            'sp_img_map'           => 'nullable|image|max:20480',
            'sp_img_venue_dewan'   => 'nullable|image|max:20480',
            'sp_img_venue_dataran' => 'nullable|image|max:20480',
            'sp_img_venue_laman'   => 'nullable|image|max:20480',
            'sp_img_wedding_hero'  => 'nullable|image|max:20480',
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
            'auto_send_receipt'    => $request->boolean('auto_send_receipt'),
            'reminder_quiet_start' => $request->reminder_quiet_start,
            'reminder_quiet_end'   => $request->reminder_quiet_end,
            'wallet_balance'       => $request->wallet_balance,
            'wallet_currency'      => $request->wallet_currency,
            'is_active'            => $request->boolean('is_active'),
            'visibility'           => $request->visibility,
            'what_flow'            => $request->what_flow,
        ]);

        if ($request->boolean('remove_payment_qr')) {
            if ($organizer->payment_qr_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($organizer->payment_qr_path);
            }
            $organizer->update(['payment_qr_path' => null]);
        } elseif ($request->hasFile('payment_qr')) {
            if ($organizer->payment_qr_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($organizer->payment_qr_path);
            }
            $file     = $request->file('payment_qr');
            $folder   = 'uploads/' . $organizer->id . '/qr';
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($folder, $filename, 'public');
            $organizer->update(['payment_qr_path' => $folder . '/' . $filename]);
        }

        // Special page image uploads
        $spSlots = ['hero', 'gallery', 'map', 'venue_dewan', 'venue_dataran', 'venue_laman', 'wedding_hero'];
        $spImages = $organizer->special_page_images ?? [];
        $folder   = 'uploads/' . $organizer->id . '/special_page';
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($folder);
        foreach ($spSlots as $slot) {
            $field = 'sp_img_' . $slot;
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                if (!empty($spImages[$slot])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($spImages[$slot]);
                }
                $file = $request->file($field);
                $filename = $slot . '.' . $file->getClientOriginalExtension();
                $file->storeAs($folder, $filename, 'public');
                $spImages[$slot] = $folder . '/' . $filename;
            }
        }
        $organizer->update(['special_page_images' => $spImages]);

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
            'tinymce_api_key'    => AppSetting::get('tinymce_api_key', ''),
            'resend_api_key'     => AppSetting::get('resend_api_key', ''),
            'health_check_from'  => AppSetting::get('health_check_from', 'onboarding@resend.dev'),
            'report_email'       => AppSetting::get('report_email', 'salessistemkami@gmail.com'),
        ];

        return view('superadmin.settings', compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'tinymce_api_key'    => 'nullable|string|max:255',
            'resend_api_key'     => 'nullable|string|max:255',
            'health_check_from'  => 'nullable|email|max:255',
            'report_email'       => 'nullable|email|max:255',
        ]);

        AppSetting::set('tinymce_api_key',    $request->input('tinymce_api_key'));
        AppSetting::set('resend_api_key',     $request->input('resend_api_key'));
        AppSetting::set('health_check_from',  $request->input('health_check_from'));
        AppSetting::set('report_email',       $request->input('report_email'));

        return back()->with('success', 'Settings saved.');
    }

    // ── UPLOAD IMAGE ─────────────────────────────────────────────────────────

    public function showUploadImage()
    {
        return view('superadmin.upload-image');
    }

    // ── REMINDERS ────────────────────────────────────────────────────────────

    public function showReminders()
    {
        $now    = \Carbon\Carbon::now();
        $cutoff = \Carbon\Carbon::now()->addHours(12);

        $pendingCount = \App\Models\Booking::whereNotIn('status', ['cancelled'])
            ->whereNull('reminder_sent_at')
            ->whereHas('vendorTimeSlots', function ($q) use ($now, $cutoff) {
                $q->whereRaw("CONCAT(booked_date_start, ' ', booked_time_start) BETWEEN ? AND ?", [
                    $now->toDateTimeString(),
                    $cutoff->toDateTimeString(),
                ]);
            })
            ->whereHas('organizer', function ($q) {
                $q->whereNotNull('fonnte_token')->where('fonnte_token', '!=', '');
            })
            ->count();

        return view('superadmin.reminders', compact('pendingCount'));
    }

    public function triggerReminders(Request $request)
    {
        \Illuminate\Support\Facades\Artisan::call('reminders:send');
        $output = \Illuminate\Support\Facades\Artisan::output();

        return back()->with('reminder_output', trim($output))->with('success', 'Reminder command executed.');
    }

    // Whitelist of runnable commands: key => [label, command, args, description, danger, background]
    private function commandList(): array
    {
        return [
            'reminders_send'        => ['label' => 'WhatsApp Reminders',           'command' => 'reminders:send',          'args' => [], 'description' => 'Send WhatsApp reminders to customers with bookings starting soon. Report email will also be sent.', 'danger' => false, 'background' => true],
            'daily_report'          => ['label' => 'Daily Booking Report',          'command' => 'bookings:daily-report',   'args' => [], 'description' => 'Generate and email the daily booking summary with per-organizer stats and visitor data.',              'danger' => false, 'background' => true],
            'health_report'         => ['label' => 'Health Check Report',           'command' => 'health:report',           'args' => ['--force'], 'description' => 'Run all health checks and email the report (--force always sends email).',                   'danger' => false, 'background' => true],
            'images_optimize'       => ['label' => 'Generate WebP (new only)',      'command' => 'images:optimize',         'args' => [], 'description' => 'Converts new uploaded images to WebP. Skips files that already have a .webp version.',               'danger' => false, 'background' => false],
            'images_optimize_force' => ['label' => 'Regenerate All WebP (force)',   'command' => 'images:optimize',         'args' => ['--force'], 'description' => 'Regenerates WebP for ALL uploaded images. Runs in background.',                                 'danger' => true,  'background' => true],
            'storage_link'          => ['label' => 'Storage Link',                  'command' => 'storage:link',            'args' => ['--force'], 'description' => 'Creates (or recreates) the public/storage symlink pointing to storage/app/public.',            'danger' => false],
            'config_cache'          => ['label' => 'Cache Config',                  'command' => 'config:cache',            'args' => [], 'description' => 'Compiles all config files into a single cached file for faster loading.',                               'danger' => false],
            'route_cache'           => ['label' => 'Cache Routes',                  'command' => 'route:cache',             'args' => [], 'description' => 'Compiles all routes into a single cached file for faster route matching.',                             'danger' => false],
            'view_cache'            => ['label' => 'Cache Views',                   'command' => 'view:cache',              'args' => [], 'description' => 'Pre-compiles all Blade templates.',                                                                      'danger' => false],
            'optimize_clear'        => ['label' => 'Clear All Caches',              'command' => 'optimize:clear',          'args' => [], 'description' => 'Clears config, route, view, and application caches. Use after code changes.',                          'danger' => false],
            'sitemap_generate'      => ['label' => 'Generate Sitemap',              'command' => 'sitemap:generate',        'args' => [], 'description' => 'Regenerates public/sitemap.xml with all public pages and organizer profiles.',                         'danger' => false],
        ];
    }

    public function showCommands()
    {
        $now    = Carbon::now();
        $cutoff = Carbon::now()->addHours(12);

        $pendingCount = Booking::whereNotIn('status', ['cancelled'])
            ->whereNull('reminder_sent_at')
            ->whereHas('vendorTimeSlots', function ($q) use ($now, $cutoff) {
                $q->whereRaw("CONCAT(booked_date_start, ' ', booked_time_start) BETWEEN ? AND ?", [
                    $now->toDateTimeString(),
                    $cutoff->toDateTimeString(),
                ]);
            })
            ->whereHas('organizer', function ($q) {
                $q->whereNotNull('fonnte_token')->where('fonnte_token', '!=', '');
            })
            ->count();

        return view('superadmin.commands', [
            'commands'     => $this->commandList(),
            'pendingCount' => $pendingCount,
        ]);
    }

    public function readCommandLog(string $key)
    {
        $commands = $this->commandList();
        if (!array_key_exists($key, $commands) || empty($commands[$key]['background'])) {
            abort(404);
        }
        $logFile = storage_path('logs/cmd-' . $key . '.log');
        $output  = file_exists($logFile) ? file_get_contents($logFile) : '(log file not found — command may not have run yet)';

        return back()
            ->with('cmd_output', trim($output) ?: '(empty log)')
            ->with('cmd_ran', $commands[$key]['label'] . ' — Log');
    }

    /**
     * AJAX endpoint: poll log file content for live streaming.
     */
    public function pollCommandLog(string $key)
    {
        $commands = $this->commandList();
        if (!array_key_exists($key, $commands) || empty($commands[$key]['background'])) {
            return response()->json(['error' => 'Invalid command'], 404);
        }

        $logFile  = storage_path('logs/cmd-' . $key . '.log');
        $pidFile  = storage_path('logs/cmd-' . $key . '.pid');
        $output   = file_exists($logFile) ? file_get_contents($logFile) : '';
        $running  = false;

        if (file_exists($pidFile)) {
            $pid = (int) trim(file_get_contents($pidFile));
            // Check if process is still running (POSIX signal 0 = test if alive)
            if ($pid > 0 && file_exists("/proc/{$pid}")) {
                $running = true;
            } elseif ($pid > 0) {
                // Try posix_kill as fallback (works on macOS too)
                $running = function_exists('posix_kill') ? posix_kill($pid, 0) : false;
            }
        }

        return response()->json([
            'output'  => trim($output) ?: '(waiting for output...)',
            'running' => $running,
        ]);
    }

    public function runCommand(Request $request)
    {
        $request->validate(['command_key' => 'required|string']);
        $commands = $this->commandList();
        $key      = $request->input('command_key');

        if (!array_key_exists($key, $commands)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Unknown command.'], 422);
            }
            return back()->with('error', 'Unknown command.');
        }

        $cmd = $commands[$key];

        // Background commands: fire-and-forget, write output to log file
        if (!empty($cmd['background'])) {
            $logFile = storage_path('logs/cmd-' . $key . '.log');
            $pidFile = storage_path('logs/cmd-' . $key . '.pid');
            $args    = implode(' ', $cmd['args']);
            $artisan = base_path('artisan');

            // Clear previous log
            file_put_contents($logFile, '');

            // Start in background and capture PID
            $shellCmd = "php {$artisan} {$cmd['command']} {$args} > " . escapeshellarg($logFile) . " 2>&1 & echo $!";
            $pid = trim(shell_exec($shellCmd));
            file_put_contents($pidFile, $pid);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'started',
                    'key'    => $key,
                    'label'  => $cmd['label'],
                    'pid'    => $pid,
                ]);
            }

            return back()
                ->with('cmd_output', "Started in background (PID: {$pid}).")
                ->with('cmd_ran', $cmd['label'])
                ->with('success', "Command '{$cmd['label']}' started in background.");
        }

        try {
            \Illuminate\Support\Facades\Artisan::call($cmd['command'], array_fill_keys($cmd['args'], true));
            $output = \Illuminate\Support\Facades\Artisan::output();
        } catch (\Throwable $e) {
            $output = 'ERROR: ' . $e->getMessage();
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'completed',
                'label'  => $cmd['label'],
                'output' => trim($output) ?: '(no output)',
            ]);
        }

        return back()
            ->with('cmd_output', trim($output) ?: '(no output)')
            ->with('cmd_ran', $cmd['label'])
            ->with('success', "Command '{$cmd['label']}' executed.");
    }
}
