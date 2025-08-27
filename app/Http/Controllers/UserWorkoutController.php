<?php

namespace App\Http\Controllers;

use App\Models\UserWorkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserWorkoutController extends Controller
{
    public function index()
    {
        return response()->json(UserWorkout::all(), 200);
    }

    public function show($id)
    {
        $userWorkout = UserWorkout::findOrFail($id);
        return response()->json($userWorkout, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'workout_id' => 'required|exists:workouts,id',
            'started_at' => 'required|date',
            'completed_at' => 'nullable|date',
            'calories_burned' => 'nullable|integer|min:0',
            'exercise_progress' => 'nullable|array',
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userWorkout = UserWorkout::create($request->all());
        return response()->json($userWorkout, 201);
    }

    public function update(Request $request, $id)
    {
        $userWorkout = UserWorkout::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'workout_id' => 'sometimes|exists:workouts,id',
            'started_at' => 'sometimes|date',
            'completed_at' => 'nullable|date',
            'calories_burned' => 'nullable|integer|min:0',
            'exercise_progress' => 'nullable|array',
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userWorkout->update($request->all());
        return response()->json($userWorkout, 200);
    }

    public function destroy($id)
    {
        $userWorkout = UserWorkout::findOrFail($id);
        $userWorkout->delete();
        return response()->json(null, 204);
    }
}