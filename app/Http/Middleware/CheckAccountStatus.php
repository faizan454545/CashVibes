<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user()->fresh();

            if ($user && $user->account_status === 'suspended') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $banReason = $user->ban_reason ?: 'Violation of Terms of Service';

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'banned' => true,
                        'ban_reason' => $banReason,
                    ], 403);
                }

                return redirect()->route('login')
                    ->with('banned', true)
                    ->with('ban_reason', $banReason);
            }
        }

        return $next($request);
    }
}
