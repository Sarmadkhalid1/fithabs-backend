<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    public function index()
    {
        return response()->json(Exercise::all(), 200);
    }

    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return response()->json($exercise, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'workout_id' => 'required|exists:workouts,id',
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|string|url',
            'image_url' => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'repetitions' => 'nullable|integer|min:0',
            'sets' => 'nullable|integer|min:0',
            'rest_seconds' => 'nullable|integer|min:0',
            'order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $exercise = Exercise::create($request->all());
        return response()->json($exercise, 201);
    }

    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'workout_id' => 'sometimes|exists:workouts,id',
            'name' => 'sometimes|string|max:255',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|string|url',
            'image_url' => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'repetitions' => 'nullable|integer|min:0',
            'sets' => 'nullable|integer|min:0',
            'rest_seconds' => 'nullable|integer|min:0',
            'order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $exercise->update($request->all());
        return response()->json($exercise, 200);
    }

    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();
        return response()->json(null, 204);
    }
}