<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Video::where('is_active', true);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $videos = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $videos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            'category' => 'required|in:exercise,workout,tutorial,other',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('video');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;
            
            // Store the video
            $path = $file->storeAs('', $filename, 'videos');
            $url = Storage::disk('videos')->url($path);

            // Create video record
            $video = Video::create([
                'title' => $request->title,
                'description' => $request->description,
                'filename' => $originalName,
                'path' => $path,
                'url' => $url,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'tags' => $request->tags,
                'uploaded_by' => auth()->id(), // Assuming admin is logged in
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully',
                'data' => $video,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return response()->json([
            'success' => true,
            'data' => $video,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|in:exercise,workout,tutorial,other',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $video->update($request->only([
            'title', 'description', 'category', 'tags', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Video updated successfully',
            'data' => $video,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        try {
            // Delete the video file
            Storage::disk('videos')->delete($video->path);
            
            // Delete thumbnail if exists
            if ($video->thumbnail_path) {
                Storage::disk('videos')->delete($video->thumbnail_path);
            }

            // Delete the record
            $video->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Serve video file for streaming
     */
    public function stream(Video $video)
    {
        if (!$video->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Video not available',
            ], 404);
        }

        $path = Storage::disk('videos')->path($video->path);
        
        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Video file not found',
            ], 404);
        }

        return response()->file($path, [
            'Content-Type' => $video->mime_type,
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
