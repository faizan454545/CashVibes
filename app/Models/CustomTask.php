<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reward_coins',
        'admin_revenue_estimate',
        'task_url',
        'is_active',
    ];

    protected $casts = [
        'reward_coins' => 'decimal:4',
        'admin_revenue_estimate' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function userLogs(): HasMany
    {
        return $this->hasMany(UserTaskLog::class);
    }
}
