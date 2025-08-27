<?php

namespace App\Http\Controllers;

use App\Models\EducationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationContentController extends Controller
{
    public function index()
    {
        return response()->json(EducationContent::all(), 200);
    }

    public function show($id)
    {
        $content = EducationContent::findOrFail($id);
        return response()->json($content, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'created_by_admin' => 'required|exists:admin_users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
            'content' => 'required|string',
            'content_type' => 'required|in:article,video,infographic,guide',
            'video_url' => 'nullable|string|url',
            'category' => 'required|in:training,nutrition,wellness,recovery,mental_health',
            'tags' => 'nullable|array',
            'read_time_minutes' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $content = EducationContent::create($request->all());
        return response()->json($content, 201);
    }

    public function update(Request $request, $id)
    {
        $content = EducationContent::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'created_by_admin' => 'sometimes|exists:admin_users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string',
            'content' => 'sometimes|string',
            'content_type' => 'sometimes|in:article,video,infographic,guide',
            'video_url' => 'nullable|string|url',
            'category' => 'sometimes|in:training,nutrition,wellness,recovery,mental_health',
            'tags' => 'nullable|array',
            'read_time_minutes' => 'nullable|integer|min:0',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $content->update($request->all());
        return response()->json($content, 200);
    }

    public function destroy($id)
    {
        $content = EducationContent::findOrFail($id);
        $content->delete();
        return response()->json(null, 204);
    }
}