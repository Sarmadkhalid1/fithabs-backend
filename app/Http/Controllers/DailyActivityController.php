<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailyActivityController extends Controller
{
    public function index()
    {
        return response()->json(DailyActivity::all(), 200);
    }

    public function show($id)
    {
        $activity = DailyActivity::findOrFail($id);
        return response()->json($activity, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'steps' => 'integer|min:0',
            'calories_consumed' => 'integer|min:0',
            'calories_burned' => 'integer|min:0',
            'water_intake' => 'numeric|min:0',
            'sleep_time' => 'integer|min:0',
            'daily_progress_percentage' => 'numeric|min:0|max:100',
            'protein_goal' => 'integer|min:0',
            'carbs_goal' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activity = DailyActivity::create($request->all());
        return response()->json($activity, 201);
    }

    public function update(Request $request, $id)
    {
        $activity = DailyActivity::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            'user_id' => 'sometimes|exists:users,id',
            'steps' => 'sometimes|integer|min:0',
            'calories_consumed' => 'sometimes|integer|min:0',
            'calories_burned' => 'sometimes|integer|min:0',
            'water_intake' => 'sometimes|numeric|min:0',
            'sleep_time' => 'sometimes|integer|min:0',
            'daily_progress_percentage' => 'sometimes|numeric|min:0|max:100',
            'protein_goal' => 'sometimes|integer|min:0',
            'carbs_goal' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $activity->update($request->all());
        return response()->json($activity, 200);
    }

    public function destroy($id)
    {
        $activity = DailyActivity::findOrFail($id);
        $activity->delete();
        return response()->json(null, 204);
    }
}