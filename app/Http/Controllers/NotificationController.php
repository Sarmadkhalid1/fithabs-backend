<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($notifications, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
        ]);

        $validated['user_id'] = auth()->id();
        
        $notification = Notification::create($validated);
        return response()->json($notification, 201);
    }

    public function show($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($notification, 200);
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'read_at' => 'nullable|date',
        ]);

        $notification->update($validated);
        return response()->json($notification, 200);
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);
        $notification->delete();
        return response()->json(null, 204);
    }
}
