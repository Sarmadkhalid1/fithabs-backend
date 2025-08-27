<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'difficulty',
        'type',
        'duration_minutes',
        'calories_per_session',
        'equipment_needed',
        'tags',
        'is_featured',
        'is_active',
        'created_by_admin',
    ];

    protected $casts = [
        'equipment_needed' => 'array',
        'tags' => 'array',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class);
    }
}