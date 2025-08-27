<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkoutController extends Controller
{
    public function index()
    {
        return response()->json(Workout::all(), 200);
    }

    public function show($id)
    {
        $workout = Workout::findOrFail($id);
        return response()->json($workout, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'type' => 'required|in:upper_body,lower_body,full_body,cardio,flexibility',
            'duration_minutes' => 'nullable|integer|min:0',
            'calories_per_session' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'created_by_admin' => 'required|exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $workout = Workout::create($request->all());
        return response()->json($workout, 201);
    }

    public function update(Request $request, $id)
    {
        $workout = Workout::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string',
            'difficulty' => 'sometimes|in:beginner,intermediate,advanced',
            'type' => 'sometimes|in:upper_body,lower_body,full_body,cardio,flexibility',
            'duration_minutes' => 'nullable|integer|min:0',
            'calories_per_session' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'tags' => 'nullable|array',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'created_by_admin' => 'sometimes|exists:admin_users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $workout->update($request->all());
        return response()->json($workout, 200);
    }

    public function destroy($id)
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();
        return response()->json(null, 204);
    }
}