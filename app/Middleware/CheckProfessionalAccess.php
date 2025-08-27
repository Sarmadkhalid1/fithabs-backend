<?php

namespace App\Http\Middleware;

use App\Models\Chat;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfessionalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $chatId = $request->route('chat') ?? $request->route('id');
        $chat = Chat::find($chatId);

        if (!$chat) {
            return response()->json(['error' => 'Chat not found'], 404);
        }

        $user = $request->user();

        $isUser = $chat->user_id === $user->id;
        $isProfessional = false;

        if ($chat->professional_type === 'coach' && $user instanceof \App\Models\Coach) {
            $isProfessional = $chat->professional_id === $user->id;
        } elseif ($chat->professional_type === 'clinic' && $user instanceof \App\Models\Clinic) {
            $isProfessional = $chat->professional_id === $user->id;
        } elseif ($chat->professional_type === 'therapist' && $user instanceof \App\Models\Therapist) {
            $isProfessional = $chat->professional_id === $user->id;
        }

        if (!$isUser && !$isProfessional) {
            return response()->json(['error' => 'Unauthorized access to chat'], 403);
        }

        return $next($request);
    }
}