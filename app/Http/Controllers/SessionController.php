<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    public function index()
    {
        return response()->json(Session::all(), 200);
    }

    public function show($id)
    {
        $session = Session::findOrFail($id);
        return response()->json($session, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|unique:sessions',
            'user_id' => 'nullable|exists:users,id',
            'ip_address' => 'nullable|string|max:45',
            'user_agent' => 'nullable|string',
            'payload' => 'required|string',
            'last_activity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $session = Session::create($request->all());
        return response()->json($session, 201);
    }

    public function update(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'ip_address' => 'nullable|string|max:45',
            'user_agent' => 'nullable|string',
            'payload' => 'sometimes|string',
            'last_activity' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $session->update($request->all());
        return response()->json($session, 200);
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return response()->json(null, 204);
    }
}