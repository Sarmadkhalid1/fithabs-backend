<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'duration_days',
        'goals',
        'dietary_preferences',
        'allergen_free',
        'target_calories_min',
        'target_calories_max',
        'difficulty',
        'is_featured',
        'is_active',
        'created_by_admin',
    ];

    protected $casts = [
        'goals' => 'array',
        'dietary_preferences' => 'array',
        'allergen_free' => 'array',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin');
    }

    public function mealPlanRecipes()
    {
        return $this->hasMany(MealPlanRecipe::class);
    }

    public function userMealPlans()
    {
        return $this->hasMany(UserMealPlan::class);
    }
}