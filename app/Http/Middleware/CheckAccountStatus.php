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
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'banned' => true,
                        'ban_reason' => $user->ban_reason ?: 'Violation of Terms of Service',
                    ], 403)
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $banReason = $user->ban_reason ?: 'Violation of Terms of Service';

                return redirect()->route('login')
                    ->with('banned', true)
                    ->with('ban_reason', $banReason);
            }
        }

        $response = $next($request);

        if ($request->is('api/*') || $request->expectsJson()) {
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->header('Pragma', 'no-cache');
                $response->header('Expires', '0');
            }
        }

        return $response;
    }
}
