<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatMessageController extends Controller
{
    public function index()
    {
        return response()->json(ChatMessage::all(), 200);
    }

    public function show($id)
    {
        $message = ChatMessage::findOrFail($id);
        return response()->json($message, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|exists:chats,id',
            'sender_type' => 'required|in:user,professional',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'is_read' => 'boolean',
            'read_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $message = ChatMessage::create($request->all());
        return response()->json($message, 201);
    }

    public function update(Request $request, $id)
    {
        $message = ChatMessage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'chat_id' => 'sometimes|exists:chats,id',
            'sender_type' => 'sometimes|in:user,professional',
            'message' => 'sometimes|string',
            'attachments' => 'nullable|array',
            'is_read' => 'sometimes|boolean',
            'read_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $message->update($request->all());
        return response()->json($message, 200);
    }

    public function destroy($id)
    {
        $message = ChatMessage::findOrFail($id);
        $message->delete();
        return response()->json(null, 204);
    }
}