<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{

    protected function getGuardConfig($role)
    {
        $map = [
            'organizer' => [
                'guard' => 'organizer',
                'model' => \App\Models\Organizer::class,
                'relation_field' => 'user_id',
            ],
            'worker' => [
                'guard' => 'worker',
                'model' => \App\Models\Worker::class,
                'relation_field' => 'user_id',
            ],
            // 'participant' => [
            //     'guard' => 'web', // assuming default
            //     'model' => \App\Models\User::class,
            //     'relation_field' => 'id',
            // ],
            // add more roles as needed
        ];

        return $map[$role] ?? null;
    }

    /**
     * Show the login page for admin.
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showLoginAdmin(Request $request)
    {
        $role = $request->route('role') ?? 'participant';
        return view('admin.auth.login', compact('role'));
    }

    public function showLoginOrganizer(Request $request)
    {
        if (auth('organizer')->check()) {
            return redirect()->route('organizer.dashboard');
        }
        // dd(auth('organizer')->user());
        $role = $request->route('role') ?? 'participant';
        return view('organizer.auth.login', compact('role'));
    }

    public function showRegisterOrganizer(Request $request)
    {
        if (auth('organizer')->check()) {
            return redirect()->route('organizer.dashboard');
        }
        // dd(auth('organizer')->user());
        $role = $request->route('role') ?? 'participant';
        return view('organizer.auth.register', compact('role'));
    }

    public function submitRegisterOrganizer(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:organizers',
            'username'      => 'required|string|max:100|unique:users',
            'email'         => 'required|email|unique:organizers,email',
            'phone'         => 'required|string|max:50',
            'business_type' => 'required|string',
            'service_type'  => 'nullable|string',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username'  => $request->username,
                'password'  => Hash::make($request->password),
                'role'      => 'organizer',
            ]);

            // Check if name is provided before slugging
            $slug = Str::slug($request->name ?? 'organizer-' . Str::random(5));

            Organizer::create([
                'name'            => $request->name,
                'email'           => $request->email,
                'phone'           => $request->phone,
                'user_id'         => $user->id,
                'slug'            => $slug,
                'is_active'       => 0,
                'type'            => $request->business_type,
                'category'        => $request->service_type,
                'visibility'      => 'private',
                'wallet_currency' => 'RM',
            ]);

            DB::commit();

            return redirect()->route('organizer.login')->with('success', 'Organizer registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log error and input
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'input' => $request->except(['password', 'password_confirmation']) // Avoid logging passwords
            ]);
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    public function showLoginOrganizerWorker(Request $request)
    {
        if (auth('worker')->check()) {
            return redirect()->route('organizer.tickets.confirmed');
        }
        // dd(auth('organizer')->user());
        $role = $request->route('role') ?? 'participant';
        return view('worker.auth.login', compact('role'));
    }

    public function login(Request $request, $role = null)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        \Log::info("Triggered login for role: {$role}");

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput()->with('error', 'Invalid username or password');
        }

        // Role match check
        if ($role) {
            if ($role === 'admin' && $user->role !== 'superadmin') {
                return back()->withInput()->with('error', 'Unauthorized (admin)');
            }

            if ($role !== 'admin' && $user->role !== $role) {
                return back()->withInput()->with('error', 'Unauthorized (role mismatch)');
            }
        } else {
            if ($user->role !== 'participant') {
                return back()->withInput()->with('error', 'Unauthorized (non-participant)');
            }
        }

        // Map role to model and guard
        $map = [
            'organizer' => [
                'model' => \App\Models\Organizer::class,
                'guard' => 'organizer',
            ],
            'worker' => [
                'model' => \App\Models\Worker::class,
                'guard' => 'worker',
            ],
            // 'participant' => [
            //     'model' => \App\Models\User::class,
            //     'guard' => 'web',
            // ],
            // 'admin' => [
            //     'model' => \App\Models\User::class,
            //     'guard' => 'web',
            // ],
            // 'marshal' => [
            //     'model' => \App\Models\Marshal::class,
            //     'guard' => 'marshal',
            // ],
        ];

        $guard = $map[$role]['guard'] ?? 'web';
        $modelClass = $map[$role]['model'] ?? \App\Models\User::class;

        // Use user_id unless it's the base User model
        $authUser = $modelClass === \App\Models\User::class
            ? $user
            : $modelClass::where('user_id', $user->id)->first();

        if (!$authUser) {
            return back()->withInput()->with('error', 'Related role model not found');
        }

        Auth::guard($guard)->login($authUser);

        // Save last login time
        $user->last_login = now();
        $user->save();

        // Redirect
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'organizer' => redirect()->route('organizer.dashboard'),
            'marshal' => redirect()->route('marshal.dashboard'),
            'worker' => redirect()->route('worker.tickets.confirmed'),
            default => redirect()->route('participant.dashboard'),
        };
    }

    /**
     * Handle login for given role.
     * 
     * @param Request $request
     * @param string $role
     * @return \Illuminate\Http\JsonResponse
     */



    public function register(Request $request)
    {

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:organizer,participant,marshall',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $guards = ['web', 'admin', 'organizer', 'worker', 'marshal']; // list your active guards
        $activeGuard = null;
        $user = null;

        // Detect which guard is currently authenticated
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $activeGuard = $guard;
                $user = auth()->guard($guard)->user();
                break;
            }
        }

        // Default redirect
        $redirectUrl = '/login';

        if ($user) {
            // If user is a related model like Worker or Organizer, load the base User model
            if (method_exists($user, 'user') && $user->relationLoaded('user') === false) {
                $user->load('user');
            }

            $baseUser = $user->user ?? $user; // either related user or self

            $role = $baseUser->role ?? null;

            $redirectMap = [
                'admin' => '/admin/login',
                'organizer' => '/organizer/login',
                'marshal' => '/marshal/login',
                'worker' => '/worker/login',
                'participant' => '/login',
            ];

            $redirectUrl = $redirectMap[$role] ?? '/login';

            Auth::guard($activeGuard)->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectUrl);
    }

}
