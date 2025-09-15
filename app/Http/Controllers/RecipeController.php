<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipeController extends Controller
{
    public function index()
    {
        try {
            $recipes = Recipe::all();
            return response()->json([
                'status' => 'success',
                'data' => $recipes,
                'count' => $recipes->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recipes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $recipe
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
                'error' => 'No recipe found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'prep_time_minutes' => 'nullable|integer|min:0',
            'cook_time_minutes' => 'nullable|integer|min:0',
            'servings' => 'integer|min:1',
            'calories_per_serving' => 'required|integer|min:0',
            'protein_per_serving' => 'nullable|numeric|min:0',
            'carbs_per_serving' => 'nullable|numeric|min:0',
            'fat_per_serving' => 'nullable|numeric|min:0',
            'fiber_per_serving' => 'nullable|numeric|min:0',
            'sugar_per_serving' => 'nullable|numeric|min:0',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'dietary_tags' => 'nullable|array',
            'allergen_info' => 'nullable|array',
            'difficulty' => 'required|in:easy,medium,hard',
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

        try {
            $recipeData = $request->except(['image']);
            $imageUrl = $request->input('image_url');

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imageUrl = $this->handleImageUpload($request->file('image'), 'recipe');
            }

            $recipeData['image_url'] = $imageUrl;
            $recipe = Recipe::create($recipeData);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Recipe created successfully',
                'data' => $recipe
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'meal_type' => 'sometimes|in:breakfast,lunch,dinner,snack',
            'prep_time_minutes' => 'nullable|integer|min:0',
            'cook_time_minutes' => 'nullable|integer|min:0',
            'servings' => 'sometimes|integer|min:1',
            'calories_per_serving' => 'sometimes|integer|min:0',
            'protein_per_serving' => 'nullable|numeric|min:0',
            'carbs_per_serving' => 'nullable|numeric|min:0',
            'fat_per_serving' => 'nullable|numeric|min:0',
            'fiber_per_serving' => 'nullable|numeric|min:0',
            'sugar_per_serving' => 'nullable|numeric|min:0',
            'ingredients' => 'sometimes|string',
            'instructions' => 'sometimes|string',
            'dietary_tags' => 'nullable|array',
            'allergen_info' => 'nullable|array',
            'difficulty' => 'sometimes|in:easy,medium,hard',
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
                $imageUrl = $this->handleImageUpload($request->file('image'), 'recipe');
                $updateData['image_url'] = $imageUrl;
            }

            $recipe->update($updateData);
            return response()->json([
                'status' => 'success',
                'message' => 'Recipe updated successfully',
                'data' => $recipe
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Recipe deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found',
                'error' => 'No recipe found with the specified ID'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search recipes by name, meal type, dietary tags, or allergen info.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'nullable|string',
            'meal_type' => 'nullable|in:breakfast,lunch,dinner,snack',
            'dietary_tags' => 'nullable|array',
            'allergen_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = Recipe::query();

            if ($request->has('query')) {
                $query->where('name', 'like', '%' . $request->input('query') . '%')
                      ->orWhere('description', 'like', '%' . $request->input('query') . '%');
            }

            if ($request->has('meal_type')) {
                $query->where('meal_type', $request->input('meal_type'));
            }

            if ($request->has('dietary_tags')) {
                $query->whereJsonContains('dietary_tags', $request->input('dietary_tags'));
            }

            if ($request->has('allergen_info')) {
                $query->whereJsonContains('allergen_info', $request->input('allergen_info'));
            }

            $recipes = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $recipes,
                'count' => $recipes->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search recipes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recipe of the day
     */
    public function recipeOfTheDay()
    {
        try {
            // Get a random featured recipe
            $recipeOfTheDay = Recipe::where('is_featured', true)
                                   ->where('is_active', true)
                                   ->inRandomOrder()
                                   ->first();

            if (!$recipeOfTheDay) {
                // Fallback to any active recipe
                $recipeOfTheDay = Recipe::where('is_active', true)
                                       ->inRandomOrder()
                                       ->first();
            }

            if (!$recipeOfTheDay) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No recipe of the day available'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $recipeOfTheDay->id,
                    'name' => $recipeOfTheDay->name,
                    'image_url' => $recipeOfTheDay->image_url,
                    'calories_per_serving' => $recipeOfTheDay->calories_per_serving,
                    'meal_type' => $recipeOfTheDay->meal_type,
                    'is_favorite' => false, // TODO: Implement user favorites
                    'tag' => 'Recipe of the day',
                    'detail_url' => "/api/v1/recipes/{$recipeOfTheDay->id}"
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get recipe of the day',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recommended recipes for nutrition screen
     */
    public function recommendations()
    {
        try {
            // Get recommended recipes based on different criteria
            $recommendations = [];

            // Section 1: Quick & Healthy (2 recipes)
            $quickHealthy = Recipe::where('is_active', true)
                                  ->where('difficulty', 'easy')
                                  ->where('calories_per_serving', '<=', 300)
                                  ->inRandomOrder()
                                  ->limit(2)
                                  ->get()
                                  ->map(function($recipe) {
                                      return [
                                          'id' => $recipe->id,
                                          'name' => $recipe->name,
                                          'image_url' => $recipe->image_url,
                                          'calories_per_serving' => $recipe->calories_per_serving,
                                          'meal_type' => $recipe->meal_type,
                                          'detail_url' => "/api/v1/recipes/{$recipe->id}"
                                      ];
                                  });

            // Section 2: Featured & Popular (3 recipes)
            $featuredPopular = Recipe::where('is_active', true)
                                     ->where('is_featured', true)
                                     ->inRandomOrder()
                                     ->limit(3)
                                     ->get()
                                     ->map(function($recipe) {
                                         return [
                                             'id' => $recipe->id,
                                             'name' => $recipe->name,
                                             'image_url' => $recipe->image_url,
                                             'calories_per_serving' => $recipe->calories_per_serving,
                                             'meal_type' => $recipe->meal_type,
                                             'detail_url' => "/api/v1/recipes/{$recipe->id}"
                                         ];
                                     });

            $recommendations = [
                'section_1' => $quickHealthy,
                'section_2' => $featuredPopular
            ];

            return response()->json([
                'status' => 'success',
                'data' => $recommendations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get recommendations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get nutrition screen data (recipe of the day + recommendations)
     */
    public function nutritionScreen()
    {
        try {
            // Get recipe of the day
            $recipeOfTheDay = Recipe::where('is_featured', true)
                                   ->where('is_active', true)
                                   ->inRandomOrder()
                                   ->first();

            if (!$recipeOfTheDay) {
                $recipeOfTheDay = Recipe::where('is_active', true)
                                       ->inRandomOrder()
                                       ->first();
            }

            // Get recommendations
            $quickHealthy = Recipe::where('is_active', true)
                                  ->where('difficulty', 'easy')
                                  ->where('calories_per_serving', '<=', 300)
                                  ->inRandomOrder()
                                  ->limit(2)
                                  ->get()
                                  ->map(function($recipe) {
                                      return [
                                          'id' => $recipe->id,
                                          'name' => $recipe->name,
                                          'image_url' => $recipe->image_url,
                                          'calories_per_serving' => $recipe->calories_per_serving,
                                          'meal_type' => $recipe->meal_type
                                      ];
                                  });

            $featuredPopular = Recipe::where('is_active', true)
                                     ->where('is_featured', true)
                                     ->inRandomOrder()
                                     ->limit(3)
                                     ->get()
                                     ->map(function($recipe) {
                                         return [
                                             'id' => $recipe->id,
                                             'name' => $recipe->name,
                                             'image_url' => $recipe->image_url,
                                             'calories_per_serving' => $recipe->calories_per_serving,
                                             'meal_type' => $recipe->meal_type
                                         ];
                                     });

            $nutritionData = [
                'recipe_of_the_day' => $recipeOfTheDay ? [
                    'id' => $recipeOfTheDay->id,
                    'name' => $recipeOfTheDay->name,
                    'image_url' => $recipeOfTheDay->image_url,
                    'calories_per_serving' => $recipeOfTheDay->calories_per_serving,
                    'meal_type' => $recipeOfTheDay->meal_type,
                    'is_favorite' => false,
                    'tag' => 'Recipe of the day',
                    'detail_url' => "/api/v1/recipes/{$recipeOfTheDay->id}"
                ] : null,
                'recommendations' => [
                    'section_1' => $quickHealthy,
                    'section_2' => $featuredPopular
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $nutritionData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get nutrition screen data',
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