<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dietary_preferences',
        'allergies',
        'meal_types',
        'caloric_goal',
        'cooking_time_preference',
        'serving_preference',
    ];

    protected $casts = [
        'dietary_preferences' => 'array',
        'allergies' => 'array',
        'meal_types' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
