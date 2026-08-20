<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('admin.login', [
            'recaptchaSiteKey' => Config::get('services.recaptcha.site_key'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $isLocalhost = in_array($request->ip(), ['127.0.0.1', '::1'])
            || $request->host() === 'localhost';

        if (! $isLocalhost) {
            $recaptchaResponse = $request->input('g-recaptcha-response');

            if (! $recaptchaResponse) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA is required.'])->withInput($request->only('username'));
            }

            $recaptchaSecret = Config::get('services.recaptcha.secret_key');

            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?'.http_build_query([
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip(),
            ]));

            $responseData = json_decode($verifyResponse);

            if (! $responseData || ! $responseData->success) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->withInput($request->only('username'));
            }
        }

        $user = Auth::guard('admin')->attempt([
            'email' => $request->username,
            'password' => $request->password,
            'is_admin' => true,
        ]);

        if (! $user) {
            return back()->withErrors(['username' => 'Invalid admin credentials.'])->withInput($request->only('username'));
        }

        $authUser = Auth::guard('admin')->user();
        $authUser->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
