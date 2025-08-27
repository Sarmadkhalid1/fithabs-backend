<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index()
    {
        return response()->json(Job::all(), 200);
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return response()->json($job, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'queue' => 'required|string',
            'payload' => 'required|string',
            'attempts' => 'required|integer|min:0',
            'reserved_at' => 'nullable|integer',
            'available_at' => 'required|integer',
            'created_at' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job = Job::create($request->all());
        return response()->json($job, 201);
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'queue' => 'sometimes|string',
            'payload' => 'sometimes|string',
            'attempts' => 'sometimes|integer|min:0',
            'reserved_at' => 'nullable|integer',
            'available_at' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job->update($request->all());
        return response()->json($job, 200);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return response()->json(null, 204);
    }
}