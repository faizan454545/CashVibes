<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Str;

class ReferralService
{
    public function generateReferralCode(User $user): string
    {
        $code = strtoupper('CV-'.Str::random(5).'-'.rand(100, 999));

        $user->update(['referral_code' => $code]);

        return $code;
    }

    public function applyReferral(User $newUser, string $referralCode): bool
    {
        $referrer = User::where('referral_code', $referralCode)->first();

        if (! $referrer || $referrer->id === $newUser->id) {
            return false;
        }

        $newUser->update(['referred_by' => $referrer->id]);

        Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $newUser->id,
        ]);

        return true;
    }

    public function checkAndTriggerReward(User $referee): bool
    {
        $referral = Referral::where('referee_id', $referee->id)
            ->where('is_first_task_done', false)
            ->first();

        if ($referral) {
            return $referral->triggerReward();
        }

        return false;
    }
}
