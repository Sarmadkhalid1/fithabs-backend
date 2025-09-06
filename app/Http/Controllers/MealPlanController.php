<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealPlanController extends Controller
{
    public function index()
    {
        try {
            $mealPlans = MealPlan::all();
            return response()->json([
                'status' => 'success',
                'data' => $mealPlans,
                'count' => $mealPlans->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve meal plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $mealPlan = MealPlan::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $mealPlan
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal plan not found',
                'error' => 'No meal plan found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve meal plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'goals' => 'nullable|array',
            'dietary_preferences' => 'nullable|array',
            'allergen_free' => 'nullable|array',
            'target_calories_min' => 'nullable|integer|min:0',
            'target_calories_max' => 'nullable|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'created_by_admin' => 'required|exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mealPlan = MealPlan::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Meal plan created successfully',
            'data' => $mealPlan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $mealPlan = MealPlan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'duration_days' => 'sometimes|integer|min:1',
            'goals' => 'nullable|array',
            'dietary_preferences' => 'nullable|array',
            'allergen_free' => 'nullable|array',
            'target_calories_min' => 'nullable|integer|min:0',
            'target_calories_max' => 'nullable|integer|min:0',
            'difficulty' => 'sometimes|in:easy,medium,hard',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'created_by_admin' => 'sometimes|exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mealPlan->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Meal plan updated successfully',
            'data' => $mealPlan
        ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal plan not found',
                'error' => 'No meal plan found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update meal plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mealPlan = MealPlan::findOrFail($id);
            $mealPlan->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Meal plan deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Meal plan not found',
                'error' => 'No meal plan found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete meal plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter meal plans by difficulty, goals, or dietary preferences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'difficulty' => 'nullable|in:easy,medium,hard',
                'goals' => 'nullable|array',
                        'dietary_preferences' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

            $query = MealPlan::query();

            if ($request->has('difficulty')) {
                $query->where('difficulty', $request->input('difficulty'));
            }

            if ($request->has('goals')) {
                $query->whereJsonContains('goals', $request->input('goals'));
            }

            if ($request->has('dietary_preferences')) {
                $query->whereJsonContains('dietary_preferences', $request->input('dietary_preferences'));
            }

            $mealPlans = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $mealPlans,
                'count' => $mealPlans->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to filter meal plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get personalized meal plans based on user preferences
     */
    public function personalized(Request $request)
    {
        try {
            $user = auth()->user();
            $preferences = $user->userPreferences;

            // Validate meal type parameter if provided
            $mealType = $request->query('meal_type');
            if ($mealType) {
                $validator = Validator::make(['meal_type' => $mealType], [
                    'meal_type' => 'required|in:breakfast,lunch,dinner,snack'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => $validator->errors()
                    ], 422);
                }
            }

            $query = MealPlan::where('is_active', true);

            // Filter by meal type if provided
            if ($mealType) {
                $query->whereHas('mealPlanRecipes', function($q) use ($mealType) {
                    $q->where('meal_type', $mealType);
                });
            }

            // Apply user preferences if available
            if ($preferences) {
                // Filter by dietary preferences
                if ($preferences->dietary_preferences && !in_array('no_preferences', $preferences->dietary_preferences)) {
                    $query->where(function($q) use ($preferences) {
                        foreach ($preferences->dietary_preferences as $preference) {
                            $q->orWhereJsonContains('dietary_preferences', $preference);
                        }
                    });
                }

                // Filter by allergies (exclude meal plans with user's allergies)
                if ($preferences->allergies && !in_array('no_allergies', $preferences->allergies)) {
                    // For now, let's be more flexible and not exclude based on allergies
                    // This can be enhanced later with more sophisticated allergy matching
                    // $query->where(function($q) use ($preferences) {
                    //     foreach ($preferences->allergies as $allergy) {
                    //         $q->where(function($subQ) use ($allergy) {
                    //             $subQ->whereJsonLength('allergen_free', 0)
                    //                  ->orWhereJsonDoesntContain('allergen_free', $allergy);
                    //         });
                    //     }
                    // });
                }

                // Filter by caloric goal
                if ($preferences->caloric_goal && $preferences->caloric_goal !== 'not_sure') {
                    switch ($preferences->caloric_goal) {
                        case 'less_than_1500':
                            $query->where('target_calories_max', '<=', 1500);
                            break;
                        case '1500_2000':
                            $query->where('target_calories_min', '>=', 1500)
                                  ->where('target_calories_max', '<=', 2000);
                            break;
                        case 'more_than_2000':
                            $query->where('target_calories_min', '>=', 2000);
                            break;
                    }
                }
            }

            $mealPlans = $query->with(['mealPlanRecipes' => function($q) use ($mealType) {
                if ($mealType) {
                    $q->where('meal_type', $mealType);
                }
            }, 'mealPlanRecipes.recipe'])->get();

            // Remove meal plans that have no recipes after filtering
            if ($mealType) {
                $mealPlans = $mealPlans->filter(function($mealPlan) {
                    return $mealPlan->mealPlanRecipes->count() > 0;
                });
            }

            return response()->json([
                'status' => 'success',
                'data' => $mealPlans,
                'count' => $mealPlans->count(),
                'meal_type_filter' => $mealType ?: 'all'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve personalized meal plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get meal plans for specific meal type
     */
    public function getByMealType($mealType)
    {
        try {
            $validator = Validator::make(['meal_type' => $mealType], [
                'meal_type' => 'required|in:breakfast,lunch,dinner,snack'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();
            $preferences = $user->userPreferences;

            $query = MealPlan::where('is_active', true)
                ->whereHas('mealPlanRecipes', function($q) use ($mealType) {
                    $q->where('meal_type', $mealType);
                });

            // Apply user preferences if available
            if ($preferences) {
                // Filter by dietary preferences
                if ($preferences->dietary_preferences && !in_array('no_preferences', $preferences->dietary_preferences)) {
                    $query->where(function($q) use ($preferences) {
                        foreach ($preferences->dietary_preferences as $preference) {
                            $q->orWhereJsonContains('dietary_preferences', $preference);
                        }
                    });
                }

                // Filter by allergies
                if ($preferences->allergies && !in_array('no_allergies', $preferences->allergies)) {
                    $query->where(function($q) use ($preferences) {
                        foreach ($preferences->allergies as $allergy) {
                            $q->whereJsonLength('allergen_free', 0)
                              ->orWhereJsonDoesntContain('allergen_free', $allergy);
                        }
                    });
                }
            }

            $mealPlans = $query->with(['mealPlanRecipes' => function($q) use ($mealType) {
                $q->where('meal_type', $mealType)->with('recipe');
            }])->get();

            return response()->json([
                'status' => 'success',
                'data' => $mealPlans,
                'count' => $mealPlans->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve meal plans for meal type',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}