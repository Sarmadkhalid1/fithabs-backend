<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function index()
    {
        return response()->json(Recipe::all(), 200);
    }

    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        return response()->json($recipe, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string',
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
            return response()->json($validator->errors(), 422);
        }

        $recipe = Recipe::create($request->all());
        return response()->json($recipe, 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image_url' => 'nullable|string',
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
            return response()->json($validator->errors(), 422);
        }

        $recipe->update($request->all());
        return response()->json($recipe, 200);
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return response()->json(null, 204);
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
            return response()->json($validator->errors(), 422);
        }

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

        return response()->json($recipes, 200);
    }
}