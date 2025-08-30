<?php

namespace App\Http\Controllers;

use App\Models\UserWorkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserWorkoutController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            $query = UserWorkout::with(['workout', 'user'])
                ->where('user_id', $user->id);
            
            // Filter by completion status
            if ($request->has('completed') && $request->input('completed') !== null) {
                if ($request->input('completed') == 'true') {
                    $query->whereNotNull('completed_at');
                } else {
                    $query->whereNull('completed_at');
                }
            }
            
            // Filter by workout ID
            if ($request->has('workout_id') && $request->input('workout_id')) {
                $query->where('workout_id', $request->input('workout_id'));
            }
            
            $userWorkouts = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $userWorkouts,
                'count' => $userWorkouts->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user workouts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $userWorkout = UserWorkout::with(['workout.exercises' => function($q) {
                $q->orderBy('order');
            }, 'user'])
                ->where('user_id', $user->id)
                ->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $userWorkout
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User workout session not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user workout session',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $userWorkout = UserWorkout::where('user_id', $user->id)->findOrFail($id);
            $userWorkout->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'User workout session deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User workout session not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user workout session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's workout statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        try {
            $user = $request->user();
            $period = $request->input('period', 'all'); // all, week, month, year
            
            $query = UserWorkout::where('user_id', $user->id)
                ->whereNotNull('completed_at');
            
            // Apply period filter
            switch ($period) {
                case 'week':
                    $query->where('completed_at', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('completed_at', '>=', now()->startOfMonth());
                    break;
                case 'year':
                    $query->where('completed_at', '>=', now()->startOfYear());
                    break;
            }
            
            $completedWorkouts = $query->get();
            
            $stats = [
                'total_workouts_completed' => $completedWorkouts->count(),
                'total_calories_burned' => $completedWorkouts->sum('calories_burned'),
                'average_rating' => $completedWorkouts->where('rating', '>', 0)->avg('rating'),
                'total_workout_time' => $completedWorkouts->sum(function($workout) {
                    if ($workout->started_at && $workout->completed_at) {
                        return $workout->started_at->diffInMinutes($workout->completed_at);
                    }
                    return 0;
                }),
                'period' => $period,
                'most_completed_workout' => $this->getMostCompletedWorkout($user->id, $period)
            ];
            
            return response()->json([
                'status' => 'success',
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workout statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's active workout sessions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveSessions(Request $request)
    {
        try {
            $user = $request->user();
            
            $activeSessions = UserWorkout::with(['workout.exercises' => function($q) {
                $q->orderBy('order');
            }])
                ->where('user_id', $user->id)
                ->whereNull('completed_at')
                ->orderBy('started_at', 'desc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $activeSessions,
                'count' => $activeSessions->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve active workout sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's workout history with pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->input('per_page', 10);
            
            $history = UserWorkout::with(['workout'])
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                    'last_page' => $history->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workout history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get most completed workout
     */
    private function getMostCompletedWorkout($userId, $period)
    {
        $query = UserWorkout::with('workout')
            ->where('user_id', $userId)
            ->whereNotNull('completed_at');
        
        // Apply period filter
        switch ($period) {
            case 'week':
                $query->where('completed_at', '>=', now()->startOfWeek());
                break;
            case 'month':
                $query->where('completed_at', '>=', now()->startOfMonth());
                break;
            case 'year':
                $query->where('completed_at', '>=', now()->startOfYear());
                break;
        }
        
        $mostCompleted = $query->selectRaw('workout_id, COUNT(*) as count')
            ->groupBy('workout_id')
            ->orderBy('count', 'desc')
            ->first();
        
        if ($mostCompleted) {
            $workout = \App\Models\Workout::find($mostCompleted->workout_id);
            return [
                'workout' => $workout,
                'completion_count' => $mostCompleted->count
            ];
        }
        
        return null;
    }
}