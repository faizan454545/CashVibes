<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.settings', ['admin' => $user]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|string|min:6|confirmed',
            'recaptcha_site_key' => 'nullable|string',
            'recaptcha_secret_key' => 'nullable|string',
            'bitlabs_app_token' => 'nullable|string',
            'bitlabs_secret_key' => 'nullable|string',
            'bitlabs_server_key' => 'nullable|string',
        ]);

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->new_password),
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        if ($request->filled('recaptcha_site_key')) {
            config(['services.recaptcha.site_key' => $request->recaptcha_site_key]);
            $this->updateEnvValue('RECAPTCHA_SITE_KEY', $request->recaptcha_site_key);
        }

        if ($request->filled('recaptcha_secret_key')) {
            config(['services.recaptcha.secret_key' => $request->recaptcha_secret_key]);
            $this->updateEnvValue('RECAPTCHA_SECRET_KEY', $request->recaptcha_secret_key);
        }

        if ($request->filled('bitlabs_app_token')) {
            config(['services.bitlabs.app_token' => $request->bitlabs_app_token]);
            $this->updateEnvValue('BITLABS_APP_TOKEN', $request->bitlabs_app_token);
        }

        if ($request->filled('bitlabs_secret_key')) {
            config(['services.bitlabs.secret_key' => $request->bitlabs_secret_key]);
            $this->updateEnvValue('BITLABS_SECRET_KEY', $request->bitlabs_secret_key);
        }

        if ($request->filled('bitlabs_server_key')) {
            config(['services.bitlabs.server_key' => $request->bitlabs_server_key]);
            $this->updateEnvValue('BITLABS_SERVER_KEY', $request->bitlabs_server_key);
        }

        return back()->with('success', 'Admin settings updated successfully.');
    }

    private function updateEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            if (str_contains($content, $key.'=')) {
                $content = preg_replace('/^'.preg_quote($key).'=.*/m', $key.'='.$value, $content);
            } else {
                $content .= "\n".$key.'='.$value;
            }

            file_put_contents($path, $content);
        }
    }
}
