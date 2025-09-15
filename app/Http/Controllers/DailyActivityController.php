<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DailyActivityController extends Controller
{
    /**
     * Get progress summary for authenticated user
     */
    public function getProgressSummary(Request $request)
    {
        try {
            $user = $request->user();
            $date = $request->query('date', Carbon::today()->format('Y-m-d'));
            
            // Get or create daily activity for the date
            $activity = DailyActivity::firstOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                [
                    'steps' => 0,
                    'calories_consumed' => 0,
                    'calories_burned' => 0,
                    'water_intake' => 0,
                    'sleep_time' => 0,
                    'daily_progress_percentage' => 0,
                    'protein_goal' => $user->daily_calorie_goal ?? 2000,
                    'carbs_goal' => $user->daily_calorie_goal ?? 2000,
                ]
            );

            // Calculate progress percentages
            $caloriesProgress = $activity->protein_goal > 0 ? 
                min(100, ($activity->calories_consumed / $activity->protein_goal) * 100) : 0;
            
            $proteinProgress = $activity->protein_goal > 0 ? 
                min(100, ($activity->calories_consumed / $activity->protein_goal) * 100) : 0;
            
            $carbsProgress = $activity->carbs_goal > 0 ? 
                min(100, ($activity->calories_consumed / $activity->carbs_goal) * 100) : 0;

            $sleepGoal = 8; // Default sleep goal
            $sleepProgress = min(100, ($activity->sleep_time / $sleepGoal) * 100);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'date' => $date,
                    'daily_progress' => [
                        'percentage' => $activity->daily_progress_percentage,
                        'description' => 'Accumulating daily report'
                    ],
                    'steps' => [
                        'current' => $activity->steps,
                        'goal' => $user->daily_steps_goal ?? 10000,
                        'progress_percentage' => $user->daily_steps_goal > 0 ? 
                            min(100, ($activity->steps / $user->daily_steps_goal) * 100) : 0
                    ],
                    'calories_burned' => [
                        'current' => $activity->calories_burned,
                        'goal' => $user->daily_calorie_goal ?? 2000,
                        'progress_percentage' => $user->daily_calorie_goal > 0 ? 
                            min(100, ($activity->calories_burned / $user->daily_calorie_goal) * 100) : 0
                    ],
                    'sleep' => [
                        'current_hours' => $activity->sleep_time,
                        'goal_hours' => $sleepGoal,
                        'progress_percentage' => $sleepProgress
                    ],
                    'calories_goal' => [
                        'current' => $activity->calories_consumed,
                        'goal' => $activity->protein_goal,
                        'progress_percentage' => $caloriesProgress
                    ],
                    'protein_goal' => [
                        'current' => $activity->calories_consumed, // Using calories as proxy
                        'goal' => $activity->protein_goal,
                        'progress_percentage' => $proteinProgress
                    ],
                    'carbs_goal' => [
                        'current' => $activity->calories_consumed, // Using calories as proxy
                        'goal' => $activity->carbs_goal,
                        'progress_percentage' => $carbsProgress
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve progress summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update daily activity for authenticated user
     */
    public function updateDailyActivity(Request $request)
    {
        try {
            $user = $request->user();
            $date = $request->input('date', Carbon::today()->format('Y-m-d'));
            
            $validator = Validator::make($request->all(), [
                'date' => 'sometimes|date',
                'steps' => 'sometimes|integer|min:0',
                'calories_consumed' => 'sometimes|integer|min:0',
                'calories_burned' => 'sometimes|integer|min:0',
                'water_intake' => 'sometimes|numeric|min:0',
                'sleep_time' => 'sometimes|numeric|min:0|max:24',
                'daily_progress_percentage' => 'sometimes|numeric|min:0|max:100',
                'protein_goal' => 'sometimes|integer|min:0',
                'carbs_goal' => 'sometimes|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $activity = DailyActivity::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                $request->only([
                    'steps', 'calories_consumed', 'calories_burned', 
                    'water_intake', 'sleep_time', 'daily_progress_percentage',
                    'protein_goal', 'carbs_goal'
                ])
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Daily activity updated successfully',
                'data' => $activity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update daily activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's daily activities for a date range
     */
    public function getDateRange(Request $request)
    {
        try {
            $user = $request->user();
            $startDate = $request->query('start_date', Carbon::today()->subDays(6)->format('Y-m-d'));
            $endDate = $request->query('end_date', Carbon::today()->format('Y-m-d'));
            
            $activities = DailyActivity::where('user_id', $user->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $activities,
                'count' => $activities->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve daily activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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