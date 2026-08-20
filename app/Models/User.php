<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'password',
        'avatar_url',
        'coin_balance',
        'referred_by',
        'ip_address',
        'account_status',
        'ban_reason',
        'is_admin',
        'referral_code',
        'access_key',
        'is_2fa_enabled',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'google_id',
        'ip_address',
        'access_key',
        'password',
    ];

    protected $casts = [
        'coin_balance' => 'decimal:4',
        'is_2fa_enabled' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relationships

    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class, 'referee_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function userTaskLogs(): HasMany
    {
        return $this->hasMany(UserTaskLog::class);
    }

    // Accessors

    public function getBalancePkrAttribute(): float
    {
        return (float) $this->coin_balance * config('app.coin_value_pkr');
    }

    public function getReferredCountAttribute(): int
    {
        return $this->referralsMade()->count();
    }

    public function getTotalEarnedAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->where('status', 'settled')
            ->sum('amount');
    }

    // Methods

    public function credit(float $amount, string $source, ?string $metadata = null): Transaction
    {
        $this->increment('coin_balance', $amount);

        return $this->transactions()->create([
            'source_ref' => $source,
            'amount' => $amount,
            'type' => 'credit',
            'status' => 'settled',
            'metadata' => $metadata,
        ]);
    }

    public function debit(float $amount, string $source, ?string $metadata = null): Transaction
    {
        $this->decrement('coin_balance', $amount);

        return $this->transactions()->create([
            'source_ref' => $source,
            'amount' => $amount,
            'type' => 'debit',
            'status' => 'settled',
            'metadata' => $metadata,
        ]);
    }

    public function canWithdraw(): bool
    {
        return $this->coin_balance >= config('app.min_withdrawal_coins');
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    public function isSuspicious(): bool
    {
        return $this->account_status === 'suspended';
    }
}
