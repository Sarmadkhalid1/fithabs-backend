<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAchievement;

class UserAchievementController extends Controller
{
    public function index()
    {
        $achievements = UserAchievement::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($achievements, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'achievement_type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
        ]);

        $validated['user_id'] = auth()->id();
        
        $achievement = UserAchievement::create($validated);
        return response()->json($achievement, 201);
    }

    public function show($id)
    {
        $achievement = UserAchievement::where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($achievement, 200);
    }

    public function update(Request $request, $id)
    {
        $achievement = UserAchievement::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'points' => 'sometimes|integer|min:0',
        ]);

        $achievement->update($validated);
        return response()->json($achievement, 200);
    }

    public function destroy($id)
    {
        $achievement = UserAchievement::where('user_id', auth()->id())
            ->findOrFail($id);
        $achievement->delete();
        return response()->json(null, 204);
    }
}
