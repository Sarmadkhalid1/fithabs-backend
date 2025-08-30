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
}