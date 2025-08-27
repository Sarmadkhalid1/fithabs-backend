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
}