<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payout_gateway',
        'requested_coins',
        'fiat_pkr_equivalent',
        'account_number_or_id',
        'account_title_receiver',
        'payout_status',
        'user_ip',
        'processed_at',
    ];

    protected $casts = [
        'requested_coins' => 'integer',
        'fiat_pkr_equivalent' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateFiatPkr(): float
    {
        return $this->requested_coins * config('app.coin_value_pkr');
    }

    public function approve(): bool
    {
        return $this->update([
            'payout_status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    public function reject(): bool
    {
        return $this->update([
            'payout_status' => 'rejected',
            'processed_at' => now(),
        ]);
    }

    public function isPending(): bool
    {
        return $this->payout_status === 'pending';
    }

    // Validation rules
    public static function validateGatewayAccount(string $gateway, string $account): bool
    {
        return match ($gateway) {
            'easypaisa', 'jazzcash' => (bool) preg_match('/^(03)[0-9]{9}$/', $account),
            'binance_pay' => (bool) preg_match('/^[0-9]{9,12}$/', $account),
            default => false,
        };
    }
}
