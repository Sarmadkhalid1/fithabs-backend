<?php

namespace App\Http\Controllers;

use App\Models\JobBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobBatchController extends Controller
{
    public function index()
    {
        return response()->json(JobBatch::all(), 200);
    }

    public function show($id)
    {
        $batch = JobBatch::findOrFail($id);
        return response()->json($batch, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|unique:job_batches',
            'name' => 'required|string',
            'total_jobs' => 'required|integer|min:0',
            'pending_jobs' => 'required|integer|min:0',
            'failed_jobs' => 'required|integer|min:0',
            'failed_job_ids' => 'required|string',
            'options' => 'nullable|string',
            'cancelled_at' => 'nullable|integer',
            'created_at' => 'required|integer',
            'finished_at' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $batch = JobBatch::create($request->all());
        return response()->json($batch, 201);
    }

    public function update(Request $request, $id)
    {
        $batch = JobBatch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'total_jobs' => 'sometimes|integer|min:0',
            'pending_jobs' => 'sometimes|integer|min:0',
            'failed_jobs' => 'sometimes|integer|min:0',
            'failed_job_ids' => 'sometimes|string',
            'options' => 'nullable|string',
            'cancelled_at' => 'nullable|integer',
            'finished_at' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $batch->update($request->all());
        return response()->json($batch, 200);
    }

    public function destroy($id)
    {
        $batch = JobBatch::findOrFail($id);
        $batch->delete();
        return response()->json(null, 204);
    }
}