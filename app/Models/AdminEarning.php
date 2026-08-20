<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminEarning extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'transaction_id',
        'gross_coins',
        'admin_coins',
        'user_coins',
    ];

    protected $casts = [
        'gross_coins' => 'decimal:4',
        'admin_coins' => 'decimal:4',
        'user_coins' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
