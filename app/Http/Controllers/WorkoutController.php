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
        try {
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $workout = Workout::create($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Workout created successfully',
                'data' => $workout
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create workout',
                'error' => $e->getMessage()
            ], 500);
        }
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

    /**
     * Filter workouts by difficulty, type, or tags.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'type' => 'nullable|in:upper_body,lower_body,full_body,cardio,flexibility',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = Workout::query();

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->input('difficulty'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('tags')) {
            $query->whereJsonContains('tags', $request->input('tags'));
        }

        $workouts = $query->get();

        return response()->json($workouts, 200);
    }
}