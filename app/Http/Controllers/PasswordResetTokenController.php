<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordResetTokenController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = PasswordResetToken::create([
            'email' => $request->email,
            'token' => $request->token,
            'created_at' => now(),
        ]);

        return response()->json($token, 201);
    }

    public function show($email)
    {
        $token = PasswordResetToken::findOrFail($email);
        return response()->json($token, 200);
    }

    public function destroy($email)
    {
        $token = PasswordResetToken::findOrFail($email);
        $token->delete();
        return response()->json(null, 204);
    }
}