<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImageIntervention;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Image::where('is_active', true);

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

        $images = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $images,
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
            'image' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'category' => 'required|in:workout,recipe,meal_plan,education,profile,other',
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
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename
            $filename = Str::uuid() . '.' . $extension;
            
            // Store the image
            $path = $file->storeAs('', $filename, 'images');
            
            // Generate the correct URL based on the current request
            $baseUrl = request()->getSchemeAndHttpHost();
            $url = $baseUrl . '/storage/images/' . $filename;

            // Get image dimensions
            $imageInfo = getimagesize($file->getPathname());
            $width = $imageInfo[0] ?? null;
            $height = $imageInfo[1] ?? null;

            // Create image record
            $image = Image::create([
                'title' => $request->title,
                'description' => $request->description,
                'filename' => $originalName,
                'path' => $path,
                'url' => $url,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'category' => $request->category,
                'tags' => $request->tags,
                'uploaded_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $image,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        return response()->json([
            'success' => true,
            'data' => $image,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|in:workout,recipe,meal_plan,education,profile,other',
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

        $image->update($request->only([
            'title', 'description', 'category', 'tags', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully',
            'data' => $image,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        try {
            // Delete the image file
            Storage::disk('images')->delete($image->path);

            // Delete the record
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Serve image file
     */
    public function serve(Image $image)
    {
        if (!$image->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Image not available',
            ], 404);
        }

        $path = Storage::disk('images')->path($image->path);
        
        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Image file not found',
            ], 404);
        }

        return response()->file($path, [
            'Content-Type' => $image->mime_type,
        ]);
    }
}