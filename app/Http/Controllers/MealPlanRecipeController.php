<?php

namespace App\Http\Controllers;

use App\Models\MealPlanRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealPlanRecipeController extends Controller
{
    public function index()
    {
        return response()->json(MealPlanRecipe::all(), 200);
    }

    public function show($id)
    {
        $mealPlanRecipe = MealPlanRecipe::findOrFail($id);
        return response()->json($mealPlanRecipe, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meal_plan_id' => 'required|exists:meal_plans,id',
            'recipe_id' => 'required|exists:recipes,id',
            'day_number' => 'required|integer|min:1',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'servings' => 'integer|min:1',
            'order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mealPlanRecipe = MealPlanRecipe::create($request->all());
        return response()->json($mealPlanRecipe, 201);
    }

    public function update(Request $request, $id)
    {
        $mealPlanRecipe = MealPlanRecipe::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'meal_plan_id' => 'sometimes|exists:meal_plans,id',
            'recipe_id' => 'sometimes|exists:recipes,id',
            'day_number' => 'sometimes|integer|min:1',
            'meal_type' => 'sometimes|in:breakfast,lunch,dinner,snack',
            'servings' => 'sometimes|integer|min:1',
            'order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mealPlanRecipe->update($request->all());
        return response()->json($mealPlanRecipe, 200);
    }

    public function destroy($id)
    {
        $mealPlanRecipe = MealPlanRecipe::findOrFail($id);
        $mealPlanRecipe->delete();
        return response()->json(null, 204);
    }
}