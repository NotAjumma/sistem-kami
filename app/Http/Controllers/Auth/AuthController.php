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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users',
            'email' => 'required|email|unique:organizers,email',
            'phone' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'organizer',
            ]);

            // Check if name is provided before slugging
            $slug = Str::slug($request->name ?? 'organizer-' . Str::random(5));

            Organizer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
                'slug' => $slug,
                'is_active' => 0,
                'visibility' => 'public',
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

    public function login(Request $request, $role = null)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid username or password');
        }
        // Role check
        if ($role) {
            if ($role === 'admin' && $user->role !== 'superadmin') {
                return redirect()->back()->withInput()->with('error', 'Invalid username or password');
            }

            if ($role !== 'admin' && $user->role !== $role) {
                return redirect()->back()->withInput()->with('error', 'Invalid username or password');
            }
        } else {
            if ($user->role !== 'participant') {
                return redirect()->back()->withInput()->with('error', 'Invalid username or password');
            }
        }

        $organizer = \App\Models\Organizer::where('user_id', $user->id)->first();

        if (!$organizer) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password');
        }

        Auth::guard('organizer')->login($organizer);

        $user->last_login = now();
        $user->save();

        // Redirect based on role
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'organizer') {
            return redirect()->route('organizer.dashboard');
        } elseif ($role === 'marshal') {
            return redirect()->route('marshal.dashboard');
        } else {
            return redirect()->route('participant.dashboard');
        }
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
        $user = auth()->guard('organizer')->user()->load('user');
        $redirectUrl = '/login';

        if ($user) {
            switch ($user->user->role) {
                case 'admin':
                    $redirectUrl = '/admin/login';
                    break;
                case 'organizer':
                    $redirectUrl = '/organizer/login';
                    break;
                case 'marshal':
                    $redirectUrl = '/marshal/login';
                    break;
                case 'user':
                    $redirectUrl = '/login';
                    break;
                // Add other roles as needed
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectUrl);
    }

}
