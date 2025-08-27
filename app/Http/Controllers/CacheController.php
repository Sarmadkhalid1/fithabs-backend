<?php

namespace App\Http\Controllers;

use App\Models\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CacheController extends Controller
{
    public function index()
    {
        return response()->json(Cache::all(), 200);
    }

    public function show($key)
    {
        $cache = Cache::findOrFail($key);
        return response()->json($cache, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|unique:cache',
            'value' => 'required|string',
            'expiration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cache = Cache::create($request->all());
        return response()->json($cache, 201);
    }

    public function update(Request $request, $key)
    {
        $cache = Cache::findOrFail($key);

        $validator = Validator::make($request->all(), [
            'value' => 'sometimes|string',
            'expiration' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cache->update($request->all());
        return response()->json($cache, 200);
    }

    public function destroy($key)
    {
        $cache = Cache::findOrFail($key);
        $cache->delete();
        return response()->json(null, 204);
    }
}