<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTaskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'custom_task_id',
        'visited',
        'claimed',
        'coins_awarded',
    ];

    protected $casts = [
        'visited' => 'boolean',
        'claimed' => 'boolean',
        'coins_awarded' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customTask(): BelongsTo
    {
        return $this->belongsTo(CustomTask::class);
    }
}
