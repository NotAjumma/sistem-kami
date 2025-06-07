<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
