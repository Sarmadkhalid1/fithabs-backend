<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealPlanController extends Controller
{
    public function index()
    {
        return response()->json(MealPlan::all(), 200);
    }

    public function show($id)
    {
        $mealPlan = MealPlan::findOrFail($id);
        return response()->json($mealPlan, 200);
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
            return response()->json($validator->errors(), 422);
        }

        $mealPlan = MealPlan::create($request->all());
        return response()->json($mealPlan, 201);
    }

    public function update(Request $request, $id)
    {
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
            return response()->json($validator->errors(), 422);
        }

        $mealPlan->update($request->all());
        return response()->json($mealPlan, 200);
    }

    public function destroy($id)
    {
        $mealPlan = MealPlan::findOrFail($id);
        $mealPlan->delete();
        return response()->json(null, 204);
    }

    /**
     * Filter meal plans by difficulty, goals, or dietary preferences.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'difficulty' => 'nullable|in:easy,medium,hard',
            'goals' => 'nullable|array',
            'dietary_preferences' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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

        return response()->json($mealPlans, 200);
    }
}