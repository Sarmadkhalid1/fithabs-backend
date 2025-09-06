<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPreferenceController extends Controller
{
    public function show()
    {
        try {
            $user = auth()->user();
            $preferences = $user->userPreferences;
            
            return response()->json([
                'status' => 'success',
                'data' => $preferences
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user preferences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dietary_preferences' => 'nullable|array',
            'dietary_preferences.*' => 'string|in:vegetarian,keto,vegan,paleo,gluten_free,no_preferences',
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|in:nuts,eggs,dairy,shellfish,no_allergies',
            'meal_types' => 'nullable|array',
            'meal_types.*' => 'string|in:breakfast,lunch,dinner,snack',
            'caloric_goal' => 'nullable|in:less_than_1500,1500_2000,more_than_2000,not_sure',
            'cooking_time_preference' => 'nullable|in:less_than_15,15_30,more_than_30',
            'serving_preference' => 'nullable|in:1,2,3_5,more_than_4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            
            // Update or create preferences
            $preferences = $user->userPreferences()->updateOrCreate(
                ['user_id' => $user->id],
                $request->only([
                    'dietary_preferences',
                    'allergies',
                    'meal_types',
                    'caloric_goal',
                    'cooking_time_preference',
                    'serving_preference'
                ])
            );

            return response()->json([
                'status' => 'success',
                'message' => 'User preferences saved successfully',
                'data' => $preferences
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save user preferences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dietary_preferences' => 'nullable|array',
            'dietary_preferences.*' => 'string|in:vegetarian,keto,vegan,paleo,gluten_free,no_preferences',
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|in:nuts,eggs,dairy,shellfish,no_allergies',
            'meal_types' => 'nullable|array',
            'meal_types.*' => 'string|in:breakfast,lunch,dinner,snack',
            'caloric_goal' => 'nullable|in:less_than_1500,1500_2000,more_than_2000,not_sure',
            'cooking_time_preference' => 'nullable|in:less_than_15,15_30,more_than_30',
            'serving_preference' => 'nullable|in:1,2,3_5,more_than_4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $preferences = $user->userPreferences;

            if (!$preferences) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User preferences not found. Please create preferences first.'
                ], 404);
            }

            $preferences->update($request->only([
                'dietary_preferences',
                'allergies',
                'meal_types',
                'caloric_goal',
                'cooking_time_preference',
                'serving_preference'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'User preferences updated successfully',
                'data' => $preferences->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user preferences',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
