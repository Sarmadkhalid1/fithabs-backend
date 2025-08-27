<?php

namespace App\Http\Controllers;

use App\Models\CacheLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CacheLockController extends Controller
{
    public function index()
    {
        return response()->json(CacheLock::all(), 200);
    }

    public function show($key)
    {
        $lock = CacheLock::findOrFail($key);
        return response()->json($lock, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|unique:cache_locks',
            'owner' => 'required|string',
            'expiration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lock = CacheLock::create($request->all());
        return response()->json($lock, 201);
    }

    public function update(Request $request, $key)
    {
        $lock = CacheLock::findOrFail($key);

        $validator = Validator::make($request->all(), [
            'owner' => 'sometimes|string',
            'expiration' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lock->update($request->all());
        return response()->json($lock, 200);
    }

    public function destroy($key)
    {
        $lock = CacheLock::findOrFail($key);
        $lock->delete();
        return response()->json(null, 204);
    }
}