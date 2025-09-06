<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\EducationContent;
use App\Models\Recipe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Get home screen data - Today's Suggestions, Education Cards, and Recommended Food
     */
    public function index(Request $request)
    {
        try {
            // 1. Today's Suggestions - Get 3 random exercises with repetitions
            $todaysSuggestions = Exercise::whereHas('workout', function($query) {
                $query->where('is_active', true);
            })
            ->whereNotNull('repetitions')
            ->inRandomOrder()
            ->limit(3)
            ->get()
            ->map(function($exercise) {
                return [
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'image_url' => $exercise->image_url,
                    'repetitions' => $exercise->repetitions . ' Times',
                    'detail_url' => "/api/v1/exercises/{$exercise->id}"
                ];
            });

            // 2. Education Cards - Get 4 featured education contents
            $educationCards = EducationContent::where('is_active', true)
                ->where('is_featured', true)
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->map(function($content) {
                    return [
                        'id' => $content->id,
                        'title' => $content->title,
                        'description' => $content->description,
                        'cover_image' => $content->cover_image,
                        'category' => $content->category,
                        'detail_url' => "/api/v1/education-contents/{$content->id}"
                    ];
                });

            // 3. Recommended Food - Get 2 random recipes with calories
            $recommendedFood = Recipe::where('is_active', true)
                ->whereNotNull('calories_per_serving')
                ->inRandomOrder()
                ->limit(2)
                ->get()
                ->map(function($recipe) {
                    return [
                        'id' => $recipe->id,
                        'name' => $recipe->name,
                        'image_url' => $recipe->image_url,
                        'calories' => $recipe->calories_per_serving . ' Cal',
                        'meal_type' => $recipe->meal_type,
                        'detail_url' => "/api/v1/recipes/{$recipe->id}"
                    ];
                });

            $homeData = [
                'todays_suggestions' => [
                    'title' => "Today's Suggestions",
                    'items' => $todaysSuggestions,
                    'see_all_url' => '/api/v1/exercises'
                ],
                'education_cards' => [
                    'title' => "Education Cards",
                    'items' => $educationCards,
                    'see_all_url' => '/api/v1/education-contents'
                ],
                'recommended_food' => [
                    'title' => "Recommended Food",
                    'items' => $recommendedFood,
                    'see_all_url' => '/api/v1/recipes'
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $homeData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve home screen data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
