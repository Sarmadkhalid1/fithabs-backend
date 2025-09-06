<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoal extends Model
{
    protected $fillable = [
        'user_id',
        'steps',
        'calories',
        'water'
    ];

    protected $casts = [
        'steps' => 'integer',
        'calories' => 'decimal:2',
        'water' => 'decimal:2'
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
