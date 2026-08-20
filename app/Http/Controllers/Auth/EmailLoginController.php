<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FraudService;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class EmailLoginController extends Controller
{
    public function __construct(
        private FraudService $fraudService,
        private ReferralService $referralService
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->onlyInput('email');
        }

        if (! $user->password) {
            return back()->withErrors([
                'email' => 'This account uses Google Sign-In. Please use Google to log in.',
            ])->onlyInput('email');
        }

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('email');
        }

        if (! $user->isActive()) {
            return back()->withErrors([
                'email' => 'Your account has been suspended. Please contact support.',
            ])->onlyInput('email');
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        Auth::login($user, true);

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $ip = $request->ip();

        if ($this->fraudService->shouldBlockRegistration($ip)) {
            return back()->withErrors([
                'email' => 'Too many registration attempts. Please try again later.',
            ])->onlyInput('name', 'email');
        }

        $referralCode = session('referral_code') ?? $request->query('ref');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'google_id' => 'email-'.Str::random(40),
            'coin_balance' => config('app.registration_bonus'),
            'ip_address' => $ip,
            'access_key' => strtoupper(bin2hex(random_bytes(24))),
            'referral_code' => strtoupper('CV-'.Str::random(5).'-'.random_int(100, 999)),
            'last_login_ip' => $ip,
        ]);

        $user->transactions()->create([
            'source_ref' => 'REGISTRATION_BONUS',
            'amount' => config('app.registration_bonus'),
            'type' => 'credit',
            'status' => 'settled',
            'metadata' => json_encode(['source' => 'email_signup_bonus']),
        ]);

        if ($referralCode) {
            $this->referralService->applyReferral($user, $referralCode);
        }

        $user->update(['last_login_at' => now()]);

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
