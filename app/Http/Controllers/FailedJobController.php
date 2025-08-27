<?php

namespace App\Http\Controllers;

use App\Models\FailedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FailedJobController extends Controller
{
    public function index()
    {
        return response()->json(FailedJob::all(), 200);
    }

    public function show($id)
    {
        $job = FailedJob::findOrFail($id);
        return response()->json($job, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string|unique:failed_jobs',
            'connection' => 'required|string',
            'queue' => 'required|string',
            'payload' => 'required|string',
            'exception' => 'required|string',
            'failed_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job = FailedJob::create($request->all());
        return response()->json($job, 201);
    }

    public function update(Request $request, $id)
    {
        $job = FailedJob::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'uuid' => 'sometimes|string|unique:failed_jobs,uuid,'.$id,
            'connection' => 'sometimes|string',
            'queue' => 'sometimes|string',
            'payload' => 'sometimes|string',
            'exception' => 'sometimes|string',
            'failed_at' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $job->update($request->all());
        return response()->json($job, 200);
    }

    public function destroy($id)
    {
        $job = FailedJob::findOrFail($id);
        $job->delete();
        return response()->json(null, 204);
    }
}