<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'data' => $users,
                'count' => $users->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'error' => 'No user found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'age' => 'nullable|integer|min:13|max:120',
            'gender' => 'nullable|in:male,female,other',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'required_with:weight|in:kg,lb',
            'height' => 'nullable|numeric|min:0',
            'height_unit' => 'required_with:height|in:cm,ft',
            'goal' => 'nullable|in:lose_weight,gain_weight,maintain_weight,build_muscle',
            'activity_level' => 'nullable|in:sedentary,light,moderate,very_active',
            'daily_calorie_goal' => 'nullable|integer|min:0',
            'daily_steps_goal' => 'nullable|integer|min:0',
            'daily_water_goal' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'weight_unit' => $request->weight_unit,
                'height' => $request->height,
                'height_unit' => $request->height_unit,
                'goal' => $request->goal,
                'activity_level' => $request->activity_level,
                'daily_calorie_goal' => $request->daily_calorie_goal,
                'daily_steps_goal' => $request->daily_steps_goal,
                'daily_water_goal' => $request->daily_water_goal,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'age' => 'sometimes|integer|min:13|max:120',
            'gender' => 'nullable|in:male,female,other',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'required_with:weight|in:kg,lb',
            'height' => 'nullable|numeric|min:0',
            'height_unit' => 'required_with:height|in:cm,ft',
            'goal' => 'nullable|in:lose_weight,gain_weight,maintain_weight,build_muscle',
            'activity_level' => 'nullable|in:sedentary,light,moderate,very_active',
            'daily_calorie_goal' => 'nullable|integer|min:0',
            'daily_steps_goal' => 'nullable|integer|min:0',
            'daily_water_goal' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only([
                'name', 'email', 'age', 'gender', 'weight', 'weight_unit', 'height',
                'height_unit', 'goal', 'activity_level', 'daily_calorie_goal',
                'daily_steps_goal', 'daily_water_goal'
            ]);

            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'error' => 'No user found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}