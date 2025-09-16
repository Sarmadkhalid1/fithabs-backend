<?php

namespace App\Http\Controllers;

use App\Models\EducationContent;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EducationContentController extends Controller
{
    public function index()
    {
        try {
            $contents = EducationContent::where('is_active', true)->get();
            
            // Transform data to match the desired structure
            $transformedContents = $contents->map(function ($content) {
                return [
                    'id' => $content->id,
                    'coverImage' => $content->image_url,
                    'title' => $content->title,
                    'description' => $content->description,
                    'sections' => $content->sections,
                    'category' => $content->category,
                    'tags' => $content->tags,
                    'is_featured' => $content->is_featured,
                    'created_at' => $content->created_at,
                    'updated_at' => $content->updated_at,
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'data' => $transformedContents,
                'count' => $transformedContents->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve education contents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $content = EducationContent::findOrFail($id);
            
            // Transform single content to match the desired structure
            $transformedContent = [
                'id' => $content->id,
                'coverImage' => $content->image_url,
                'title' => $content->title,
                'description' => $content->description,
                'sections' => $content->sections,
                'category' => $content->category,
                'tags' => $content->tags,
                'is_featured' => $content->is_featured,
                'created_at' => $content->created_at,
                'updated_at' => $content->updated_at,
            ];
            
            return response()->json([
                'status' => 'success',
                'data' => $transformedContent
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Education content not found',
                'error' => 'No education content found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve education content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string|url',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'sections' => 'required|array|min:1',
            'sections.*.heading' => 'required|string|max:255',
            'sections.*.content' => 'required|string',
            'category' => 'required|in:training,nutrition,wellness,recovery,mental_health',
            'tags' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['image']);
            $coverImageUrl = $request->input('image_url');

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $coverImageUrl = $this->handleImageUpload($request->file('image'), 'education');
            }

            $data['image_url'] = $coverImageUrl;
            $data['created_by_admin'] = auth()->id() ?? 1; // Default to admin ID 1 if not authenticated
            
            $content = EducationContent::create($data);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Education content created successfully',
                'data' => [
                    'id' => $content->id,
                    'coverImage' => $content->image_url,
                    'title' => $content->title,
                    'description' => $content->description,
                    'sections' => $content->sections,
                    'category' => $content->category,
                    'tags' => $content->tags,
                    'is_featured' => $content->is_featured,
                    'created_at' => $content->created_at,
                    'updated_at' => $content->updated_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create education content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $content = EducationContent::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'created_by_admin' => 'sometimes|exists:admin_users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string|url',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'sections' => 'sometimes|array',
            'sections.*.heading' => 'required_with:sections|string|max:255',
            'sections.*.content' => 'required_with:sections|string',
            'category' => 'sometimes|in:training,nutrition,wellness,recovery,mental_health',
            'tags' => 'nullable|array',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->except(['image']);
            
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $coverImageUrl = $this->handleImageUpload($request->file('image'), 'education');
                $updateData['image_url'] = $coverImageUrl;
            }

            $content->update($updateData);
            return response()->json([
                'status' => 'success',
                'message' => 'Education content updated successfully',
                'data' => $content
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update education content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $content = EducationContent::findOrFail($id);
            $content->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Education content deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Education content not found',
                'error' => 'No education content found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete education content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search education content by category or tags.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|in:training,nutrition,wellness,recovery,mental_health',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = EducationContent::query();

            if ($request->has('category')) {
                $query->where('category', $request->input('category'));
            }

            if ($request->has('tags')) {
                $query->whereJsonContains('tags', $request->input('tags'));
            }

            $contents = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $contents,
                'count' => $contents->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search education content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle image upload and return the URL
     */
    private function handleImageUpload($file, $category = 'other')
    {
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
        Image::create([
            'title' => $originalName,
            'description' => 'Uploaded image for ' . $category,
            'filename' => $originalName,
            'path' => $path,
            'url' => $url,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'category' => $category,
            'uploaded_by' => auth()->id(),
        ]);

        return $url;
    }
}