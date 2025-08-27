<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalAccessTokenController extends Controller
{
    public function index()
    {
        return response()->json(PersonalAccessToken::all(), 200);
    }

    public function show($id)
    {
        $token = PersonalAccessToken::findOrFail($id);
        return response()->json($token, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tokenable_type' => 'required|string',
            'tokenable_id' => 'required|integer',
            'name' => 'required|string',
            'token' => 'required|string|unique:personal_access_tokens|size:64',
            'abilities' => 'nullable|string',
            'last_used_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = PersonalAccessToken::create($request->all());
        return response()->json($token, 201);
    }

    public function update(Request $request, $id)
    {
        $token = PersonalAccessToken::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'abilities' => 'nullable|string',
            'last_used_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token->update($request->all());
        return response()->json($token, 200);
    }

    public function destroy($id)
    {
        $token = PersonalAccessToken::findOrFail($id);
        $token->delete();
        return response()->json(null, 204);
    }
}