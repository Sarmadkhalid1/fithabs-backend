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
        return response()->json(User::all(), 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
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
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:8',
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
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only([
            'name', 'email', 'gender', 'weight', 'weight_unit', 'height',
            'height_unit', 'goal', 'activity_level', 'daily_calorie_goal',
            'daily_steps_goal', 'daily_water_goal'
        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}