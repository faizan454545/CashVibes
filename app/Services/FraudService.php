<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FraudService
{
    private const VPN_DATACENTER_KEYWORDS = [
        'amazon', 'aws', 'google', 'azure', 'digitalocean',
        'linode', 'vultr', 'ovh', 'hetzner', 'cloudflare',
        'heroku', 'vercel', 'netlify',
    ];

    private const BLOCKED_IP_RANGES = [
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
    ];

    private function isLocalEnvironment(): bool
    {
        return app()->environment('local') || app()->environment('testing');
    }

    private function isLocalIp(?string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost', '0.0.0.0']);
    }

    public function isVpnOrDatacenter(?string $ip): bool
    {
        if ($this->isLocalEnvironment() || $this->isLocalIp($ip)) {
            return false;
        }

        if (! $ip) {
            return false;
        }

        $hostname = gethostbyaddr($ip);
        if (! $hostname) {
            return false;
        }

        foreach (self::VPN_DATACENTER_KEYWORDS as $keyword) {
            if (str_contains(strtolower($hostname), $keyword)) {
                return true;
            }
        }

        return false;
    }

    public function detectMultiAccount(string $ip, string $googleId): bool
    {
        if ($this->isLocalEnvironment() || $this->isLocalIp($ip)) {
            return false;
        }

        $cacheKey = "ip_logins:{$ip}";
        $logins = Cache::get($cacheKey, []);

        $logins[] = [
            'google_id' => $googleId,
            'timestamp' => now()->timestamp,
        ];

        $logins = array_filter($logins, function ($login) {
            return $login['timestamp'] > now()->subHours(24)->timestamp;
        });

        Cache::put($cacheKey, $logins, 86400);

        $uniqueGoogleIds = array_unique(array_column($logins, 'google_id'));

        // Allow up to 2 distinct accounts from same IP before flagging
        return count($uniqueGoogleIds) > 2;
    }

    public function shouldBlockRegistration(?string $ip): bool
    {
        if ($this->isLocalEnvironment() || $this->isLocalIp($ip)) {
            return false;
        }

        if ($this->isVpnOrDatacenter($ip)) {
            return true;
        }

        $cacheKey = "registration_attempts:{$ip}";
        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= 3) {
            return true;
        }

        Cache::put($cacheKey, $attempts + 1, 3600);

        return false;
    }

    public function flagSuspiciousActivity(User $user, string $reason): void
    {
        $user->update(['account_status' => 'suspended']);

        Log::channel('fraud')->warning(
            "Suspicious activity flagged for user {$user->id}: {$reason}"
        );
    }
}
