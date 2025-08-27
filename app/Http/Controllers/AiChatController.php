<?php

namespace App\Http\Controllers;

use App\Models\AiChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
    public function index()
    {
        return response()->json(AiChat::all(), 200);
    }

    public function show($id)
    {
        $aiChat = AiChat::findOrFail($id);
        return response()->json($aiChat, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'session_id' => 'required|string|unique:ai_chats',
            'title' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $aiChat = AiChat::create($request->all());
        return response()->json($aiChat, 201);
    }

    public function update(Request $request, $id)
    {
        $aiChat = AiChat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'session_id' => 'sometimes|string|unique:ai_chats,session_id,'.$id,
            'title' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $aiChat->update($request->all());
        return response()->json($aiChat, 200);
    }

    public function destroy($id)
    {
        $aiChat = AiChat::findOrFail($id);
        $aiChat->delete();
        return response()->json(null, 204);
    }
}