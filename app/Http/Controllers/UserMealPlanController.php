<?php

namespace App\Http\Controllers;

use App\Models\UserMealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserMealPlanController extends Controller
{
    public function index()
    {
        return response()->json(UserMealPlan::all(), 200);
    }

    public function show($id)
    {
        $userMealPlan = UserMealPlan::findOrFail($id);
        return response()->json($userMealPlan, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'meal_plan_id' => 'required|exists:meal_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userMealPlan = UserMealPlan::create($request->all());
        return response()->json($userMealPlan, 201);
    }

    public function update(Request $request, $id)
    {
        $userMealPlan = UserMealPlan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'meal_plan_id' => 'sometimes|exists:meal_plans,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userMealPlan->update($request->all());
        return response()->json($userMealPlan, 200);
    }

    public function destroy($id)
    {
        $userMealPlan = UserMealPlan::findOrFail($id);
        $userMealPlan->delete();
        return response()->json(null, 204);
    }

    /**
     * Get user's current active meal plan
     */
    public function current()
    {
        try {
            $user = auth()->user();
            $currentMealPlan = $user->userMealPlans()
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with(['mealPlan.mealPlanRecipes.recipe'])
                ->first();

            if (!$currentMealPlan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No active meal plan found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $currentMealPlan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve current meal plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's meal plan for specific date
     */
    public function getByDate($date)
    {
        try {
            $validator = Validator::make(['date' => $date], [
                'date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();
            $mealPlan = $user->userMealPlans()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->with(['mealPlan.mealPlanRecipes' => function($q) use ($date) {
                    $q->where('day_number', date('j', strtotime($date)))
                      ->with('recipe');
                }])
                ->first();

            if (!$mealPlan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No meal plan found for the specified date'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $mealPlan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve meal plan for date',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}