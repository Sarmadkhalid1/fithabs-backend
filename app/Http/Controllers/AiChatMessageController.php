<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiChatMessageController extends Controller
{
    public function index()
    {
        return response()->json(AiChatMessage::all(), 200);
    }

    public function show($id)
    {
        $message = AiChatMessage::findOrFail($id);
        return response()->json($message, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ai_chat_id' => 'required|exists:ai_chats,id',
            'role' => 'required|in:user,assistant,system',
            'content' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $message = AiChatMessage::create($request->all());
        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = AiChatMessage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ai_chat_id' => 'sometimes|exists:ai_chats,id',
            'role' => 'sometimes|in:user,assistant,system',
            'content' => 'sometimes|string',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $message->update($request->all());
        return response()->json($message, 200);
    }

    public function destroy($id)
    {
        $message = AiChatMessage::findOrFail($id);
        $message->delete();
        return response()->json(null, 204);
    }
}