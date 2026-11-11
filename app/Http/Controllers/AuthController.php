<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Find user by phone OR email
        $user = User::where('phone', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'login' => 'Invalid mobile number/email or account does not exist.',
            ]);
        }

        // Check password
        if (!$user->password || !Hash::check($password, $user->password)) {
            return back()->withInput()->withErrors([
                'login' => 'Incorrect password entered.',
            ]);
        }

        // Check if Deactivated / Offboarded
        if ($user->isOffboarded()) {
            $exitDate = $user->exit_date ? $user->exit_date->format('Y-m-d') : 'Recent';
            $reason = $user->exit_reason ?: 'Separation / Resignation';
            return back()->withInput()->withErrors([
                'login' => "ACCESS DENIED: Your employee account has been DEACTIVATED as of {$exitDate} (Reason: {$reason}). Please contact HR department.",
            ]);
        }

        // Check if Pending Activation
        if ($user->isPendingActivation()) {
            return back()->withInput()->withErrors([
                'login' => "Account is pending activation! Please use the SMS link sent to your phone ({$user->phone}) to create your password.",
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function quickLogin($type)
    {
        $user = match ($type) {
            'admin' => User::where('role', 'Admin')->first(),
            'zayd' => User::where('phone', '+92 300 1234567')->first(),
            'fatima' => User::where('phone', '+92 321 9876543')->first(),
            'inactive' => User::where('phone', '+92 322 8899001')->first(),
            default => null,
        };

        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'Requested test user not found.']);
        }

        if ($user->isOffboarded()) {
            $exitDate = $user->exit_date ? $user->exit_date->format('Y-m-d') : 'Recent';
            return redirect()->route('login')->withErrors([
                'login' => "ACCESS DENIED: {$user->name}'s account was DEACTIVATED on {$exitDate} (Reason: {$user->exit_reason}). Offboarded employees cannot access the system.",
            ]);
        }

        Auth::login($user);
        request()->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out safely.');
    }

    public function showActivation(Request $request)
    {
        $token = $request->query('token');
        $user = null;

        if ($token) {
            $user = User::where('activation_token', $token)->first();
        }

        if (!$user) {
            // Find any pending intern for demonstration if no token passed
            $user = User::where('status', 'Pending_Activation')->first();
            if ($user && !$token) {
                $token = $user->activation_token;
            }
        }

        return view('auth.activate', compact('user', 'token'));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = $this->employeeService->activateAccount($request->token, $request->password);
            return redirect()->route('login')->with('success', "Account for {$user->name} activated successfully! You may now sign in with your phone & new password.");
        } catch (\Exception $e) {
            return back()->withErrors(['token' => 'Invalid or expired activation token.']);
        }
    }
}
