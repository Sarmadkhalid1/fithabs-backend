<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'meal_type',
        'prep_time_minutes',
        'cook_time_minutes',
        'servings',
        'calories_per_serving',
        'protein_per_serving',
        'carbs_per_serving',
        'fat_per_serving',
        'fiber_per_serving',
        'sugar_per_serving',
        'ingredients',
        'instructions',
        'dietary_tags',
        'allergen_info',
        'difficulty',
        'is_featured',
        'is_active',
        'created_by_admin',
    ];

    protected $casts = [
        'dietary_tags' => 'array',
        'allergen_info' => 'array',
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
}