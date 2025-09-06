<?php

namespace App\Http\Controllers;

use App\Models\UserGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGoalController extends Controller
{
    /**
     * Get user's goals
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();
            $userGoal = $user->userGoal;

            if (!$userGoal) {
                return response()->json([
                    'status' => 'success',
                    'data' => null,
                    'message' => 'No goals set yet'
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => $userGoal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update user's goals
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'nullable|integer|min:1000|max:20000',
            'calories' => 'nullable|numeric|min:64|max:643',
            'water' => 'nullable|numeric|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            
            // Create or update user goals
            $userGoal = $user->userGoal()->updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['steps', 'calories', 'water'])
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Goals saved successfully',
                'data' => $userGoal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user's goals
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'nullable|integer|min:1000|max:20000',
            'calories' => 'nullable|numeric|min:64|max:643',
            'water' => 'nullable|numeric|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $userGoal = $user->userGoal;

            if (!$userGoal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No goals found. Please create goals first.'
                ], 404);
            }

            // Update only provided fields
            $updateData = array_filter($request->only(['steps', 'calories', 'water']), function($value) {
                return $value !== null;
            });

            $userGoal->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Goals updated successfully',
                'data' => $userGoal->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user's goals
     */
    public function destroy(Request $request)
    {
        try {
            $user = $request->user();
            $userGoal = $user->userGoal;

            if (!$userGoal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No goals found to delete'
                ], 404);
            }

            $userGoal->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Goals deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete goals',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}