<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Exercise::with('workout');
            
            // Filter by workout if provided
            if ($request->has('workout_id') && $request->input('workout_id')) {
                $query->where('workout_id', $request->input('workout_id'));
            }
            
            $exercises = $query->orderBy('order')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $exercises,
                'count' => $exercises->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve exercises',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $exercise = Exercise::with('workout')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $exercise
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve exercise',
                'error' => $e->getMessage()
            ], 500);
        }
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
        try {
            $exercise = Exercise::findOrFail($id);
            $exercise->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Exercise deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete exercise',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exercises for a specific workout
     *
     * @param int $workoutId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkoutExercises($workoutId)
    {
        try {
            $workout = Workout::findOrFail($workoutId);
            
            $exercises = Exercise::where('workout_id', $workoutId)
                ->orderBy('order')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'workout' => $workout,
                    'exercises' => $exercises,
                    'total_exercises' => $exercises->count(),
                    'total_sets' => $exercises->sum('sets'),
                    'estimated_duration' => $exercises->sum(function($exercise) {
                        return ($exercise->duration_seconds ?? 0) + ($exercise->rest_seconds ?? 0);
                    })
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Workout not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workout exercises',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the next exercise in a workout
     *
     * @param int $workoutId
     * @param int $currentExerciseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNextExercise($workoutId, $currentExerciseId = null)
    {
        try {
            $query = Exercise::where('workout_id', $workoutId)->orderBy('order');
            
            if ($currentExerciseId) {
                $currentExercise = Exercise::findOrFail($currentExerciseId);
                $query->where('order', '>', $currentExercise->order);
            }
            
            $nextExercise = $query->first();
            
            if (!$nextExercise) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No more exercises in this workout',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => $nextExercise
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get next exercise',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the previous exercise in a workout
     *
     * @param int $workoutId
     * @param int $currentExerciseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousExercise($workoutId, $currentExerciseId)
    {
        try {
            $currentExercise = Exercise::findOrFail($currentExerciseId);
            
            $previousExercise = Exercise::where('workout_id', $workoutId)
                ->where('order', '<', $currentExercise->order)
                ->orderBy('order', 'desc')
                ->first();
            
            if (!$previousExercise) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No previous exercises in this workout',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => $previousExercise
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exercise not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get previous exercise',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update exercise progress for a user workout session
     *
     * @param Request $request
     * @param int $sessionId
     * @param int $exerciseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateExerciseProgress(Request $request, $sessionId, $exerciseId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sets_completed' => 'nullable|integer|min:0',
                'reps_completed' => 'nullable|integer|min:0',
                'duration_completed' => 'nullable|integer|min:0',
                'is_completed' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid data provided',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $session = $user->userWorkouts()->findOrFail($sessionId);
            $exercise = Exercise::findOrFail($exerciseId);

            // Update exercise progress in session
            $progress = $session->exercise_progress ?? [];
            $progress[$exerciseId] = [
                'sets_completed' => $request->input('sets_completed', 0),
                'reps_completed' => $request->input('reps_completed', 0),
                'duration_completed' => $request->input('duration_completed', 0),
                'is_completed' => $request->input('is_completed', false),
                'updated_at' => now()->toISOString()
            ];

            $session->update(['exercise_progress' => $progress]);

            return response()->json([
                'status' => 'success',
                'message' => 'Exercise progress updated',
                'data' => [
                    'session' => $session,
                    'exercise_progress' => $progress[$exerciseId]
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session or exercise not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update exercise progress',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}