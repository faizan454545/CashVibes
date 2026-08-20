<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referee_id',
        'is_first_task_done',
        'reward_triggered_at',
    ];

    protected $casts = [
        'is_first_task_done' => 'boolean',
        'reward_triggered_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function triggerReward(): bool
    {
        if ($this->is_first_task_done) {
            return false;
        }

        $this->update([
            'is_first_task_done' => true,
            'reward_triggered_at' => now(),
        ]);

        $referralBonus = config('app.referral_bonus_amt');

        $this->referrer->credit(
            $referralBonus,
            'REFERRAL_BONUS',
            json_encode([
                'referee_id' => $this->referee_id,
                'referee_name' => $this->referee->name,
            ])
        );

        return true;
    }
}
