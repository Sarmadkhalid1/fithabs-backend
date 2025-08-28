<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
        'password' => 'hashed',
    ];

    // Relationships
    public function workouts()
    {
        return $this->hasMany(Workout::class, 'created_by_admin');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'created_by_admin');
    }

    public function mealPlans()
    {
        return $this->hasMany(MealPlan::class, 'created_by_admin');
    }

    public function educationContents()
    {
        return $this->hasMany(EducationContent::class, 'created_by_admin');
    }
}