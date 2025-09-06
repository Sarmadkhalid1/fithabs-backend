<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CoachController extends Controller
{
    public function index()
    {
        try {
            $coaches = Coach::where('is_active', true)
                ->get()
                ->map(function($coach) {
                    return [
                        'id' => $coach->id,
                        'name' => $coach->name,
                        'bio' => $coach->bio,
                        'profile_image' => $coach->profile_image,
                        'specializations' => $coach->specializations,
                        'certifications' => $coach->certifications,
                        'phone' => $coach->phone,
                        'chat_url' => "/api/v1/coaches/{$coach->id}/chat"
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $coaches,
                'count' => $coaches->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve coaches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $coach = Coach::findOrFail($id);
        return response()->json($coach, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:coaches',
            'password' => 'required|string|min:8',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|string',
            'specializations' => 'nullable|array',
            'certifications' => 'nullable|array',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $coach = Coach::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'profile_image' => $request->profile_image,
            'specializations' => $request->specializations,
            'certifications' => $request->certifications,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($coach, 201);
    }

    public function update(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:coaches,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|string',
            'specializations' => 'nullable|array',
            'certifications' => 'nullable|array',
            'phone' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only([
            'name', 'email', 'bio', 'profile_image', 'specializations',
            'certifications', 'phone', 'is_active'
        ]);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $coach->update($data);
        return response()->json($coach, 200);
    }

    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        $coach->delete();
        return response()->json(null, 204);
    }
}