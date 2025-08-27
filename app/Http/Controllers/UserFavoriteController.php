<?php

namespace App\Http\Controllers;

use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFavoriteController extends Controller
{
    public function index()
    {
        return response()->json(UserFavorite::all(), 200);
    }

    public function show($id)
    {
        $favorite = UserFavorite::findOrFail($id);
        return response()->json($favorite, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'favoritable_type' => 'required|in:workout,recipe,education_content,meal_plan',
            'favoritable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $favorite = UserFavorite::create($request->all());
        return response()->json($favorite, 201);
    }

    public function destroy($id)
    {
        $favorite = UserFavorite::findOrFail($id);
        $favorite->delete();
        return response()->json(null, 204);
    }
}