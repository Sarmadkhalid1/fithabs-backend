<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProgressController extends Controller
{
    public function index()
    {
        return response()->json(UserProgress::all(), 200);
    }

    public function show($id)
    {
        $progress = UserProgress::findOrFail($id);
        return response()->json($progress, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'weight' => 'nullable|numeric|min:0',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'measurements' => 'nullable|array',
            'progress_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $progress = UserProgress::create($request->all());
        return response()->json($progress, 201);
    }

    public function update(Request $request, $id)
    {
        $progress = UserProgress::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'weight' => 'nullable|numeric|min:0',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'measurements' => 'nullable|array',
            'progress_image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $progress->update($request->all());
        return response()->json($progress, 200);
    }

    public function destroy($id)
    {
        $progress = UserProgress::findOrFail($id);
        $progress->delete();
        return response()->json(null, 204);
    }
}