<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkoutController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Workout::with(['exercises' => function($q) {
                $q->orderBy('order');
            }])->where('is_active', true);
            
            // Filter by difficulty if provided
            if ($request->has('difficulty') && $request->input('difficulty')) {
                $query->where('difficulty', $request->input('difficulty'));
            }
            
            // Filter by type if provided
            if ($request->has('type') && $request->input('type')) {
                $query->where('type', $request->input('type'));
            }
            
            $workouts = $query->get();
            
            // Add computed fields
            $workouts->each(function($workout) {
                $workout->total_exercises = $workout->exercises->count();
                $workout->total_sets = $workout->exercises->sum('sets');
            });
            
            return response()->json([
                'status' => 'success',
                'data' => $workouts,
                'count' => $workouts->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workouts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $workout = Workout::with(['exercises' => function($q) {
                $q->orderBy('order');
            }])->findOrFail($id);
            
            // Add computed fields
            $workout->total_exercises = $workout->exercises->count();
            $workout->total_sets = $workout->exercises->sum('sets');
            $workout->estimated_duration = $workout->exercises->sum(function($exercise) {
                return ($exercise->duration_seconds ?? 0) + ($exercise->rest_seconds ?? 0);
            });
            
            return response()->json([
                'status' => 'success',
                'data' => $workout
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Workout not found',
                'error' => 'No workout found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image_url' => 'nullable|string',
                'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
                'difficulty' => 'required|in:beginner,intermediate,advanced',
                'type' => 'required|in:upper_body,lower_body,full_body,cardio,flexibility',
                'duration_minutes' => 'nullable|integer|min:0',
                'calories_per_session' => 'nullable|integer|min:0',
                'equipment_needed' => 'nullable|array',
                'tags' => 'nullable|array',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'created_by_admin' => 'required|exists:admin_users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $workoutData = $request->except(['image']);
            $imageUrl = $request->input('image_url');

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imageUrl = $this->handleImageUpload($request->file('image'), 'workout');
            }

            $workoutData['image_url'] = $imageUrl;
            $workout = Workout::create($workoutData);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Workout created successfully',
                'data' => $workout
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create workout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $workout = Workout::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'difficulty' => 'sometimes|in:beginner,intermediate,advanced',
            'type' => 'sometimes|in:upper_body,lower_body,full_body,cardio,flexibility',
            'duration_minutes' => 'nullable|integer|min:0',
            'calories_per_session' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'tags' => 'nullable|array',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'created_by_admin' => 'sometimes|exists:admin_users,id',
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
                $imageUrl = $this->handleImageUpload($request->file('image'), 'workout');
                $updateData['image_url'] = $imageUrl;
            }

            $workout->update($updateData);
            return response()->json([
                'status' => 'success',
                'message' => 'Workout updated successfully',
                'data' => $workout
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update workout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $workout = Workout::findOrFail($id);
            $workout->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Workout deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Workout not found',
                'error' => 'No workout found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete workout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter workouts by difficulty, type, or tags.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'type' => 'nullable|in:upper_body,lower_body,full_body,cardio,flexibility',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $query = Workout::query();

            if ($request->has('difficulty') && $request->input('difficulty')) {
                $query->where('difficulty', $request->input('difficulty'));
            }

            if ($request->has('type') && $request->input('type')) {
                $query->where('type', $request->input('type'));
            }

            if ($request->has('tags') && is_array($request->input('tags')) && !empty($request->input('tags'))) {
                // Handle tags filtering - check if any of the provided tags exist in the workout tags
                $tags = $request->input('tags');
                $query->where(function($q) use ($tags) {
                    foreach ($tags as $tag) {
                        $q->orWhereJsonContains('tags', $tag);
                    }
                });
            }

            $workouts = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $workouts,
                'count' => $workouts->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to filter workouts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workouts by difficulty level
     *
     * @param string $difficulty
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDifficulty($difficulty)
    {
        try {
            $validator = Validator::make(['difficulty' => $difficulty], [
                'difficulty' => 'required|in:beginner,intermediate,advanced'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid difficulty level',
                    'errors' => $validator->errors()
                ], 422);
            }

            $workouts = Workout::with(['exercises' => function($q) {
                $q->orderBy('order');
            }])
            ->where('difficulty', $difficulty)
            ->where('is_active', true)
            ->get();

            // Add computed fields
            $workouts->each(function($workout) {
                $workout->total_exercises = $workout->exercises->count();
                $workout->total_sets = $workout->exercises->sum('sets');
            });

            return response()->json([
                'status' => 'success',
                'data' => $workouts,
                'count' => $workouts->count(),
                'difficulty' => $difficulty
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve workouts by difficulty',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a workout session for a user
     *
     * @param Request $request
     * @param int $workoutId
     * @return \Illuminate\Http\JsonResponse
     */
    public function startWorkout(Request $request, $workoutId)
    {
        try {
            $workout = Workout::with(['exercises' => function($q) {
                $q->orderBy('order');
            }])->findOrFail($workoutId);

            $user = $request->user();

            // Check if user already has an active workout session
            $existingSession = $user->userWorkouts()
                ->where('workout_id', $workoutId)
                ->whereNull('completed_at')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Workout session already active',
                    'data' => [
                        'session' => $existingSession,
                        'workout' => $workout
                    ]
                ], 200);
            }

            // Create new workout session
            $session = $user->userWorkouts()->create([
                'workout_id' => $workoutId,
                'started_at' => now(),
                'exercise_progress' => []
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Workout session started',
                'data' => [
                    'session' => $session,
                    'workout' => $workout
                ]
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Workout not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to start workout session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a workout session
     *
     * @param Request $request
     * @param int $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeWorkout(Request $request, $sessionId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'calories_burned' => 'nullable|integer|min:0',
                'rating' => 'nullable|integer|min:1|max:5',
                'notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid data provided',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $session = $user->userWorkouts()->findOrFail($sessionId);

            $session->update([
                'completed_at' => now(),
                'calories_burned' => $request->input('calories_burned'),
                'rating' => $request->input('rating'),
                'notes' => $request->input('notes')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Workout completed successfully',
                'data' => $session->load('workout')
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Workout session not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete workout',
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