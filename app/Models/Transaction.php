<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source_ref',
        'provider',
        'amount',
        'type',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }

    public function isSettled(): bool
    {
        return $this->status === 'settled';
    }

    public function getFormattedAmountAttribute(): string
    {
        $prefix = $this->type === 'credit' ? '+' : '-';

        return $prefix.number_format($this->amount, 2);
    }

    public function getPkrValueAttribute(): float
    {
        return $this->amount * config('app.coin_value_pkr');
    }
}
