<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FraudService;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    public function __construct(
        private FraudService $fraudService,
        private ReferralService $referralService
    ) {}

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $ip = $request->ip();

            if ($this->fraudService->shouldBlockRegistration($ip)) {
                Log::warning('Google OAuth blocked by fraud service', [
                    'ip' => $ip,
                    'email' => $googleUser->getEmail(),
                ]);

                return redirect()->route('login')
                    ->withErrors(['auth' => 'Access temporarily restricted. Please try again later.']);
            }

            if ($this->fraudService->detectMultiAccount($ip, $googleUser->getId())) {
                Log::warning('Google OAuth blocked - multi-account detected', [
                    'ip' => $ip,
                    'google_id' => $googleUser->getId(),
                ]);

                return redirect()->route('login')
                    ->withErrors(['auth' => 'Multiple account usage detected.']);
            }

            $user = User::where('google_id', $googleUser->getId())->first();

            if (! $user) {
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    $user->update(['google_id' => $googleUser->getId()]);
                    Log::info('Google account linked to existing email', [
                        'user_id' => $user->id,
                        'email' => $googleUser->getEmail(),
                    ]);
                } else {
                    $user = $this->createUser($googleUser, $ip);
                    Log::info('New user created via Google OAuth', [
                        'user_id' => $user->id,
                        'email' => $googleUser->getEmail(),
                    ]);
                }
            }

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
            ]);

            Auth::login($user, true);

            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');

        } catch (InvalidStateException $e) {
            Log::error('Google OAuth state mismatch', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')
                ->withErrors(['auth' => 'Authentication session expired. Please try again.']);

        } catch (\Exception $e) {
            Log::error('Google OAuth callback error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login')
                ->withErrors(['auth' => 'Google sign-in failed. Please try again or use email login.']);
        }
    }

    private function createUser($googleUser, ?string $ip): User
    {
        $user = User::create([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'avatar_url' => $googleUser->getAvatar(),
            'coin_balance' => config('app.registration_bonus'),
            'ip_address' => $ip,
            'referral_code' => strtoupper('CV-'.Str::random(5).'-'.random_int(100, 999)),
            'access_key' => strtoupper(bin2hex(random_bytes(24))),
            'last_login_ip' => $ip,
        ]);

        $user->transactions()->create([
            'source_ref' => 'REGISTRATION_BONUS',
            'amount' => config('app.registration_bonus'),
            'type' => 'credit',
            'status' => 'settled',
            'metadata' => json_encode(['source' => 'signup_bonus']),
        ]);

        $referralCode = request()->query('ref');
        if ($referralCode) {
            $this->referralService->applyReferral($user, $referralCode);
        }

        return $user;
    }

    /**
     * Handle Firebase authentication callback from frontend.
     * Receives google_id, email, name, avatar_url via JSON payload.
     */
    public function handleFirebaseCallback(Request $request)
    {
        try {
            $request->validate([
                'google_id' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'avatar_url' => 'nullable|url',
            ]);

            $ip = $request->ip();

            // Find or create user first (existing users bypass fraud)
            $user = User::where('google_id', $request->google_id)->first();

            if (! $user) {
                // New user: run fraud checks
                if ($this->fraudService->isVpnOrDatacenter($ip)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access restricted. VPN or datacenter connections are not allowed.',
                    ], 403);
                }

                if ($this->fraudService->detectMultiAccount($ip, $request->google_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Multiple account usage detected from this IP.',
                    ], 403);
                }

                if ($this->fraudService->shouldBlockRegistration($ip)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many attempts. Please try again later.',
                    ], 429);
                }

                // Check if email already exists (different google account)
                $user = User::where('email', $request->email)->first();

                if ($user) {
                    // Link existing account to new Google ID
                    $user->update(['google_id' => $request->google_id]);
                } else {
                    // Create new user with 50 coin registration bonus
                    $user = $this->createUserFromFirebase($request, $ip);
                    $user->refresh();
                }
            }

            // Update last login timestamp and IP
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
            ]);

            // Check if account is suspended
            if (! $user->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been suspended. Please contact support.',
                ], 403);
            }

            // Login user via Laravel AuthGuard
            Auth::login($user, true);

            $redirectUrl = $user->is_admin ? route('admin.dashboard') : route('dashboard');

            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'coin_balance' => $user->coin_balance,
                ],
            ]);

        } catch (ValidationException $e) {
            Log::warning('Firebase Auth validation error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid authentication data: '.$e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Firebase Auth Error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please try again.',
            ], 500);
        }
    }

    /**
     * Create new user from Firebase authentication data.
     * Grants 50 coins registration bonus automatically.
     */
    private function createUserFromFirebase(Request $request, ?string $ip): User
    {
        $user = User::create([
            'google_id' => $request->google_id,
            'name' => $request->name,
            'email' => $request->email,
            'avatar_url' => $request->avatar_url,
            'coin_balance' => config('app.registration_bonus'),
            'ip_address' => $ip,
            'referral_code' => strtoupper('CV-'.Str::random(5).'-'.random_int(100, 999)),
            'access_key' => strtoupper(bin2hex(random_bytes(24))),
            'last_login_ip' => $ip,
        ]);

        // Log registration bonus transaction
        $user->transactions()->create([
            'source_ref' => 'REGISTRATION_BONUS',
            'amount' => config('app.registration_bonus'),
            'type' => 'credit',
            'status' => 'settled',
            'metadata' => json_encode([
                'source' => 'firebase_signup_bonus',
                'ip' => $ip,
            ]),
        ]);

        // Check for referral code in session or query
        $referralCode = session('referral_code') ?? request()->query('ref');
        if ($referralCode) {
            $this->referralService->applyReferral($user, $referralCode);
        }

        return $user;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
