<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        return response()->json(Chat::all(), 200);
    }

    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        return response()->json($chat, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'professional_type' => 'required|in:coach,clinic,therapist',
            'professional_id' => 'required|integer',
            'chat_title' => 'nullable|string',
            'is_active' => 'boolean',
            'last_message_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chat = Chat::create($request->all());
        return response()->json($chat, 201);
    }

    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'professional_type' => 'sometimes|in:coach,clinic,therapist',
            'professional_id' => 'sometimes|integer',
            'chat_title' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'last_message_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chat->update($request->all());
        return response()->json($chat, 200);
    }

    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();
        return response()->json(null, 204);
    }
}