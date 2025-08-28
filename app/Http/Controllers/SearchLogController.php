<?php

namespace App\Http\Controllers;

use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchLogController extends Controller
{
    public function index()
    {
        return response()->json(SearchLog::all(), 200);
    }

    public function show($id)
    {
        $searchLog = SearchLog::findOrFail($id);
        return response()->json($searchLog, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'search_query' => 'required|string',
            'search_type' => 'required|in:workouts,recipes,education,meal_plans,general',
            'filters_applied' => 'nullable|array',
            'results_count' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $searchLog = SearchLog::create($request->all());
        return response()->json($searchLog, 201);
    }

    public function update(Request $request, $id)
    {
        $searchLog = SearchLog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'search_query' => 'sometimes|string',
            'search_type' => 'sometimes|in:workouts,recipes,education,meal_plans,general',
            'filters_applied' => 'nullable|array',
            'results_count' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $searchLog->update($request->all());
        return response()->json($searchLog, 200);
    }

    public function destroy($id)
    {
        $searchLog = SearchLog::findOrFail($id);
        $searchLog->delete();
        return response()->json(null, 204);
    }

    /**
     * Aggregate recent searches by search type.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentSearches(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_type' => 'nullable|in:workouts,recipes,education,meal_plans,general',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = SearchLog::query()
            ->orderBy('created_at', 'desc')
            ->limit($request->input('limit', 10));

        if ($request->has('search_type')) {
            $query->where('search_type', $request->input('search_type'));
        }

        $searches = $query->get();

        return response()->json($searches, 200);
    }
}