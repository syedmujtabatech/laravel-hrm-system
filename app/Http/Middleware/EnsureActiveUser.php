<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isOffboarded()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $exitDate = $user->exit_date ? $user->exit_date->format('Y-m-d') : 'Recent';
                $reason = $user->exit_reason ?: 'Separation / Deactivation';

                return redirect()->route('login')->withErrors([
                    'login' => "ACCESS DENIED: Your employee account has been DEACTIVATED as of {$exitDate} (Reason: {$reason}). Please contact HR department.",
                ]);
            }

            if ($user->isPendingActivation()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'login' => "Your account is pending password activation via SMS. Please check your phone for the setup link.",
                ]);
            }
        }

        return $next($request);
    }
}
