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
    /**
     * Handle login for given role.
     * 
     * @param Request $request
     * @param string $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, $role = null)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid username or password'
            ], 401);
        }

        // If role is specified, check user role matches
        if ($role) {
            // For superadmin role, your user.role must be 'superadmin'
            // Adjust role names if needed

            if ($role === 'admin' && $user->role !== 'superadmin') {
                return response()->json(['message' => 'Unauthorized: Superadmin only'], 403);
            }

            if ($role !== 'admin' && $user->role !== $role) {
                return response()->json(['message' => 'Unauthorized: ' . $role . ' only'], 403);
            }
        } else {
            // No role param means generic login - only allow participant
            if ($user->role !== 'participant') {
                return response()->json(['message' => 'Unauthorized: Participant only'], 403);
            }
        }

        // Create token
        $token = $user->createToken($role ? $role . '_auth_token' : 'auth_token')->plainTextToken;
        Auth::login($user);
        // Update last login timestamp
        $user->last_login = now();
        $user->save();

        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => [
        //         'id' => $user->id,
        //         'username' => $user->username,
        //         'role' => $user->role,
        //     ],
        // ]);
        // Redirect based on role
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard'); // define this route
        } elseif ($role === 'organizer') {
            return redirect()->route('organizer.dashboard');
        } elseif ($role === 'marshal') {
            return redirect()->route('marshal.dashboard');
        } else {
            return redirect()->route('participant.dashboard');
        }
    }
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
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login'); // redirect to login page after logout
    }
}
