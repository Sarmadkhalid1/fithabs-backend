<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Example: resourceful routes
Route::apiResource('workouts', WorkoutController::class);
